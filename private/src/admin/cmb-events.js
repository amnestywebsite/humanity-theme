import { isFunction } from 'lodash';
import { applyFilters } from '@wordpress/hooks';

let globalChangeEvent;

/**
 * Find a parent element, optionally by selector
 *
 * @param {Node} $node the origin node
 * @param {string} selector selector to optionally traverse for
 *
 * @return {Node|null} the found parent
 */
const parent = ($node, selector = '') => {
  let elem = $node.parentElement;

  if (!selector) {
    return elem;
  }

  while (elem != null) {
    if (elem.matches(selector)) {
      return elem;
    }

    elem = elem.parentElement;
  }

  return null;
};

/**
 * Handle all change events on the form
 *
 * @param {Event} event the current event
 * @param {Node} $form the form element
 * @param {array} $groups field group elements
 *
 * @returns {Event}
 */
const handleChangeEvents = (event, $form) => {
  const { target } = event;
  const name = target.name.replace(/^.*\[([^\]]+)\]$/, '$1');

  $form.removeEventListener('change', globalChangeEvent);

  const cb = applyFilters(`amnesty.cmb2.change.${name}`, null, event, $form);

  if (isFunction(cb)) {
    cb(event, $form);
  }

  $form.addEventListener('change', globalChangeEvent);
};

document.addEventListener('DOMContentLoaded', () => {
  const $cmb2table = document.querySelector('form .cmb2-wrap.form-table');

  if (!$cmb2table) {
    return;
  }

  const $form = parent($cmb2table, 'form');

  if (!$form) {
    return;
  }

  globalChangeEvent = (e) => handleChangeEvents(e, $form);
  $form.addEventListener('change', globalChangeEvent);
});
