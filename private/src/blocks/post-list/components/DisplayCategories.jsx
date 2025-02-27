import { get, isString } from 'lodash';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import LinkList from './display/LinkList.jsx';
import GridItem from './display/GridItem.jsx';
import PetitionItem from './display/PetitionItem.jsx';

// Strips HTML from a string
const strip = (html) => {
  const doc = new DOMParser().parseFromString(html, 'text/html');
  return doc.body.textContent || '';
};

// Alters the API response to the desired structure
const alterResults = (response) =>
  response.map((resp) => {
    let tags = [];
    // eslint-disable-next-line no-underscore-dangle
    if (resp._embedded['wp:term']) {
      // eslint-disable-next-line no-underscore-dangle
      tags = resp._embedded['wp:term']
        .reduce((prev, curr) => [...prev, ...curr], [])
        .map((tag) => ({
          title: tag.name,
          link: tag.link,
        }));
    }

    let featuredImage = false;
    if (resp.featured_media || resp.featured_media > 0) {
      featuredImage =
        get(
          resp,
          '_embedded["wp:featuredmedia"][0].media_details.sizes["post-half@2x"].source_url',
        ) || get(resp, '_embedded["wp:featuredmedia"][0].source_url', false);
    }

    let excerpt = strip(resp.excerpt.rendered);
    excerpt = excerpt.length > 250 ? `${excerpt.slice(0, 250)}...` : '';

    return {
      id: resp.id,
      title: resp.title.rendered,
      link: resp.link,
      tag: tags.shift(),
      excerpt,
      featured_image: featuredImage,
      date: resp.datePosted,
      authorName: resp.authorName,
    };
  });

const DisplayCategories = ({
  category,
  overrideTypes,
  amount,
  style,
  prefix,
  showAuthor,
  showPostDate,
}) => {
  // State to manage results and loading status
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(false);

  // Normalizes the category input
  const normaliseCategory = (cat = '[]') => {
    let normal = cat;

    if (isString(normal)) {
      try {
        normal = JSON.parse(normal);
      } catch (error) {
        // eslint-disable-next-line no-console
        console.debug(error);
        // eslint-disable-next-line no-console
        console.debug(normal);
      }
    }

    if (!Array.isArray(normal)) {
      normal = [normal];
    }

    normal = normal.map((val) => {
      if (isString(val)) {
        try {
          return JSON.parse(normal);
        } catch (error) {
          return false;
        }
      }

      return val;
    });

    return normal.filter(Boolean);
  };

  const fetchPostsByCategory = () => {
    let value = normaliseCategory(category);

    if (!value.length) {
      setResults([]);
      return;
    }

    setLoading(true);

    value = value.map((v) => v.value).join(',');
    let restPath = `/wp/v2/posts?category=${value}&per_page=${amount}&_embed`;

    if (overrideTypes) {
      const overrideBase = `${overrideTypes[Object.keys(overrideTypes)[0]].rest_base}`;
      restPath = `/wp/v2/${overrideBase}?category=${value}&per_page=${amount}&_embed`;
    }

    wp.apiRequest({ path: restPath }).then((res) => {
      setResults(alterResults(res));
      setLoading(false);
    });
  };

  // Fetch posts by category when component mounts or category changes
  useEffect(fetchPostsByCategory, [amount, category, overrideTypes]);

  // Normalized category for rendering
  const normalizedCategory = normaliseCategory(category);
  const hasCategory = normalizedCategory.length > 0;
  const hasResults = results.length > 0;

  // Rendering loading, no category, and no results states
  if (loading) {
    return (
      <div>
        <p>{__('Loading…', 'amnesty')}</p>
      </div>
    );
  }

  if (!hasCategory) {
    return (
      <div>
        <p className="linklist-container">{__('Select a category.', 'amnesty')}</p>
      </div>
    );
  }

  if (!hasResults) {
    return (
      <div>
        <p className="linklist-container">{__('No Items found', 'amnesty')}</p>
      </div>
    );
  }

  // Render based on the selected style
  if (style === 'list') {
    return (
      <div>
        <ul className="linkList linklist-container">
          {results.slice(0, amount).map((result) => (
            <LinkList
              key={`${prefix}-${result.id}`}
              {...result}
              showAuthor={showAuthor}
              showPostDate={showPostDate}
            />
          ))}
        </ul>
      </div>
    );
  }

  if (style === 'grid' || style === 'petition') {
    return (
      <div
        className={`grid ${[1, 2, 3, 5, 6, 7].includes(amount) ? `grid-${amount}` : 'grid-many'}`}
      >
        {results
          .slice(0, amount)
          .map((result) =>
            style === 'grid' ? (
              <GridItem key={`${prefix}-${result.id}`} {...result} />
            ) : (
              <PetitionItem key={`${prefix}-${result.id}`} {...result} />
            ),
          )}
      </div>
    );
  }

  return <div></div>;
};

export default DisplayCategories;
