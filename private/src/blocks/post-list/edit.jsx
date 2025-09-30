import { has } from 'lodash';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl, ToggleControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import DisplayAuthor from './components/DisplayAuthor.jsx';
import DisplayCategories from './components/DisplayCategories.jsx';
import DisplayCustom from './components/DisplayCustom.jsx';
import DisplayFeed from './components/DisplayFeed.jsx';
import DisplaySelect from './components/DisplaySelect.jsx';
import DisplayTaxonomies from './components/DisplayTaxonomies.jsx';
import * as api from './components/post-selector/api';

import AuthorSelector from './components/selectors/AuthorSelector.jsx';
import CategorySelector from './components/selectors/CategorySelector.jsx';
import TaxonomySelector from './components/selectors/TaxonomySelector.jsx';
import TermSelector from './components/selectors/TermSelector.jsx';

import { createRange, randId } from '../../utils/index';

const getTaxonomyTerms = (value, callback) => {
  if (!value) {
    callback([]);
    return;
  }

  api
    .getTerms(value)
    .then((data) => data.map((termItem) => ({ label: termItem.name, value: termItem.id })))
    .then(callback);
};

let defaultStyleOptions = [
  /* translators: [admin] */
  { label: __('Link List', 'amnesty'), value: 'list' },
  /* translators: [admin] */
  { label: __('Grid', 'amnesty'), value: 'grid' },
];

let defaultDisplayTypes = [
  /* translators: [admin] */
  { label: __('Category', 'amnesty'), value: 'category' },
  /* translators: [admin] */
  { label: __('Object Selection', 'amnesty'), value: 'select' },
  /* translators: [admin] */
  { label: __('Custom', 'amnesty'), value: 'custom' },
  /* translators: [admin] */
  { label: __('Taxonomy', 'amnesty'), value: 'taxonomy' },
  /* translators: [admin] */
  { label: __('Author', 'amnesty'), value: 'author' },
];

const range = createRange(1, 100);

const keyPrefix = randId();
const setupOptions = ({ attributes, setAttributes }) => {
  if (has(attributes, 'extraStyleOptions')) {
    defaultStyleOptions = attributes.extraStyleOptions;
    setAttributes({ style: defaultStyleOptions[0].value });
  }

  if (has(attributes, 'displayTypes')) {
    defaultDisplayTypes = attributes.displayTypes;

    if (defaultDisplayTypes[0].value === attributes.type) {
      setAttributes({ type: defaultDisplayTypes[0].value });
    } else {
      setAttributes({ type: defaultDisplayTypes[1].value });
    }
  }
};

const Categories = ({ attributes, setAttributes }) => {
  if (attributes.type !== 'category') {
    return null;
  }

  return (
    <>
      <CategorySelector
        value={attributes.category}
        onChange={(category) => setAttributes({ category })}
        attributes={attributes}
        setAttributes={setAttributes}
      />
      <div>
        <DisplayCategories
          amount={attributes.amount || 3}
          category={attributes.category}
          style={attributes.style}
          prefix={keyPrefix}
          showAuthor={attributes.displayAuthor}
          showPostDate={attributes.displayPostDate}
          overrideTypes={attributes?.postTypes}
        />
      </div>
    </>
  );
};

const Custom = ({ attributes, setAttributes }) => {
  if (attributes.type !== 'custom') {
    return null;
  }

  return (
    <InspectorControls>
      <DisplayCustom
        setAttributes={setAttributes}
        custom={attributes.custom || []}
        style={attributes.style}
        prefix={keyPrefix}
        showAuthor={attributes.displayAuthor}
        showPostDate={attributes.displayPostDate}
      />
    </InspectorControls>
  );
};

const Select = ({ attributes, setAttributes }) => {
  const [preview, setPreview] = useState(false);

  if (attributes.type !== 'select') {
    return null;
  }

  return (
    <>
      <InspectorControls>
        <button onClick={() => setPreview(!preview)}>
          {preview
            ? /* translators: [admin] */
              __('Hide Preview', 'amnesty')
            : /* translators: [admin] */
              __('Show Preview', 'amnesty')}
        </button>
      </InspectorControls>
      <div>
        <DisplaySelect
          setAttributes={setAttributes}
          selectedPosts={attributes.selectedPosts || []}
          defaultPostType={attributes.postType || 'post'}
          preview={preview}
          style={attributes.style}
          prefix={keyPrefix}
          overrideTypes={[]}
          showAuthor={attributes.displayAuthor}
          showPostDate={attributes.displayPostDate}
        />
      </div>
    </>
  );
};

const Taxonomy = ({ attributes, setAttributes }) => {
  const [allTerms, setAllTerms] = useState();

  if (attributes.type !== 'taxonomy') {
    return null;
  }

  const addTaxonomyFilter = (taxonomy) => {
    setAttributes({ taxonomy });
    getTaxonomyTerms(taxonomy.value, (terms) => {
      setAllTerms(terms);
      setAttributes({ terms });
    });
  };

  return (
    <>
      <InspectorControls>
        <div>
          <TaxonomySelector onChange={addTaxonomyFilter} value={attributes.taxonomy} />
          <TermSelector
            options={allTerms}
            value={attributes.terms}
            onChange={(terms) => setAttributes({ terms })}
          />
        </div>
        <RangeControl
          /* translators: [admin] */
          label={__('Number of posts to show:', 'amnesty')}
          min={1}
          max={8}
          value={attributes.amount || 3}
          onChange={(amount) => setAttributes({ amount })}
        />
      </InspectorControls>
      <div>
        <DisplayTaxonomies
          setAttributes={setAttributes}
          style={attributes.style}
          prefix={keyPrefix}
          taxonomy={attributes.taxonomy}
          showAuthor={attributes.displayAuthor}
          showPostDate={attributes.displayPostDate}
          terms={attributes.terms}
          amount={attributes.amount || 3}
        />
      </div>
    </>
  );
};

const Author = ({ attributes, setAttributes }) => {
  if (attributes.type !== 'author') {
    return null;
  }

  return (
    <>
      <AuthorSelector
        value={attributes.authors}
        onChange={(authors) => setAttributes({ authors })}
      />
      <div>
        <DisplayAuthor
          setAttributes={setAttributes}
          style={attributes.style}
          prefix={keyPrefix}
          authors={attributes.authors}
          amount={10}
          showAuthor={attributes.displayAuthor}
          showPostDate={attributes.displayPostDate}
        />
      </div>
    </>
  );
};

const Feed = ({ attributes, setAttributes }) => {
  if (attributes.type !== 'feed') {
    return null;
  }

  return (
    <>
      <InspectorControls>
        <RangeControl
          /* translators: [admin] */
          label={__('Number of posts to show:', 'amnesty')}
          min={1}
          max={8}
          value={attributes.amount || 3}
          onChange={(amount) => setAttributes({ amount: range(amount) })}
        />
      </InspectorControls>
      <div>
        <DisplayFeed
          amount={attributes.amount || 3}
          category={attributes.category}
          overrideTypes={attributes?.postTypes}
          style={attributes.style}
          prefix={keyPrefix}
          showAuthor={attributes.displayAuthor}
          showPostDate={attributes.displayPostDate}
        />
      </div>
    </>
  );
};

export default function Edit(props) {
  setupOptions(props);

  return (
    <>
      <InspectorControls>
        <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
          {defaultStyleOptions.length > 0 && (
            <SelectControl
              /* translators: [admin] */
              label={__('Style', 'amnesty')}
              options={defaultStyleOptions}
              value={props.attributes.style}
              onChange={(style) => props.setAttributes({ style })}
            />
          )}
          <SelectControl
            /* translators: [admin] */
            label={__('Type', 'amnesty')}
            options={defaultDisplayTypes}
            value={props.attributes.type}
            onChange={(type) => props.setAttributes({ type })}
          />
          <ToggleControl
            /* translators: [admin] */
            label={__('Display Post Author', 'amnesty')}
            checked={props.attributes.displayAuthor}
            onChange={(displayAuthor) => props.setAttributes({ displayAuthor })}
          />
          <ToggleControl
            /* translators: [admin] */
            label={__('Display Post Date', 'amnesty')}
            checked={props.attributes.displayPostDate}
            onChange={(displayPostDate) => props.setAttributes({ displayPostDate })}
          />
        </PanelBody>
      </InspectorControls>
      <div {...useBlockProps()}>
        <Categories {...props} />
        <Custom {...props} />
        <Select {...props} />
        <Taxonomy {...props} />
        <Author {...props} />
        <Feed {...props} />
      </div>
    </>
  );
}
