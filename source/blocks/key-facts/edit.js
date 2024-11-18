import classnames from 'classnames';
import memoize from 'memize';

import { times } from 'lodash';
import { InnerBlocks, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const ALLOWED_BLOCKS = ['amnesty-core/key-fact'];
const getLayoutTemplate = memoize((blocks) => times(blocks, () => ALLOWED_BLOCKS));

const edit = ({ attributes, setAttributes }) => {
  const classes = classnames('factBlock', {
    'has-background': !!attributes.background,
    [`has-${attributes.background}-background-color`]: !!attributes.background,
  });

  return (
    <>
      <InspectorControls>
        <PanelBody>
          <RangeControl
            // translators: [admin]
            label={__('Quantity', 'amnesty')}
            value={attributes.quantity}
            onChange={(quantity) => setAttributes({ quantity })}
            min={1}
            max={4}
          />
          <SelectControl
            // translators: [admin]
            label={__('Background Colour', 'amnesty')}
            value={attributes.background}
            onChange={(background) => setAttributes({ background })}
            options={[
              // translators: [admin]
              { value: '', label: __('None', 'amnesty') },
              // translators: [admin]
              { value: 'very-light-gray', label: __('Grey', 'amnesty') },
            ]}
          />
        </PanelBody>
      </InspectorControls>
      <div className={classes}>
        <RichText
          className="factBlock-title"
          tagName="h2"
          // translators: [admin]
          placeholder={__('(Insert Title)', 'amnesty')}
          keepPlaceholderOnFocus={true}
          value={attributes.title}
          allowedFormats={[]}
          onChange={(title) => setAttributes({ title })}
          format="string"
        />
        <InnerBlocks
          template={getLayoutTemplate(attributes.quantity)}
          templateLock="insert"
          allowedBlocks={ALLOWED_BLOCKS}
        />
      </div>
    </>
  );
};

export default edit;
