/* eslint-disable import/prefer-default-export */

/**
 * Retrieve media data and pass response to a callback
 *
 * @param {Number} id the media to fetch
 * @param {Function} callback the callback to execute
 * @param {Object} state previous data, if any
 *
 * @return {Promise}
 */
export function fetchMediaData(id, callback, state = {}) {
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
}
