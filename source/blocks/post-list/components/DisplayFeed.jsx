import React, { useState, useEffect } from 'react';
import LinkList from './display/LinkList.jsx';
import GridItem from './display/GridItem.jsx';
import PetitionItem from './display/PetitionItem.jsx';
import { get, isString } from 'lodash';
import { __ } from '@wordpress/i18n';

/**
 * DisplayFeed Functional Component
 * This component fetches and displays posts based on specified post types.
 */
const DisplayFeed = ({ overrideTypes, style, prefix, showAuthor, showPostDate, amount }) => {
  // State variables for managing results and loading status
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(false);

  // Fetch posts when component mounts
  useEffect(() => {
    fetchPostsByPostType();
  }, []); // Empty dependency array means this runs once after the initial render

  // Fetch posts based on post types defined in props
  const fetchPostsByPostType = () => {
    const postTypes = Object.keys(overrideTypes);

    // Handle case where no post types are available
    if (!postTypes.length) {
      setResults([]);
      return;
    }

    setLoading(true); // Set loading state

    const postString = postTypes.join(', ');

    wp.apiRequest({
      path: `/wp/v2/${postString}?_embed`,
    })
      .then((results) => {
        setResults(alterResults(results)); // Alter results and update state
        setLoading(false); // Reset loading state
      });
  };

  // Strip HTML from a string
  const strip = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || '';
  };

  // Alter results to extract relevant fields
  const alterResults = (response) =>
    response.map((resp) => {
      const tags = [];
      const sources = [
        '_links["wp:featuredmedia"][0].media_details.sizes["post-half@2x"].source_url',
        '_links["wp:featuredmedia"][0].source_url',
        '_embedded["wp:featuredmedia"][0].media_details.sizes["post-half@2x"].source_url',
        '_embedded["wp:featuredmedia"][0].source_url',
      ];

      let featuredImage = false;

      sources.forEach((source) => {
        featuredImage = featuredImage || get(resp, source, false);
      });

      let excerpt = strip(resp.excerpt.rendered);
      excerpt = excerpt.length > 250 ? `${excerpt.slice(0, 250)}...` : excerpt;

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

  // Render the component based on loading state and results
  if (loading) {
    return (
      <div>
        <p>{__('Loading…', 'amnesty')}</p>
      </div>
    );
  }

  if (results.length === 0) {
    return (
      <div>
        <p className="linklist-container">{__('No Items found', 'amnesty')}</p>
      </div>
    );
  }

  const isList = style === 'list';
  const isGrid = style === 'grid';
  const isPetition = style === 'petition';

  // Render based on style type
  if (isList) {
    return (
      <div>
        <ul className="linkList linklist-container">
          {results
            .filter((_, i) => i < amount)
            .map((result) => (
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
        {results
          .filter((_, i) => i < amount)
          .map((result) => (
            <GridItem key={`${prefix}-${result.id}`} {...result} />
          ))}
      </div>
    );
  }

  if (isPetition) {
    return (
      <div className={`grid grid-many petition-grid`}>
        {results
          .filter((_, i) => i < amount)
          .map((result) => (
            <PetitionItem key={`${prefix}-${result.id}`} {...result} />
          ))}
      </div>
    );
  }

  return <div></div>;
};

export default DisplayFeed;
