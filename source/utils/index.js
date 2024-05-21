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
    return;
  }

  return wp.apiRequest({
    path: `/wp/v2/media/${mediaId}`,
  }).then((r) => callback(r?.source_url ?? r.url));
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
