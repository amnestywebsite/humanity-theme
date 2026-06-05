/* eslint-disable import/prefer-default-export */
const { _ } = window;

export function recursivelyReplaceHighlights(item) {
  if (item instanceof Object && item?.value) {
    /* eslint-disable-next-line no-param-reassign */
    item.value = _.escape(item.value)
      .replace(/__ais-highlight__/g, '<em>')
      .replace(/__\/ais-highlight__/g, '</em>');
  }

  return item;
}
