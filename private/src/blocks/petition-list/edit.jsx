import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl, ToggleControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';
import DisplayFeed from '../post-list/components/DisplayFeed.jsx';
import DisplaySelect from '../post-list/components/DisplaySelect.jsx';

export default function Edit({ attributes, setAttributes }) {
  const [preview, setPreview] = useState(attributes.selectedPosts.length > 0);
  const togglePreview = () => setPreview((prev) => !prev);
  const keyPrefix = Math.random().toString(36).substring(7);
  // Petition post name may be different to its original codename.
  const petitionsSlug = window.aiPostTypeCodenames.petition;

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Petition Settings', 'amnesty')}>
          {attributes.type === 'select' && (
            <button onClick={togglePreview}>
              {preview ? __('Hide Preview', 'amnesty') : __('Show Preview', 'amnesty')}
            </button>
          )}
          <SelectControl
            label={__('Type', 'amnesty')}
            options={metadata.attributes.type.enum.map((v) => ({
              value: v,
              label: v.charAt(0).toUpperCase() + v.slice(1),
            }))}
            value={attributes.type}
            onChange={(type) => setAttributes({ type })}
          />
          <RangeControl
            label={__('Number of posts to show:', 'amnesty')}
            min={1}
            max={100}
            value={attributes.amount || 3}
            onChange={(amount) => setAttributes({ amount })}
          />
          <ToggleControl
            label={__('Display Post Author', 'amnesty')}
            checked={attributes.displayAuthor}
            onChange={(displayAuthor) => setAttributes({ displayAuthor })}
          />
          <ToggleControl
            label={__('Display Post Date', 'amnesty')}
            checked={attributes.displayPostDate}
            onChange={(displayPostDate) => setAttributes({ displayPostDate })}
          />
        </PanelBody>
      </InspectorControls>
      <div {...useBlockProps()}>
        {attributes.type === 'feed' && (
          <DisplayFeed
            amount={attributes.amount || 3}
            overrideTypes={{ [petitionsSlug]: true }}
            style="petition"
            prefix={keyPrefix}
            showAuthor={attributes.displayAuthor}
            showPostDate={attributes.displayPostDate}
          />
        )}
        {attributes.type === 'select' && (
          <DisplaySelect
            setAttributes={setAttributes}
            selectedPosts={attributes.selectedPosts || []}
            defaultPostType={petitionsSlug}
            preview={preview}
            style="petition"
            prefix={keyPrefix}
            showAuthor={attributes.displayAuthor}
            showPostDate={attributes.displayPostDate}
          />
        )}
      </div>
    </>
  );
}
