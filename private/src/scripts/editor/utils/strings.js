const { isType } = lodash;

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
 * Generate a random identifier
 *
 * @returns {String}
 */
export const randId = () =>
  Math.random()
    .toString(36)
    .replace(/[^a-z]+/g, '')
    .substring(2, 10);
