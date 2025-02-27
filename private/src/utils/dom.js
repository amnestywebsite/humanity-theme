// find an element's target element
export const findTarget = (element, attribute = 'toggle') => {
  const selector = element.dataset[attribute];
  let target;

  if (selector.indexOf('^') === 0) {
    target = element.parentElement;

    if (selector === '^parent') {
      return target;
    }

    if (selector === 'self') {
      return element;
    }

    return target.querySelector(selector.replace(/^\^/, ''));
  }

  return document.querySelector(selector);
};

// hide/show element
export const toggleElementVisibility = (element) => {
  // toggle active class name
  element.classList.toggle('is-open');
  const isOpen = element.classList.contains('is-open');

  if (element.getAttribute('tabindex') !== null) {
    element.setAttribute('tabindex', isOpen ? '0' : '-1');
  }

  if (element.getAttribute('aria-hidden') !== null) {
    element.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
  }

  if (element.getAttribute('aria-expanded') !== null) {
    element.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  }

  if (element.style.visibility !== '') {
    // eslint-disable-next-line no-param-reassign
    element.style.visibility = isOpen ? 'visible' : 'hidden';
  }

  const focusableChildren = Array.from(element.querySelectorAll('[tabindex]'));

  if (isOpen) {
    focusableChildren.forEach((el) => el.setAttribute('tabindex', '0'));
  } else {
    focusableChildren.forEach((el) => el.setAttribute('tabindex', '-1'));
  }

  // target has descendent with autofocus
  if (element.dataset.autofocus) {
    const focusInto = element.querySelector(element.dataset.autofocus);

    // found autofocus target
    if (focusInto) {
      setTimeout(() => focusInto.focus({ preventScroll: true }), 0);
    }
  }
};

export const hasOpenOrActiveItems = (cname = '') =>
  cname.indexOf('-open') !== -1 || cname.indexOf('-active') !== -1;

export default {
  findTarget,
  hasOpenOrActiveItems,
  toggleElementVisibility,
};
