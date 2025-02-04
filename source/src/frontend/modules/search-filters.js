import { getFormInputData } from '../../utils/form-data';
import { objectToQueryString, queryStringToObject } from '../../utils/url';

const { xor } = lodash;

let searchForm;
let searchInput;
let searchInputButton;
let defaultFilters;
let advancedFilters;
let applyFiltersButton;
let initialFormData;

/**
 * Hijack submit and redirect instead.
 * We can keep the URI nice and pretty this way.
 * @param {Event} event the submit event
 */
const handleSubmitEvents = (event) => {
  event.preventDefault();

  const action = searchForm.getAttribute('action');
  const formData = getFormInputData(searchForm);
  let actionData = {};

  const [url, qsa] = action.split('?');

  if (qsa) {
    actionData = queryStringToObject(qsa);
  }

  const urlArgs = { ...actionData, ...formData };

  // ideally there would be a more extensible way to do this
  if (!urlArgs?.qyear && urlArgs?.qmonth) {
    // "unset" month if year is not set.
    // UX states that users cannot select a month without a year.
    urlArgs.qmonth = '';
  }

  const hasQsa = Object.keys(urlArgs).some((key) => !!urlArgs[key]);

  // if there's no query string, omit the `?` from the target URL
  if (!hasQsa) {
    window.location = url;
    return;
  }

  const queryString = objectToQueryString(urlArgs);
  const targetUrl = `${url}?${queryString}`;

  window.location = targetUrl;
};

/**
 * Handle click events within the search form
 * @param {MouseEvent} event the event object
 * @returns {Void}
 */
const handleClickEvents = (event) => {
  // filters button clicked - toggle advanced filter visibility
  if (event.target.matches('.toggle-search-filters')) {
    event.target.classList.toggle('is-expanded');
    defaultFilters.classList.toggle('is-expanded');
    advancedFilters.classList.toggle('is-active');
    return;
  }

  // if an additional filter value changes, we enable the "Apply" button
  // these fields are multi-selection inputs
  if (event.target.matches('.additional-filters input[type="checkbox"]')) {
    const parent = event.target.closest('fieldset');
    const inputs = Array.from(parent.querySelectorAll('input[type="checkbox"]'));
    const values = inputs.filter((i) => i.checked).map((i) => i.value);
    const initial = initialFormData?.[event.target.name.replace(/\[\]$/, '')];

    if (xor(initial, values).length) {
      applyFiltersButton.classList.add('is-active');
      applyFiltersButton.removeAttribute('disabled');
    } else {
      applyFiltersButton.classList.remove('is-active');
      applyFiltersButton.setAttribute('disabled', 'disabled');
    }
  }
};

/**
 * Handle change events within the search form
 *
 * @param {Event} event the event object
 *
 * @returns {Void}
 */
const handleChangeEvents = (event) => {
  if (event?.data?.input.closest('.checkboxGroup').matches('.autosubmit')) {
    searchForm.requestSubmit();
  }
};

/**
 * When the search input value changes, change its button state
 * @param {KeyboardEvent} event the event object
 */
const handleKeyupEvents = (event) => {
  if (event.target.value !== initialFormData?.s) {
    searchInputButton.classList.add('is-active');
    searchInputButton.removeAttribute('disabled');
  } else {
    searchInputButton.classList.remove('is-active');
    searchInputButton.setAttribute('disabled', 'disabled');
  }
};

/**
 * Setup the search form
 */
const setupSearch = () => {
  searchInput = searchForm.querySelector('input[type="text"][name="s"]');
  searchInputButton = searchForm.querySelector('.search-input button[type="submit"]');
  defaultFilters = searchForm.querySelector('.default-search-filters .basic-filters');
  advancedFilters = searchForm.querySelector('.additional-filters');
  applyFiltersButton = document.getElementById('search-filters-submit');
  initialFormData = getFormInputData(searchForm);

  searchForm.addEventListener('submit', handleSubmitEvents);
  searchForm.addEventListener('click', handleClickEvents);
  searchForm.addEventListener('change', handleChangeEvents);
  // target the input directly so we don't have to filter keyboard navigation
  searchInput.addEventListener('keyup', handleKeyupEvents);
};

export default function searchFilters() {
  searchForm = document.querySelector('.horizontal-search');

  if (searchForm) {
    setupSearch();
  }
}
