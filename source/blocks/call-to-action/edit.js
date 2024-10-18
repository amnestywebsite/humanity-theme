import classnames from 'classnames';

import { isUndefined } from 'lodash';
import { InnerBlocks, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { useEffect, useRef } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { applyFilters } from '@wordpress/hooks';

const ALLOWED_BLOCKS = [
  'core/buttons',
  'amnesty-core/block-button',
  'amnesty-core/block-download',
  'amnesty-core/iframe-button',
];

// type is undef for old versions of block
const getTemplate = (type) => {
  if (isUndefined(type)) {
    return [['amnesty-core/block-button']];
  }

  if (type === 'download') {
    return [['amnesty-core/block-download']];
  }

  return [['core/buttons']];
};

const edit = ({ attributes, setAttributes }) => {
  const allowedInnerBlocks = applyFilters('add-modal-to-allowed-blocks', ALLOWED_BLOCKS);
  const mounted = useRef();

  useEffect(() => {
    if (!mounted?.current) {
      mounted.current = true;
      if (!attributes.actionType) {
        setAttributes({ actionType: '' });
      }
    }
  }, []);

  return (
    <>
      <InspectorControls>
        <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
          <SelectControl
            // translators: [admin]
            label={__('Background Style', 'amnesty')}
            options={[
              {
                // translators: [admin]
                label: __('Light', 'amnesty'),
                value: '',
              },
              {
                // translators: [admin]
                label: __('Grey', 'amnesty'),
                value: 'shade',
              },
            ]}
            value={attributes.background}
            onChange={(background) => setAttributes({ background })}
          />
        </PanelBody>
      </InspectorControls>
      <div
        className={classnames('callToAction', {
          [`callToAction--${attributes.background}`]: attributes.background,
        })}
      >
        <RichText
          tagName="h2"
          className="callToAction-preHeading"
          // translators: [admin]
          placeholder={__('(Pre-heading)', 'amnesty')}
          allowedFormats={[]}
          value={attributes.preheading}
          keepPlaceholderOnFocus={true}
          onChange={(preheading) => setAttributes({ preheading })}
        />
        <RichText
          tagName="h1"
          className="callToAction-heading"
          // translators: [admin]
          placeholder={__('(Heading)', 'amnesty')}
          allowedFormats={[]}
          value={attributes.title}
          keepPlaceholderOnFocus={true}
          onChange={(title) => setAttributes({ title })}
        />
        <RichText
          tagName="p"
          className="callToAction-content"
          // translators: [admin]
          placeholder={__('(Content)', 'amnesty')}
          value={attributes.content}
          keepPlaceholderOnFocus={true}
          onChange={(content) => setAttributes({ content })}
        />
        <div className="callToAction-buttonContainer">
          <InnerBlocks
            templateInsertUpdatesSelection={false}
            template={getTemplate(attributes.actionType)}
            templateLock={false}
            allowedBlocks={allowedInnerBlocks}
          />
        </div>
      </div>
    </>
  );
};

export default edit;
