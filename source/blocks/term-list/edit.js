import { useState, useEffect, Fragment } from 'react';
import { filter, map } from 'lodash';
import { BlockAlignmentToolbar, BlockControls, InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useEntityRecords } from '@wordpress/core-data';

const groupTerms = (terms) => {
  return terms?.reduce((grouped, term) => {
    const char = term.name.charAt(0).toUpperCase();
    if (!grouped[char]) grouped[char] = [];
    grouped[char].push(term);
    return grouped;
  }, {}) || {};
};

const Edit = ({ attributes, className, setAttributes }) => {
  const [activeLetter, setActiveLetter] = useState('');

  const { records: taxonomies, isResolving: taxonomiesLoading } = useEntityRecords('root', 'taxonomy');
  const { records: terms, isResolving: termsLoading } = useEntityRecords('taxonomy', attributes.taxonomy, { per_page: -1 });

  const [groupedTerms, setGroupedTerms] = useState({});
  const [options, setOptions] = useState([]);

  useEffect(() => {
    if (!taxonomiesLoading) {
      const filteredTaxonomies = filter(taxonomies, (t) => t.amnesty);
      setOptions(map(filteredTaxonomies, (tax) => ({ label: tax.name, value: tax.slug })));
      const currentTaxonomy = filteredTaxonomies.find((t) => t.slug === attributes.taxonomy);
      if (currentTaxonomy) {
        setAttributes({ taxonomy: currentTaxonomy.slug });
      }
    }
  }, [taxonomies, taxonomiesLoading, attributes.taxonomy, setAttributes]);

  useEffect(() => {
    if (!termsLoading && terms) {
      const grouped = groupTerms(terms);
      setGroupedTerms(grouped);
      setActiveLetter(Object.keys(grouped)[0] || '');
    }
  }, [terms, termsLoading]);

  const handleSetActiveLetter = (letter) => {
    if (groupedTerms[letter]) setActiveLetter(letter);
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

  const letterItems = groupedTerms[activeLetter] || [];

  const blockProps = useBlockProps({
    className,
  });

  return (
    <Fragment>
      {renderInspectorControls()}
      {renderBlockControls()}
      <aside {...blockProps}>
        <RichText
          tagName="h2"
          format="string"
          className={attributes.alignment ? `is-${attributes.alignment}-aligned` : ''}
          value={attributes.title}
          onChange={(title) => setAttributes({ title })}
          placeholder={__('A-Z of Countries and Regions', 'amnesty')}
          withoutInteractiveFormatting
        />
        <div className="navigation">
          {/* Render buttons for each letter */}
          {Object.keys(groupedTerms).map((letter) => (
            <button
              key={letter}
              className={letter === activeLetter ? 'is-active' : ''}
              onClick={() => handleSetActiveLetter(letter)}
              disabled={groupedTerms[letter]?.length === 0}
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
