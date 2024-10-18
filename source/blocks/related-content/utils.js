import { __ } from '@wordpress/i18n';

/**
 * Retrieve featured image info from a post object
 *
 * @param {Object} post the post object
 * @param {String} size the image size
 *
 * @returns {(String|null)} the image URI, or null
 */
export const getFeaturedImage = (item, size) => {
  const image = item?.featuredImage;
  return image?.sizes?.[size]?.url ?? image?.url;
};

/**
 * Retrieve the first location term that has the type "default"
 *
 * @param {Object} terms the post's terms
 *
 * @returns {(Object|null)} the found term, or null
 */
export const getCountryTerm = (terms) => {
  let country = null;

  terms.forEach((term) => {
    if (term.type !== 'default') {
      return;
    }

    if (country) {
      return;
    }

    country = term;
  });

  if (!country) {
    return country;
  }

  // mock a post response so that {getProminentTerm()} can find it
  return {
    ...country,
    _embedded: {
      'wp:term': [
        [
          {
            // translators: [admin]
            name: __('Country', 'amnesty'),
            link: '#',
          },
        ],
      ],
    },
  };
};
