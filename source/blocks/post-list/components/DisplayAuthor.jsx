import classnames from 'classnames';
import LinkList from './display/LinkList.jsx';
import GridItem from './display/GridItem.jsx';

import { isString, keyBy } from 'lodash';
import { useEntityRecords } from '@wordpress/core-data';
import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { addQueryArgs } from '@wordpress/url';

const normaliseAuthor = (author = '[]') => {
  let normal = author;

  if (isString(normal)) {
    normal = JSON.parse(normal);
  }

  if (!Array.isArray(normal)) {
    normal = [normal];
  }

  normal = normal.map((val) => {
    if (isString(val)) {
      return JSON.parse(val);
    }

    return val;
  });

  return normal.filter(Boolean);
};

const addTermData = (results, termRecords) => results.map((result) => {
  result.topics = result.topics.map((topic) => termRecords[topic]);
  return result;
});

const fetchPostsByAuthor = ({ authors, records, setLoading, setResults, signal }) => {
  const normalisedAuthors = normaliseAuthor(authors).map((v) => v.value).join(',');

  if (!normalisedAuthors) {
    setResults([]);
    return;
  }

  const requestArgs = {
    author: authors,
    context: 'edit',
    per_page: 10,
    type: 'post',
    _fields: [
      'id',
      'title.raw',
      'link',
      'topic',
      'excerpt.rendered',
      'featured_media',
      'datePosted',
      'author',
      'authorName',
    ],
  };

  setLoading(true);

  const path = addQueryArgs('/wp/v2/posts', requestArgs);
  wp.apiFetch({ path, signal })
    .then((results) => addTermData(results, keyBy(records, 'id')))
    .then(setResults)
    .finally(() => setLoading(false));
};

const DisplayAuthor = ({ amount, authors, prefix, showAuthor, showPostDate, style }) => {
  const { abort, signal } = new AbortController();
  const { records } = useEntityRecords('taxonomy', 'topic', { per_page: -1 });
  const [loading, setLoading] = useState(false);
  const [results, setResults] = useState([]);
  const mounted = useRef();

  useEffect(() => {
    if (!mounted?.current) {
      mounted.current = true;
      fetchPostsByAuthor({ authors, records, setLoading, setResults, signal });
    }

    return abort;
  }, [records]);

  useEffect(() => {
    fetchPostsByAuthor({ authors, records, setLoading, setResults, signal });
    return abort;
  }, [authors]);

  if (loading) {
    return (
      <div>
        <p>{/* translators: [admin] */ __('Loading…', 'amnesty')}</p>
      </div>
    );
  }

  if (!authors) {
    return (
      <div>
        <p className="linklist-container">
          {/* translators: [admin] */ __('Select an author.', 'amnesty')}
        </p>
      </div>
    );
  }

  if (!results.length) {
    return (
      <div>
        <p className="linklist-container">
          {/* translators: [admin] */ __('No Items found', 'amnesty')}
        </p>
      </div>
    );
  }

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

  if (style !== 'grid') {
    return null;
  }

  const classes = classnames('grid', {
    [`grid-${amount}`]: amount < 8,
    'grid-many': amount >= 8,
  });

  return (
    <div>
      <div className={classes}>
        {results.slice(0, amount).map((result) => (
          <GridItem key={`${prefix}-${result.id}`} {...result} />
        ))}
      </div>
    </div>
  );
};


export default DisplayAuthor;
