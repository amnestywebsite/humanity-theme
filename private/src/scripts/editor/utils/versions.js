/* eslint-disable import/prefer-default-export */

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
export function versionCompare(left, right) {
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
}
