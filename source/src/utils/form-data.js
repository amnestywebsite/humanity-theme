/* eslint-disable import/prefer-default-export */
/**
 * Retrieve form's current data
 * @param {DOMNode} form the form element
 * @returns {Object}
 */
export const getFormInputData = (form) => {
  const formData = new FormData(form);
  const data = {};

  [...formData.entries()].forEach((entry) => {
    const [name, value] = entry;

    if (name.indexOf('[]') === -1) {
      data[name] = value;
      return;
    }

    const arrName = name.replace(/\[\]$/, '');
    if (!Object.hasOwnProperty.call(data, arrName)) {
      data[arrName] = [];
    }

    data[arrName].push(value);
  });

  return data;
};
/* eslint-enable import/prefer-default-export */
