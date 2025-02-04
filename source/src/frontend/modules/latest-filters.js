import { inputsToObject, objectToQueryString, queryStringToObject } from '../../utils/url';

const { merge } = lodash;

const handleFilterSubmit = (event) => {
  event.preventDefault();

  const [formTarget, queryString = ''] = event.target.getAttribute('action').split('?');
  const inputs = Array.from(event.target.querySelectorAll('input,select'));
  const request = inputsToObject(inputs);
  const { search } = window.location;

  const params = merge(queryStringToObject(queryString), queryStringToObject(search), request);

  window.location = [formTarget, objectToQueryString(params)].filter(Boolean).join('?');
};

export default function latestFilters() {
  const form = document.getElementById('filter-form');

  if (form) {
    form.addEventListener('submit', handleFilterSubmit);
  }
}
