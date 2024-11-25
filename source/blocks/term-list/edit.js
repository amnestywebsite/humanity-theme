import { useState, useEffect, Fragment } from 'react';
import { filter, has, head, map } from 'lodash';
import { BlockAlignmentToolbar, BlockControls, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { addQueryArgs } from '@wordpress/url';

const groupTerms = (terms) => {
  const grouped = {};

  terms
    .filter(({ hidden, type }) => type === 'default' && !hidden)
    .forEach((term) => {
      const char = term.name.charAt(0).toUpperCase();
      if (!has(grouped, char)) {
        grouped[char] = [];
      }
      grouped[char].push(term);
    });

  return Object.keys(grouped)
    .sort()
    .reduce((object, key) => {
      // eslint-disable-next-line no-param-reassign
      object[key] = grouped[key];
      return object;
    }, {});
};

const Edit = ({ attributes, className, setAttributes }) => {
  const [taxonomies, setTaxonomies] = useState([]);
  const [options, setOptions] = useState([]);
  const [current, setCurrent] = useState({});
  const [terms, setTerms] = useState([]);
  const [cache, setCache] = useState({});
  const [activeLetter, setActiveLetter] = useState('');

  useEffect(() => {
    const { taxonomy } = attributes;

    wp.apiFetch({ path: '/wp/v2/taxonomies/' }).then((rawTaxonomies) => {
      const filteredTaxonomies = filter(rawTaxonomies, (t) => t.amnesty);
      const selectedTaxonomy = head(filter(filteredTaxonomies, (t) => t.slug === taxonomy));
      const taxonomyOptions = map(filteredTaxonomies, (tax) => ({ label: tax.name, value: tax.slug }));

      setTaxonomies(filteredTaxonomies);
      setCurrent(selectedTaxonomy);
      setOptions(taxonomyOptions);
    });
  }, [attributes.taxonomy]);

  useEffect(() => {
    if (current) {
      fetchTerms();
    }
  }, [current]);

  const fetchTerms = () => {
    const { rest_base } = current;

    const path = addQueryArgs(`/wp/v2/${rest_base}/`, {
      hide_empty: 'false',
      per_page: '250',
    });

    if (cache[path]) {
      setTerms(cache[path]);
      return;
    }

    wp.apiFetch({ path }).then((data) => {
      const groupedTerms = groupTerms(data);

      setTerms(groupedTerms);
      setActiveLetter(Object.keys(groupedTerms)[0]);
      setCache((prevCache) => ({
        ...prevCache,
        [path]: groupedTerms,
      }));
    });
  };

  const handleSetActiveLetter = (letter) => {
    if (terms[letter]) {
      setActiveLetter(letter);
    }
  };

  const renderInspectorControls = () => (
    <InspectorControls>
      <PanelBody title={__('Display Options', 'amnesty')}>
        <SelectControl
          label={__('Choose taxonomy to display', 'amnesty')}
          options={options}
          value={attributes.taxonomy}
          onChange={(taxonomy) => setAttributes({ taxonomy })}
        />
      </PanelBody>
    </InspectorControls>
  );

  const renderBlockControls = () => (
    <BlockControls>
      <BlockAlignmentToolbar
        value={attributes.alignment}
        onChange={(alignment) => setAttributes({ alignment })}
      />
    </BlockControls>
  );

  const letterItems = Array.from(terms[activeLetter] || []);

  return (
    <Fragment>
      {renderInspectorControls()}
      {renderBlockControls()}
      <aside className={className}>
        <RichText
          tagName="h2"
          format="string"
          className={attributes.alignment ? `is-${attributes.alignment}-aligned` : null}
          value={attributes.title}
          onChange={(title) => setAttributes({ title })}
          placeholder={__('A-Z of Countries and Regions', 'amnesty')}
          withoutInteractiveFormatting
        />
        <div className="navigation">
          {Object.keys(terms).map((letter) => (
            <button
              key={letter}
              className={letter === activeLetter ? 'is-active' : null}
              onClick={() => handleSetActiveLetter(letter)}
              disabled={(terms[letter] || []).length === 0}
            >
              {letter}
            </button>
          ))}
        </div>
        <div className="listContainer">
          <div className="activeLetter">{activeLetter}</div>
          <ul className="listItems">
            {letterItems.map((item) => (
              <li className="listItem" key={item.id}>
                <a href={item.link}>{item.name}</a>
              </li>
            ))}
          </ul>
        </div>
      </aside>
    </Fragment>
  );
};

export default Edit;
