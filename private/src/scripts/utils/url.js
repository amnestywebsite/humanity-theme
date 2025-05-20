const { isObject } = window.lodash;

/**
 * Convert a query string into an object
 *
 * @param {String} string the query string
 * @returns {Object}
 */
export const queryStringToObject = (string) => {
  const object = {};

  string
    .replace('?', '')
    .split('&')
    .forEach((keypair) => {
      const [key, value] = keypair.split('=');
      if (key) {
        object[key] = value?.split(',');
      }
    });

  return object;
};

/**
 * Convert an object to a query string
 *
 * @param {Object} object the object to transform
 * @returns string
 */
export const objectToQueryString = (object) =>
  Object.keys(object)
    .map((prop) => {
      let val = object[prop];

      if (!val) {
        return null;
      }

      if (Array.isArray(val)) {
        val = val.join(',');
      }

      if (isObject(val)) {
        val = objectToQueryString(object);
      }

      return `${encodeURIComponent(prop)}=${encodeURIComponent(val).replace(/%2C/g, ',')}`;
    })
    .filter(Boolean)
    .join('&');

/**
 * Convert a list of DOM nodes to an object
 *
 * @param {DOMNodeList} inputs the list of inputs
 * @returns {Object}
 */
export const inputsToObject = (inputs) => {
  const request = {};

  inputs.forEach((input) => {
    if (!input.value || !input.name) {
      return;
    }

    if (input.name === 's') {
      request[input.name] = input.value.replace(/\s+/g, '+');
    }

    if (input.nodeName === 'SELECT') {
      request[input.name] = input.value;
      return;
    }

    if (input.type === 'checkbox') {
      if (!input.checked) {
        return;
      }

      if (input.name.indexOf('[]') === -1) {
        request[input.name] = input.value;
        return;
      }

      const arrName = input.name.replace(/\[\]/, '');
      if (!Object.hasOwnProperty.call(request, arrName)) {
        request[arrName] = [];
      }

      request[arrName].push(input.value);
    }
  });

  return request;
};
