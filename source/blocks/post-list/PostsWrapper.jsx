import { useState, useEffect, useCallback } from 'react';
import { has } from 'lodash';
import { PanelBody, RangeControl, SelectControl, ToggleControl } from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

import DisplayCategories from './components/DisplayCategories.jsx';
import DisplayCustom from './components/DisplayCustom.jsx';
import DisplaySelect from './components/DisplaySelect.jsx';
import DisplayTaxonomies from './components/DisplayTaxonomies.jsx';
import * as api from './components/post-selector/api';
import DisplayAuthor from './components/DisplayAuthor.jsx';
import DisplayFeed from './components/DisplayFeed.jsx';
import AuthorSelector from './components/selectors/AuthorSelector.jsx';
import CategorySelector from './components/selectors/CategorySelector.jsx';
import TaxonomySelector from './components/selectors/TaxonomySelector.jsx';
import TermSelector from './components/selectors/TermSelector.jsx';
import { use } from '@wordpress/data';

const { InspectorControls } = wp.blockEditor;

const PostsWrapper = createHigherOrderComponent((BlockEdit) => {
  const createRange = (min, max) => (num) => Math.max(min, Math.min(max, num));

  const PostList = ({ attributes, setAttributes }) => {
    const [preview, setPreview] = useState((attributes.selectedPosts || []).length > 0);
    const [keyPrefix] = useState(Math.random().toString(36).substring(7));
    const [allTerms, setAllTerms] = useState([]);

    const range = createRange(1, 100);

    const createUpdateAttribute = useCallback(
      (key) => (value) => setAttributes({ [key]: value }),
      [setAttributes]
    );

    const createUpdateAttributeWithFilter = useCallback(
      (key, filter) => (value) => setAttributes({ [key]: filter(value) }),
      [setAttributes]
    );

    const togglePreview = () => setPreview((prev) => !prev);

    const getTaxonomyTerms = useCallback(
      (value) => {
        if (value) {
          api.getTerms(value).then((data) => {
            const termData = data.map((termItem) => ({
              label: termItem.name,
              value: termItem.id,
            }));
            setAllTerms(termData);
          });
        } else {
          setAllTerms([]);
          setAttributes({ terms: [] });
        }
      },
      [setAttributes]
    );

    const addTaxonomyFilter = (value) => {
      setAttributes({ taxonomy: value });
      getTaxonomyTerms(value.value);
    };

    const addTerms = (value) => setAttributes({ terms: value });

    let postTypeOveride;
    let defaultStyleOptions = [
      {
        label: __('Link List', 'amnesty'),
        value: 'list',
      },
      {
        label: __('Grid', 'amnesty'),
        value: 'grid',
      },
    ];

    let defaultDisplayTypes = [
      {
        label: __('Category', 'amnesty'),
        value: 'category',
      },
      {
        label: __('Object Selection', 'amnesty'),
        value: 'select',
      },
      {
        label: __('Custom', 'amnesty'),
        value: 'custom',
      },
      {
        label: __('Taxonomy', 'amnesty'),
        value: 'taxonomy',
      },
      {
        label: __('Author', 'amnesty'),
        value: 'author',
      },
    ];

    useEffect(() => {
      if (has(attributes, 'postTypes')) {
        postTypeOveride = attributes?.postTypes;
      }
    }, []);

    useEffect(() => {
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
        postTypeOveride = attributes?.postTypes;
      }
    }, [attributes, setAttributes]);

    console.log(postTypeOveride);


    // return (
    //   <>
    //     <InspectorControls>
    //       <PanelBody title={__('Options', 'amnesty')}>
    //         {defaultStyleOptions.length > 0 && (
    //           <SelectControl
    //             label={__('Style', 'amnesty')}
    //             options={defaultStyleOptions}
    //             value={attributes.style}
    //             onChange={createUpdateAttribute('style')}
    //           />
    //         )}
    //         <SelectControl
    //           label={__('Type', 'amnesty')}
    //           options={defaultDisplayTypes}
    //           value={attributes.type}
    //           onChange={createUpdateAttribute('type')}
    //         />
    //         {attributes.type === 'category' && (
    //           <label>
    //             {__('Category:', 'amnesty')}
    //             <br />
    //             <CategorySelector
    //               value={attributes.category}
    //               onChange={createUpdateAttribute('category')}
    //             />
    //             <br />
    //           </label>
    //         )}
    //         {attributes.type === 'category' && (
    //           <RangeControl
    //             label={__('Number of posts to show:', 'amnesty')}
    //             min={1}
    //             max={8}
    //             value={attributes.amount || 3}
    //             onChange={createUpdateAttributeWithFilter('amount', range)}
    //           />
    //         )}
    //         {attributes.type === 'feed' && (
    //           <RangeControl
    //             label={__('Number of posts to show:', 'amnesty')}
    //             min={1}
    //             max={8}
    //             value={attributes.amount || 3}
    //             onChange={createUpdateAttributeWithFilter('amount', range)}
    //           />
    //         )}
    //         {attributes.type === 'taxonomy' && (
    //           <RangeControl
    //             label={__('Number of posts to show:', 'amnesty')}
    //             min={1}
    //             max={8}
    //             value={attributes.amount || 3}
    //             onChange={(value) => setAttributes({ amount: value })}
    //           />
    //         )}
    //         {attributes.type === 'category' && (
    //           <ToggleControl
    //             label={__('Use related categories where supported', 'amnesty')}
    //             checked={attributes.categoryRelated}
    //             onChange={createUpdateAttribute('categoryRelated')}
    //           />
    //         )}
    //         {attributes.type === 'select' && (
    //           <button onClick={togglePreview}>
    //             {preview ? __('Hide Preview', 'amnesty') : __('Show Preview', 'amnesty')}
    //           </button>
    //         )}
    //         {attributes.type === 'taxonomy' && (
    //           <div>
    //             <label>
    //               {__('Taxonomy:', 'amnesty')}
    //               <br />
    //               <TaxonomySelector value={attributes.taxonomy} onChange={addTaxonomyFilter} />
    //               <br />
    //             </label>
    //             <label>
    //               <div className="term-selector">
    //                 {__('Terms:', 'amnesty')}
    //                 <br />
    //                 <TermSelector
    //                   allTerms={allTerms}
    //                   value={attributes.terms}
    //                   onChange={addTerms}
    //                 />
    //               </div>
    //             </label>
    //           </div>
    //         )}
    //         {attributes.type === 'author' && (
    //           <label>
    //             {__('Author:', 'amnesty')}
    //             <br />
    //             <AuthorSelector
    //               value={attributes.authors}
    //               onChange={createUpdateAttribute('authors')}
    //             />
    //             <br />
    //           </label>
    //         )}
    //         <ToggleControl
    //           label={__('Display Post Author', 'amnesty')}
    //           checked={attributes.displayAuthor}
    //           onChange={createUpdateAttribute('displayAuthor')}
    //         />
    //         <ToggleControl
    //           label={__('Display Post Date', 'amnesty')}
    //           checked={attributes.displayPostDate}
    //           onChange={createUpdateAttribute('displayPostDate')}
    //         />
    //       </PanelBody>
    //     </InspectorControls>

    //     <div>
    //       {attributes.type === 'category' && (
    //         <DisplayCategories
    //           amount={attributes.amount || 3}
    //           category={attributes.category}
    //           style={attributes.style}
    //           prefix={keyPrefix}
    //           showAuthor={attributes.displayAuthor}
    //           showPostDate={attributes.displayPostDate}
    //           overrideTypes={postTypeOveride}
    //         />
    //       )}
    //       {attributes.type === 'custom' && (
    //         <DisplayCustom
    //           setAttributes={setAttributes}
    //           custom={attributes.custom || []}
    //           style={attributes.style}
    //           prefix={keyPrefix}
    //           showAuthor={attributes.displayAuthor}
    //           showPostDate={attributes.displayPostDate}
    //         />
    //       )}
    //       {attributes.type === 'select' && (
    //         <DisplaySelect
    //           setAttributes={setAttributes}
    //           selectedPosts={attributes.selectedPosts || []}
    //           preview={preview}
    //           style={attributes.style}
    //           prefix={keyPrefix}
    //           overrideTypes={postTypeOveride}
    //           showAuthor={attributes.displayAuthor}
    //           showPostDate={attributes.displayPostDate}
    //         />
    //       )}
    //       {attributes.type === 'taxonomy' && (
    //         <DisplayTaxonomies
    //           setAttributes={setAttributes}
    //           style={attributes.style}
    //           prefix={keyPrefix}
    //           taxonomy={attributes.taxonomy}
    //           showAuthor={attributes.displayAuthor}
    //           showPostDate={attributes.displayPostDate}
    //           terms={attributes.terms}
    //           amount={attributes.amount || 3}
    //         />
    //       )}
    //       {attributes.type === 'author' && (
    //         <DisplayAuthor
    //           setAttributes={setAttributes}
    //           style={attributes.style}
    //           prefix={keyPrefix}
    //           authors={attributes.authors}
    //           amount={10}
    //           showAuthor={attributes.displayAuthor}
    //           showPostDate={attributes.displayPostDate}
    //         />
    //       )}
    //       {attributes.type === 'feed' && (
    //         <DisplayFeed
    //           amount={attributes.amount || 3}
    //           category={attributes.category}
    //           overrideTypes={postTypeOveride}
    //           style={attributes.style}
    //           prefix={keyPrefix}
    //           showAuthor={attributes.displayAuthor}
    //           showPostDate={attributes.displayPostDate}
    //         />
    //       )}
    //     </div>
    //   </>
    // );
  };

  return (props) => (
    <>
      <BlockEdit {...props} />
      <PostList {...props} />
    </>
  );
}, 'withInspectorControl');

export default PostsWrapper;
