import React, { useState, useEffect } from 'react';
import LinkList from './display/LinkList.jsx';
import GridItem from './display/GridItem.jsx';
import * as api from './post-selector/api.js';
import { difference, isString, isEqual } from 'lodash';
import { __ } from '@wordpress/i18n';

/**
 * DisplayTaxonomies Functional Component
 * This component fetches and displays posts based on taxonomy and terms.
 */
const DisplayTaxonomies = ({ taxonomy, terms, style, prefix, showAuthor, showPostDate, amount, category }) => {
  // State variables for managing results and loading status
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(false);

  // Normalizes category input
  const normaliseCategory = (category = '[]') => {
    let normal = isString(category) ? JSON.parse(category) : category;

    if (!Array.isArray(normal)) {
      normal = [normal];
    }

    return normal
      .map(val => (isString(val) ? JSON.parse(val) : val))
      .filter(Boolean);
  };

  // Fetch posts when component mounts or when taxonomy/terms change
  useEffect(() => {
    if (taxonomy) {
      fetchPostsByTaxonomy();
    }
  }, [taxonomy, terms]); // Dependencies on taxonomy and terms

  // Fetch posts based on taxonomy
  const fetchPostsByTaxonomy = () => {
    const defaultArgs = { per_page: 10, type: 'post' };
    const requestArguments = { ...defaultArgs };

    api.getPostsFromTerms(requestArguments, taxonomy, terms)
      .then((data = []) => {
        const posts = data.map(p => ({
          ...p,
          featured_image: p.featured_media ?
            p._embedded['wp:featuredmedia'][0].source_url || false : false,
        }));
        setResults(alterResults(posts));
        setLoading(false);
      })
      .catch(() => {
        setResults([]);
        setLoading(false);
      });

    setLoading(true);
  };

  // Alter results to extract relevant fields
  const alterResults = (response) =>
    response.map(resp => {
      const tags = resp._embedded['wp:term'].reduce((prev, curr) => [...prev, ...curr], [])
        .map(tag => ({ title: tag.name, link: tag.link }));

      const featuredImage = resp.featured_media ?
        resp._embedded['wp:featuredmedia'][0].source_url || false : false;

      let excerpt = strip(resp.excerpt.rendered);
      excerpt = excerpt.length > 250 ? `${excerpt.slice(0, 250)}...` : excerpt;

      return {
        id: resp.id,
        title: resp.title.rendered,
        link: resp.link,
        tag: tags.shift(),
        excerpt,
        featured_image: featuredImage,
        authorName: resp.authorName,
        date: resp.datePosted,
      };
    });

  // Strip HTML from a string
  const strip = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || '';
  };

  // Render the component based on loading state and results
  if (loading) {
    return <div><p>{__('Loading…', 'amnesty')}</p></div>;
  }

  if (!taxonomy) {
    return <div><p className="linklist-container">{__('Select a taxonomy.', 'amnesty')}</p></div>;
  }

  if (results.length === 0) {
    return <div><p className="linklist-container">{__('No Items found', 'amnesty')}</p></div>;
  }

  const isList = style === 'list';
  const isGrid = style === 'grid';

  if (isList) {
    return (
      <div>
        <ul className="linkList linklist-container">
          {results.filter((item, i) => i < amount).map(result => (
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

  if (isGrid) {
    return (
      <div className={`grid ${[1, 2, 3, 5, 6, 7].includes(amount) ? `grid-${amount}` : 'grid-many'}`}>
        {results.filter((item, i) => i < amount).map(result => (
          <GridItem key={`${prefix}-${result.id}`} {...result} />
        ))}
      </div>
    );
  }

  return <div></div>;
};

export default DisplayTaxonomies;
