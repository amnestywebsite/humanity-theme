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
