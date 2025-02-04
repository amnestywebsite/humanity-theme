let pageHeader;
let menuToggle;
let mobileMenu;
let subMenus = [];

// if menu has lost focus inadvertently, restore it
const setupFocusTrap = () => {
  const lastMenuItem = pageHeader.querySelector('.mobile-menu > ul > li:last-of-type');
  const menuItemClassList = `.${Array.from(lastMenuItem.classList).join('.')}`;
  let previousFocus;

  mobileMenu.addEventListener('focusout', (event) => {
    previousFocus = event.target;
  });

  document.addEventListener('focusin', (event) => {
    const closestMenuItemToPrevious = previousFocus?.closest(menuItemClassList);
    const originWasLastItem = previousFocus === lastMenuItem.firstElementChild;
    const originWasChildOfLastItem = closestMenuItemToPrevious === lastMenuItem;

    // previous element wasn't in the mobile nav, or wasn't the last item in it
    if (!originWasLastItem && !originWasChildOfLastItem) {
      return;
    }

    // focus is still within the mobile nav
    if (event.target.closest('.mobile-menu')) {
      return;
    }

    event.preventDefault();

    previousFocus = undefined;
    menuToggle.focus();
  });
};

// close all sub menus
const closeSubMenus = (exclude = null) => {
  subMenus.forEach((sub) => {
    if (sub !== exclude) {
      sub.classList.remove('is-open');
    }
  });
};

// enable sub-nav open/close on click
const handleClickEvents = (event) => {
  const { target } = event;

  if (!target.parentElement.matches('.menu-item-has-children')) {
    return;
  }

  // prevent '#' from being appended to URI
  event.preventDefault();
  event.stopPropagation(); // for screen readers
  event.target.focus(); // for screen readers

  closeSubMenus(event.target.parentElement);

  event.target.parentElement.classList.toggle('is-open');
};

// handle sub-nav open/close on interactions; handle exit
const handleKeyboardEvents = (event) => {
  const { code } = event;

  // accept keyboard events as clicks
  if (['Space', 'Spacebar', 'Enter'].indexOf(code) !== -1) {
    // but not if a modifier key is being pressed at the same time
    if (!event.shiftKey && !event.metaKey && !event.ctrlKey && !event.altKey) {
      handleClickEvents(event);
    }
    return;
  }

  // remove all stateful classes from the menu
  if (['Esc', 'Escape'].indexOf(code) !== -1) {
    closeSubMenus();
  }
};

// close mobile menu when burger is shift-tabbed out of
const maybeCloseMobileMenu = (event) => {
  const { code, shiftKey } = event;

  if (code === 'Tab' && shiftKey) {
    const closeMenuEvent = new KeyboardEvent('keydown', {
      altKey: false,
      bubbles: true,
      cancelBubble: false,
      cancelable: true,
      charCode: 0,
      code: 'Escape',
      ctrlKey: false,
      key: 'Escape',
      keyCode: 27,
      metaKey: false,
      shiftKey: false,
      which: 27,
    });

    menuToggle.dispatchEvent(closeMenuEvent);
  }
};

const init = () => {
  pageHeader = document.querySelector('.page-header');
  mobileMenu = document.getElementById('mobile-menu');

  if (!pageHeader || !mobileMenu) {
    return;
  }

  menuToggle = mobileMenu.parentElement.querySelector('.burger');
  subMenus = Array.from(mobileMenu.querySelectorAll('.menu-item-has-children'));

  mobileMenu.addEventListener('click', handleClickEvents);
  mobileMenu.addEventListener('keydown', handleKeyboardEvents);
  menuToggle.addEventListener('keydown', maybeCloseMobileMenu);

  setupFocusTrap();
};

export default init;
