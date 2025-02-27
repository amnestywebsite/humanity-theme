import { isUndefined } from 'lodash';
import { sanitizeUrl } from '@braintree/sanitize-url';

/**
 * Ensure a URI uses HTTPS proto. Fails to an empty string.
 *
 * @param {String} string the string to force HTTPS for
 *
 * @returns {String}
 */
export const httpsOnly = (string) => {
  try {
    const url = new URL(string.replace(/^http:/, 'https:'));

    url.protocol = 'https:';

    return sanitizeUrl(url.toString());
  } catch (e) {
    // not a proper url yet
    return string;
  }
};

export const fetchMediaUrl = (mediaId, callback) => {
  if (!mediaId) {
    return Promise.resolve(null);
  }

  return wp
    .apiRequest({ path: `/wp/v2/media/${mediaId}` })
    .then((r) => callback(r?.source_url ?? r.url));
};

export const fetchMediaMetadata = (imageId, callback) => {
  if (!imageId) {
    return;
  }

  wp.apiRequest({
    path: `/wp/v2/media/${imageId}?_fields=description,caption&context=edit`,
  }).then((r) => callback({ caption: r.caption.raw, description: r.description.raw }));
};

/**
 * Generate a random identifier
 *
 * @returns {String}
 */
export const randId = () =>
  Math.random()
    .toString(36)
    .replace(/[^a-z]+/g, '')
    .substring(2, 10);

/**
 * Ensure a numeric value falls within a predetermined range
 *
 * @param {Number} min the minimum value
 * @param {Number} max the maximum value
 *
 * @returns {Number}
 */
export const createRange = (min, max) => (num) => Math.max(min, Math.min(max, num));

/**
 * Simply compares two string version values.
 *
 * Example:
 * versionCompare('1.1', '1.2') => -1
 * versionCompare('1.1', '1.1') =>  0
 * versionCompare('1.2', '1.1') =>  1
 * versionCompare('2.23.3', '2.22.3') => 1
 *
 * Returns:
 * -1 = left is LOWER than right
 *  0 = they are equal
 *  1 = left is GREATER = right is LOWER
 *  And FALSE if one of input versions are not valid
 *
 * @function
 * @param {String} left  Version #1
 * @param {String} right Version #2
 * @return {Integer|Boolean}
 * @author Alexey Bass (albass)
 * @since 2011-07-14
 */
export const versionCompare = (left, right) => {
  if (typeof left + typeof right !== 'stringstring') return false;

  const a = left.split('.');
  const b = right.split('.');
  let i = 0;
  const len = Math.max(a.length, b.length);

  // eslint-disable-next-line no-plusplus
  for (; i < len; i++) {
    if ((a[i] && !b[i] && parseInt(a[i], 10) > 0) || parseInt(a[i], 10) > parseInt(b[i], 10)) {
      return 1;
    }
    if ((b[i] && !a[i] && parseInt(b[i], 10) > 0) || parseInt(a[i], 10) < parseInt(b[i], 10)) {
      return -1;
    }
  }

  return 0;
};

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
 * Generate a random alphanumeric key of {length} length
 *
 * @param {Integer} length the length of key to generate
 *
 * @return {String}
 */
export const key = (length) => {
  const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  const charLength = characters.length;
  let result = '';

  for (let i = 0; i < length; i += 1) {
    result += characters.charAt(Math.floor(Math.random() * charLength));
  }

  return result;
};

/**
 * Convert the first character of a string to uppercase
 *
 * @param {String} str the string to texturise
 *
 * @returns {String}
 */
export const ucfirst = (str = '') => str.charAt(0).toUpperCase() + str.slice(1);

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

/**
 * Convert an array to a string
 *
 * @param {Array|String} val the value to stringify
 *
 * @returns {String}
 */
export const stringify = (val) => (isType(val, 'array') ? val.join('') : val);

/**
 * Trim line breaks from a string
 *
 * @param {String} str the string to trim
 *
 * @returns {String}
 */
export const trimBr = (str = '') => str.replace(/\u003cbr\/\u003e/gi, ' ').trim();

/**
 * Convert line breaks to paragraphs
 *
 * @param {String} str the string to texturise
 *
 * @returns {String}
 */
export const brToP = (str = '') =>
  stringify(str)
    .replace(/((<|&lt;|\u003c)br[^>]*?>)+$/, '') // trim trailing <br>s
    .split(/(?:(?:(?:<|&lt;|\u003c)br[^>]*?>)+)/) // split on one or more embedded <br>s
    .map((p) => `<p>${p}</p>`) // make paragraphs
    .join(''); // make a string

/**
 * Retrieve media data and pass response to a callback
 *
 * @param {Number} id the media to fetch
 * @param {Function} callback the callback to execute
 * @param {Object} state previous data, if any
 *
 * @return {Promise}
 */
export const fetchMediaData = (id, callback, state = {}) => {
  if (!id) {
    return Promise.resolve(callback({}));
  }

  if (state?.id === id) {
    return Promise.resolve(callback(state));
  }

  return wp
    .apiFetch({
      path: `/wp/v2/media/${id}?_fields=id,source_url,caption,description&context=edit`,
    })
    .then((media) =>
      callback({
        id: media.id,
        url: media.source_url,
        caption: media.caption.raw,
        copyright: media.description.raw,
      }),
    );
};

/**
 * Create a hash usable in a HTML tag's id attribute
 *
 * @param {String} string the string to use as the seed
 *
 * @returns {String}
 */
export async function makeHtmlId(string) {
  return Array.from(
    new Uint8Array(await crypto.subtle.digest('SHA-1', new TextEncoder().encode(string))),
    (byte) => byte.toString(16).padStart(2, 'a'),
  ).join('');
}
