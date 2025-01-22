const TYPE_TIME = 600; // max delay between keypresses
let timer; // timer for keypresses
let keys = []; // list of keys pressed
let focused = null; // the focused element

/**
 * Perform a search through the items in a list
 *
 * @param {String} groupSelector the group selector
 * @param {Function} textFilter the text filter function
 *
 * @returns {Function}
 */
const searchList =
  ({ groupSelector, textFilter }) =>
  (event, options) => {
    const { key } = event;

    /**
     *
     * @param {Array<int,DOMNode>} matches the list of matching options
     * @returns {Void}
     */
    const searchSelect = (matches) => {
      if (!matches.length) {
        return;
      }

      let currentIndex = -1;

      // if we have a focused list item, use that as the starting point
      if (document.activeElement.closest(groupSelector)) {
        focused = document.activeElement;
        currentIndex = matches.indexOf(focused);
      }

      const nextIndex = currentIndex + 1;
      const toBeSelected = matches[nextIndex] || matches[0];

      if (toBeSelected === currentIndex) {
        return;
      }

      toBeSelected.focus();
      toBeSelected.scrollIntoView({
        behavior: 'smooth',
        block: 'nearest',
        inline: 'nearest',
      });
    };

    clearTimeout(timer);

    keys.push(key.toLowerCase());

    // find the FIRST option that most closely matches our keys
    // if that first one is already selected, go to NEXT option
    const stringMatch = keys.join('');
    // attempt an exact match
    const exactMatches = options.filter((option) => textFilter(option, stringMatch));

    if (exactMatches.length) {
      searchSelect(exactMatches);
    } else {
      // plan b - first character match
      const firstChar = stringMatch[0];
      searchSelect(options.filter((option) => textFilter(option, firstChar)));
    }

    timer = setTimeout(() => {
      keys = [];
    }, TYPE_TIME);
  };

export default searchList;
