import classnames from 'classnames';
import memoize from 'memize';
import { times } from 'lodash';
import { InnerBlocks, InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const ALLOWED_BLOCKS = ['amnesty-core/key-fact'];
const getLayoutTemplate = memoize((blocks) => times(blocks, () => ALLOWED_BLOCKS));

export default function Edit({ attributes, setAttributes }) {
  // Ensure quantity has a fallback default
  const quantity = attributes.quantity || 1;

  const classes = classnames('factBlock', {
    'has-background': !!attributes.background,
    [`has-${attributes.background}-background-color`]: !!attributes.background,
  });

  const blockProps = useBlockProps({
    className: classes,
  });

  return (
    <>
      <InspectorControls>
        <PanelBody>
          <RangeControl
            /* translators: [admin] */
            label={__('Quantity', 'amnesty')}
            value={quantity}
            onChange={(newQuantity) => setAttributes({ quantity: newQuantity })}
            min={1}
            max={4}
          />
          <SelectControl
            /* translators: [admin] */
            label={__('Background Colour', 'amnesty')}
            value={attributes.background}
            onChange={(newBackground) => setAttributes({ background: newBackground })}
            options={[
              /* translators: [admin] */
              { value: '', label: __('None', 'amnesty') },
              /* translators: [admin] */
              { value: 'very-light-gray', label: __('Grey', 'amnesty') },
            ]}
          />
        </PanelBody>
      </InspectorControls>
      <div {...blockProps}>
        <RichText
          className="factBlock-title"
          tagName="h2"
          /* translators: [admin] */
          placeholder={__('(Insert Title)', 'amnesty')}
          value={attributes.title}
          allowedFormats={[]}
          onChange={(newTitle) => setAttributes({ title: newTitle })}
        />
        <InnerBlocks template={getLayoutTemplate(attributes.quantity)} templateLock="all" />
      </div>
    </>
  );
}
