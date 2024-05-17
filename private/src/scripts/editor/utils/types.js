import { ucfirst } from './strings';

const { isUndefined } = lodash;

/**
 * Validate whether a value is a boolean type
 *
 * @param {Mixed} val the value to validate
 *
 * @return {Boolean}
 */
export const validateBool = (val) => {
  if (isUndefined(val)) {
    return false;
  }

  if (val === true || val === false) {
    return val;
  }

  if (['1', 'true', 'yes', 'on', 'y'].indexOf(`${val}`.toLowerCase()) !== -1) {
    return true;
  }

  if (['0', 'false', 'no', 'off', 'n'].indexOf(`${val}`.toLowerCase()) !== -1) {
    return false;
  }

  return !!val;
};

/**
 * Check whether a variable is of the type specified
 *
 * @param {Mixed} val the value to test
 * @param {String} type the type to check
 *
 * @returns {Boolean}
 */
export const isType = (val, type) =>
  Object.prototype.toString.call(val) === `[object ${ucfirst(type)}]`;
