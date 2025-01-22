const isEscape = ({ key }) => ['Esc', 'Escape'].indexOf(key) !== -1;
const isSpace = ({ key }) => ['Space', 'Spacebar', ' '].indexOf(key) !== -1;
const isEnter = ({ key }) => ['Enter'].indexOf(key) !== -1;
const isTab = ({ key }) => ['Tab'].indexOf(key) !== -1;

const isArrowUp = ({ key }) => ['ArrowUp', 'Up'].indexOf(key) !== -1;
const isArrowDown = ({ key }) => ['ArrowDown', 'Down'].indexOf(key) !== -1;
const isArrowLeft = ({ key }) => ['ArrowLeft', 'Left'].indexOf(key) !== -1;
const isArrowRight = ({ key }) => ['ArrowRight', 'Right'].indexOf(key) !== -1;

const isLetter = ({ key }) => /[a-zA-Z]$/.test(key);
const isNumber = ({ key }) => /[0-9]$/.test(key);

const isDirectional = (event) =>
  isArrowUp(event) || isArrowDown(event) || isArrowLeft(event) || isArrowRight(event);

// nb: the [type]Key props are always false unless the event is for a keypress whilst a modifier is held
const hasModifier = ({ metaKey, ctrlKey, altKey, shiftKey }) =>
  metaKey || ctrlKey || altKey || shiftKey;

export default {
  isEscape,
  isSpace,
  isEnter,
  isTab,

  isArrowUp,
  isArrowDown,
  isArrowLeft,
  isArrowRight,
  isDirectional,

  isLetter,
  isNumber,

  hasModifier,
};
