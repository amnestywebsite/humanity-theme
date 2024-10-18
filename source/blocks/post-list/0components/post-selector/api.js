import { __ } from '@wordpress/i18n';

/**
 * Makes a get request to the PostTypes endpoint.
 *
 * @returns {Promise<any>}
 */
export const getPostTypes = () => wp.apiRequest({ path: '/wp/v2/types' });

/**
 * Fetch taxonomies for the current post type
 *
 * @returns {Promise<any>}
 */
export const getTaxonomies = () => {
  const type = wp.data.select('core/editor').getEditedPostAttribute('type');
  return wp.apiRequest({ path: `/wp/v2/taxonomies/?type=${type}` }).then((taxes) => taxes);
};

/**
 * Fetch taxonomy terms
 *
 * @param {String} slug the taxonomy slug
 * @returns {Promise<any>}
 */
export const getTerms = (slug) =>
  wp.apiRequest({
    path: wp.url.addQueryArgs(`/wp/v2/${slug}`, {
      per_page: slug === 'category' ? 100 : 250,
    }),
  });

/**
 * Makes a get request to the desired post type and builds the query string based on an object.
 *
 * @param {string|boolean} restBase - rest base for the query.
 * @param {object} args
 * @returns {Promise<any>}
 */
export const getPosts = ({ restBase = false, ...args }, taxonomy, term) => {
  const queryString = Object.keys(args)
    .map((arg) => `${arg}=${args[arg]}`)
    .join('&');

  return wp.apiRequest({
    path: `/wp/v2/${restBase}?${taxonomy}=${term}&${queryString}&_embed`,
  });
};

/**
 * Makes a get request to the desired post type and builds the query string based on an object.
 *
 * @param {string|boolean} restBase - rest base for the query.
 * @param {object} args
 * @returns {Promise<any>}
 */
export const getPostsFromTerms = ({ ...args }, taxonomy, terms) => {
  if (!terms.length) {
    return new Promise((resolve, reject) => {
      reject(__('No terms selected', 'amnesty'));
    });
  }

  let allTermsString = '';
  if (terms) {
    allTermsString = terms.map((arg) => `${arg.value}`).join('+');
  }

  const queryString = Object.keys(args)
    .map((arg) => `${arg}=${args[arg]}`)
    .join('&');

  return wp.apiRequest({
    path: `/wp/v2/posts?${taxonomy.value}=${allTermsString}&${queryString}&_embed`,
  });
};

/**
 * Makes a get request to the desired post type and builds the query string based on an object.
 *
 * @param {string|boolean} restBase - rest base for the query.
 * @param {object} args
 * @returns {Promise<any>}
 */
export const getPostsFromAuthors = ({ ...args }, authors) => {
  const queryString = Object.keys(args)
    .map((arg) => `${arg}=${args[arg]}`)
    .join('&');

  return wp.apiRequest({
    path: `/wp/v2/posts?author=${authors}&${queryString}&_embed`,
  });
};
