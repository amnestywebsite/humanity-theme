const { apiFetch } = wp;
const { sprintf } = wp.i18n;

export const findImage = async (basename, year, month) => {
  let found = false;

  const results = await apiFetch({
    path: sprintf('wp/v2/media?search=%s', encodeURIComponent(basename)),
  });

  if (results.length === 0) {
    return found;
  }

  if (results.length === 0) {
    return results[0];
  }

  found = results.filter((result) => {
    const guid = result.guid.rendered;
    const fileYear = guid.replace(/^.*\/(\d{4})\/\d{2}\/.*\.[a-z]{3,4}$/, '$1');
    const fileMonth = guid.replace(/^.*\/\d{4}\/(\d{2})\/.*\.[a-z]{3,4}$/, '$1');

    if (fileYear !== year) {
      return false;
    }

    if (fileMonth !== month) {
      return false;
    }

    return true;
  });

  return found[0];
};

export const fetchImageData = (imageId, callback) => {
  if (!imageId) {
    return;
  }

  wp.apiRequest({
    path: `/wp/v2/media/${imageId}?_fields=description,caption&context=edit`,
  }).then((r) => callback({ caption: r.caption.raw, description: r.description.raw }));
};
