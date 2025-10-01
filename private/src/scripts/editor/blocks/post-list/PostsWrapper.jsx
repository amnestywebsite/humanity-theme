/* eslint-disable react/display-name */
// This configures the post list for use by other blocks
import CategorySelect from './components/category-select.jsx';
import DisplayCategories from './components/DisplayCategories.jsx';
import DisplayCustom from './components/DisplayCustom.jsx';
import DisplaySelect from './components/DisplaySelect.jsx';
import DisplayTaxonomies from './components/DisplayTaxonomies.jsx';
import TaxonomySelect from './components/taxonomy-select.jsx';
import * as api from './components/post-selector/api';
import TermSelect from './components/term-select.jsx';
import DisplayAuthor from './components/DisplayAuthor.jsx';
import AuthorSelect from './components/author-select.jsx';
import DisplayFeed from './components/DisplayFeed.jsx';

const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, RangeControl, SelectControl, ToggleControl } = wp.components;
const { Component } = wp.element;
const { applyFilters } = wp.hooks;
const { __ } = wp.i18n;

const { has } = lodash;

const PostsWrapper = createHigherOrderComponent((BlockEdit) => {
  const createRange = (min, max) => (num) => Math.max(min, Math.min(max, num));

  const PostList = class DisplayComponent extends Component {
    constructor(...args) {
      super(...args);

      this.state = {
        results: [],
        loading: false,
        preview: (this.props.attributes.selectedPosts || []).length > 0,
        // Generate a key prefix as post id may not be unique.
        keyPrefix: Math.random().toString(36).substring(7),
        allTerms: [],
      };
      this.range = createRange(1, 100);
    }

    /**
     * Higher order component that takes the attribute key,
     * this then returns a function which takes a value,
     * when called it updates the attribute with the key.
     * @param key
     * @returns {function(*): *}
     */
    createUpdateAttribute = (key) => (value) => this.props.setAttributes({ [key]: value });

    createUpdateAttributeWithFilter = (key, filter) => (value) =>
      this.props.setAttributes({
        [key]: filter(value),
      });

    /**
     * Toggle the preview state for the 'selection' style.
     * @returns {*}
     */
    togglePreview = () => {
      this.setState({
        preview: !this.state.preview,
      });
    };

    getTaxonomyTerms = (value) => {
      if (value) {
        const allTerms = api.getTerms(value);

        allTerms
          .then((data) => {
            const termData = data.map((termItem) => ({ label: termItem.name, value: termItem.id }));

            this.setState({ allTerms: termData });
          })
          .then(() => {});
      } else {
        this.setState({ allTerms: [] });
        this.props.setAttributes({
          terms: [],
        });
      }
    };

    addTaxonomyFilter = (value) => {
      this.props.setAttributes({
        taxonomy: value,
      });
      this.getTaxonomyTerms(value.value);
    };

    addTerms = (value) => {
      this.props.setAttributes({
        terms: value,
      });
    };

    render() {
      const { attributes, setAttributes } = this.props;
      let postTypeOveride;

      let defaultStyleOptions = [
        {
          // translators: [admin]
          label: __('Link List', 'amnesty'),
          value: 'list',
        },
        {
          // translators: [admin]
          label: __('Grid', 'amnesty'),
          value: 'grid',
        },
      ];

      let defaultDisplayTypes = [
        {
          // translators: [admin]
          label: __('Category', 'amnesty'),
          value: 'category',
        },
        {
          // translators: [admin]
          label: __('Object Selection', 'amnesty'),
          value: 'select',
        },
        {
          // translators: [admin]
          label: __('Custom', 'amnesty'),
          value: 'custom',
        },
        {
          // translators: [admin]
          label: __('Taxonomy', 'amnesty'),
          value: 'taxonomy',
        },
        {
          // translators: [admin]
          label: __('Author', 'amnesty'),
          value: 'author',
        },
      ];

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

      if (has(attributes, 'postTypes')) {
        postTypeOveride = attributes.postTypes;
      }

      const maxQuantity = parseInt(applyFilters('amnesty-post-list-quantity', 8, attributes), 10);

      return (
        <Fragment>
          <InspectorControls>
            <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
              {defaultStyleOptions.length > 0 && (
                <SelectControl
                  // translators: [admin]
                  label={__('Style', 'amnesty')}
                  options={defaultStyleOptions}
                  value={attributes.style}
                  onChange={this.createUpdateAttribute('style')}
                />
              )}
              <SelectControl
                // translators: [admin]
                label={__('Type', 'amnesty')}
                options={defaultDisplayTypes}
                value={attributes.type}
                onChange={this.createUpdateAttribute('type')}
              />
              {attributes.type === 'category' && (
                <label>
                  {/* translators: [admin] */ __('Category:', 'amnesty')}
                  <br />
                  <CategorySelect
                    value={attributes.category}
                    onChange={this.createUpdateAttribute('category')}
                  />
                  <br />
                </label>
              )}
              {attributes.type === 'category' && (
                <RangeControl
                  // translators: [admin]
                  label={__('Number of posts to show:', 'amnesty')}
                  min={1}
                  max={maxQuantity}
                  value={attributes.amount || 3}
                  onChange={this.createUpdateAttributeWithFilter('amount', this.range)}
                />
              )}
              {attributes.type === 'feed' && (
                <RangeControl
                  // translators: [admin]
                  label={__('Number of posts to show:', 'amnesty')}
                  min={1}
                  max={maxQuantity}
                  value={attributes.amount || 3}
                  onChange={this.createUpdateAttributeWithFilter('amount', this.range)}
                />
              )}
              {attributes.type === 'taxonomy' && (
                <RangeControl
                  // translators: [admin]
                  label={__('Number of posts to show:', 'amnesty')}
                  min={1}
                  max={maxQuantity}
                  value={attributes.amount || 3}
                  onChange={(value) => setAttributes({ amount: value })}
                />
              )}
              {attributes.type === 'category' && (
                <ToggleControl
                  // translators: [admin]
                  label={__('Use related categories where supported', 'amnesty')}
                  checked={attributes.categoryRelated}
                  onChange={this.createUpdateAttribute('categoryRelated')}
                />
              )}
              {attributes.type === 'select' && (
                <button onClick={this.togglePreview}>
                  {this.state.preview
                    ? // translators: [admin]
                      __('Hide Preview', 'amnesty')
                    : // translators: [admin]
                      __('Show Preview', 'amnesty')}
                </button>
              )}
              {attributes.type === 'taxonomy' && (
                <div>
                  <label>
                    {/* translators: [admin] */ __('Taxonomy:', 'amnesty')}
                    <br />
                    <TaxonomySelect value={attributes.taxonomy} onChange={this.addTaxonomyFilter} />
                    <br />
                  </label>
                  <label>
                    <div className="term-selector">
                      {/* translators: [admin] */ __('Terms:', 'amnesty')}
                      <br />
                      <TermSelect
                        allTerms={this.state.allTerms}
                        value={attributes.terms}
                        onChange={this.addTerms}
                      />
                    </div>
                  </label>
                </div>
              )}
              {attributes.type === 'author' && (
                <label>
                  {/* translators: [admin] */ __('Author:', 'amnesty')}
                  <br />
                  <AuthorSelect
                    value={attributes.authors}
                    onChange={this.createUpdateAttribute('authors')}
                  />
                  <br />
                </label>
              )}
              <ToggleControl
                // translators: [admin]
                label={__('Display Post Author', 'amnesty')}
                checked={attributes.displayAuthor}
                onChange={this.createUpdateAttribute('displayAuthor')}
              />
              <ToggleControl
                // translators: [admin]
                label={__('Display Post Date', 'amnesty')}
                checked={attributes.displayPostDate}
                onChange={this.createUpdateAttribute('displayPostDate')}
              />
            </PanelBody>
          </InspectorControls>
          <div>
            {attributes.type === 'category' && (
              <DisplayCategories
                amount={attributes.amount || 3}
                category={attributes.category}
                style={attributes.style}
                prefix={this.state.keyPrefix}
                showAuthor={attributes.displayAuthor}
                showPostDate={attributes.displayPostDate}
                overrideTypes={postTypeOveride}
              />
            )}
            {attributes.type === 'custom' && (
              <DisplayCustom
                setAttributes={this.props.setAttributes}
                custom={attributes.custom || []}
                style={attributes.style}
                prefix={this.state.keyPrefix}
                showAuthor={attributes.displayAuthor}
                showPostDate={attributes.displayPostDate}
              />
            )}
            {attributes.type === 'select' && (
              <DisplaySelect
                setAttributes={this.props.setAttributes}
                selectedPosts={attributes.selectedPosts || []}
                preview={this.state.preview}
                style={attributes.style}
                prefix={this.state.keyPrefix}
                postType={attributes.postType}
                overrideTypes={postTypeOveride}
                showAuthor={attributes.displayAuthor}
                showPostDate={attributes.displayPostDate}
              />
            )}
            {attributes.type === 'taxonomy' && (
              <DisplayTaxonomies
                setAttributes={this.props.setAttributes}
                style={attributes.style}
                prefix={this.state.keyPrefix}
                taxonomy={attributes.taxonomy}
                showAuthor={attributes.displayAuthor}
                showPostDate={attributes.displayPostDate}
                terms={attributes.terms}
                amount={attributes.amount || 3}
              />
            )}
            {attributes.type === 'author' && (
              <DisplayAuthor
                setAttributes={this.props.setAttributes}
                style={attributes.style}
                prefix={this.state.keyPrefix}
                authors={attributes.authors}
                amount={10}
                showAuthor={attributes.displayAuthor}
                showPostDate={attributes.displayPostDate}
              />
            )}
            {attributes.type === 'feed' && (
              <DisplayFeed
                amount={attributes.amount || 3}
                category={attributes.category}
                overrideTypes={postTypeOveride}
                style={attributes.style}
                prefix={this.state.keyPrefix}
                showAuthor={attributes.displayAuthor}
                showPostDate={attributes.displayPostDate}
              />
            )}
          </div>
        </Fragment>
      );
    }
  };

  return (props) => (
    <Fragment>
      <BlockEdit {...props} />
      <PostList {...props} />
    </Fragment>
  );
}, 'withInspectorControl');

export default PostsWrapper;
