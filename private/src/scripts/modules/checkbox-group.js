import keyboard from '../utils/keyboard';
import searchList from '../utils/list-search';

const groupCache = new Map();
const siblingGroupsCache = new Map();
const groupsItemsCache = new Map();

/**
 * Close a group
 *
 * @param {DOMNode} group the group to close
 * @param {DOMNode} button the group's button
 *
 * @returns {Void}
 */
const closeGroup = (group, button) => {
  button.classList.remove('is-active');
  button.setAttribute('aria-expanded', false);
};

/**
 * Close all open groups
 *
 * @returns {Void}
 */
const closeGroups = () => {
  groupCache.forEach(closeGroup);
};

/**
 * Toggle a group's visibility
 *
 * @param {DOMNode} button the group's button
 *
 * @returns {Void}
 */
const toggleGroup = (button) => {
  if (!button.classList.contains('is-active')) {
    closeGroups();
  }

  button.classList.toggle('is-active');
  button.setAttribute('aria-expanded', button.classList.contains('is-active'));
};

/**
 * Perform a search through the items in a group
 *
 * @param {Event} event the event object
 * @param {DOMNode} parent the parent group
 *
 * @returns {Void}
 */
const search = searchList({
  groupSelector: '.checkboxGroup-list',
  textFilter: (o, term) => o.nextElementSibling.textContent.toLowerCase().indexOf(term) === 0,
});

/**
 * Fire a change event on an input
 *
 * @param {DOMNode} input the input to fire the event on
 * @param {Object} data any data to pass along with the event
 *
 * @returns {Void}
 */
const fireChangeEvent = (input, data = {}) => {
  const event = new Event('change', { bubbles: true });
  event.data = data;
  input.dispatchEvent(event);
};

/**
 * Toggle visibility of groups on keyboard events
 *
 * @param {Event} event the event object
 *
 * @returns {Void}
 */
const handleKeyboardEvents = (event) => {
  const { target } = event;

  if (keyboard.isEscape(event)) {
    closeGroups();
    return;
  }

  // perform a search of items if the user is typing
  if (keyboard.isLetter(event) && !keyboard.hasModifier(event)) {
    const parent = target.closest('.checkboxGroup');
    const options = groupsItemsCache.get(parent);
    if (parent && options) {
      search(event, options);
    }
    return;
  }

  // submit if the user is selecting a radio button
  if (keyboard.isEnter(event) || keyboard.isSpace(event)) {
    const parent = target.closest('.checkboxGroup');

    if (target.matches('.checkboxGroup-button')) {
      // clicking a group button; toggle active state
      toggleGroup(target);
      return;
    }

    if (parent?.matches('.is-nav') && target?.matches('input[type="radio"]')) {
      const { value } = target.parentElement.dataset;

      // if the item's data-value attribute is empty, no-op.
      if (!value) {
        return;
      }

      // use the item's data-value attribute to perform the navigation.
      const url = new URL(value);
      window.location.href = url.href;

      return;
    }

    // a radio has been clicked; trigger its action by submitting the form
    if (parent?.matches('.is-form') && target?.matches('input[type="radio"]')) {
      parent.submit();
    }

    if (parent?.matches('.is-control') && target?.matches('input[type="radio"]')) {
      toggleGroup(parent.querySelector('.checkboxGroup-button'));
      fireChangeEvent(target, { input: target });
    }

    return;
  }

  // would normally return-early, but we may add to this later
  if (keyboard.isDirectional(event)) {
    const parent = target.closest('.checkboxGroup');

    // we only care about checkbox group events
    if (!parent) {
      return;
    }

    // with modifiers, assume the user is doing something we don't want to interfere with
    if (keyboard.hasModifier(event)) {
      return;
    }

    if (keyboard.isArrowDown(event)) {
      // group will have a value if target is the button
      const group = groupCache.get(target);

      if (!group) {
        return;
      }

      // if the event is on the button, select the first item in the list
      event.preventDefault();
      group.querySelector('.checkboxGroup-item input').focus();

      return;
    }

    if (keyboard.isArrowUp(event)) {
      const group = groupCache.get(target);

      if (!group) {
        return;
      }

      // if the event is on the button, select the last item in the list
      event.preventDefault();
      group.querySelector('.checkboxGroup-item:last-child').focus();

      return;
    }

    if (keyboard.isArrowLeft(event) || keyboard.isArrowRight(event)) {
      event.preventDefault();
    }
  }
};

/**
 * Toggle visibility of groups on mouse events
 *
 * @param {Event} event the event object
 *
 * @returns {Void}
 */
const handleMouseEvents = (event) => {
  if (!(event instanceof MouseEvent)) {
    return;
  }

  const { target } = event;
  // get the group itself
  const parent = target.closest('.checkboxGroup');

  // close open groups on outside clicks
  if (!parent) {
    closeGroups();
    return;
  }

  if (target.matches('.checkboxGroup-button')) {
    // clicking a group button; toggle active state
    toggleGroup(target);
    return;
  }

  if (parent?.matches('.is-nav') && target?.matches('.checkboxGroup-item label')) {
    const { value } = target.dataset;

    // if the item's data-value attribute is empty, no-op.
    if (!value) {
      return;
    }

    // use the item's data-value attribute to perform the navigation.
    const url = new URL(value);
    window.location.href = url.href;

    return;
  }

  if (parent?.matches('.is-form') && target?.matches('.checkboxGroup-item label')) {
    target.previousElementSibling.checked = true;
    parent.submit();
    return;
  }

  if (parent?.matches('.is-control') && target?.matches('.checkboxGroup-item label')) {
    target.previousElementSibling.checked = true;

    const button = parent.querySelector('.checkboxGroup-button');
    button.textContent = target.textContent;

    toggleGroup(button);
    fireChangeEvent(target, { input: target.previousElementSibling });
    return;
  }

  // cache siblings for faster access later
  if (!siblingGroupsCache.has(parent)) {
    const { children } = parent.parentElement;
    const siblings = Array.from(children).filter((child) => child.matches('.checkboxGroup'));

    siblingGroupsCache.set(parent, siblings);
  }

  // close other groups
  siblingGroupsCache
    .get(parent)
    .filter((child) => child !== parent)
    .forEach((sibling) => {
      closeGroup(sibling, sibling.querySelector('.checkboxGroup-button'));
    });
};

export default () => {
  const groups = Array.from(document.querySelectorAll('.checkboxGroup'));

  if (!groups.length) {
    return;
  }

  groups.forEach((group) => {
    const button = group.querySelector('.checkboxGroup-button');
    groupCache.set(button, group);

    const items = group.querySelectorAll('.checkboxGroup-item input');
    groupsItemsCache.set(group, Array.from(items));
  });

  document.addEventListener('keyup', handleKeyboardEvents);
  document.addEventListener('mouseup', handleMouseEvents);
};
