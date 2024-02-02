import { findTarget, hasOpenOrActiveItems, toggleElementVisibility } from '../utils/dom';

const html = document.documentElement;
const classCache = html.className;
const scrollbarWidth = window.innerWidth - html.offsetWidth;
let scrollPos = 0;
let togglableElements;

// pad the document when something is open, to prevent
// reflow from scrollbar visibility change
const setDocumentPadding = () => {
  if (hasOpenOrActiveItems(html.className)) {
    document.body.style.paddingRight = `${scrollbarWidth}px`;
  } else {
    document.body.style.paddingRight = '';
  }
};

// toggle element visibility on trigger clicks
const triggerToggle = (event) => {
  const { state, self } = event.target.dataset;
  const element = findTarget(event.target);

  // no target element
  if (!element) {
    return;
  }

  event.preventDefault();

  // set class on event target
  if (self) {
    event.target.classList.toggle(self);
  }

  toggleElementVisibility(element);

  // set state on html
  if (state) {
    html.classList.toggle(state);
  }
};

// dispatch click target changes
const handleClickEvents = (event) => {
  const { target } = event;
  const { dataset } = target;

  // click event is on a toggle
  if (dataset.toggle) {
    // no state set on html
    if (html.className === classCache) {
      scrollPos = html.scrollTop;
    }

    triggerToggle(event);

    if (html.className === classCache) {
      html.scrollTop = scrollPos;
    }
  }

  // it's on the background overlay (something is open in front)
  if (target.classList.contains('overlay')) {
    html.setAttribute('class', classCache);
    // close any open items
    togglableElements.forEach((elem) => {
      const tmpTarget = findTarget(elem);

      if (!tmpTarget || !tmpTarget.classList.contains('is-open')) {
        return;
      }

      toggleElementVisibility(tmpTarget);
    });
  }

  setDocumentPadding();
};

// collapse open items when escape key is pressed
const handleKeyEvents = (event) => {
  const { code } = event;

  // remove all stateful classes from the document
  if (['Esc', 'Escape'].indexOf(code) !== -1) {
    html.setAttribute('class', classCache);
    setDocumentPadding();
  }
};

const init = () => {
  togglableElements = Array.from(html.querySelectorAll('[data-toggle]'));

  html.addEventListener('click', handleClickEvents);
  html.addEventListener('keydown', handleKeyEvents);
};

export default init;
