import classnames from 'classnames';

import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, SelectControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const isRightToLeft = document.documentElement.getAttribute('dir') === 'rtl';
const hasI18n = Object.prototype.hasOwnProperty.call(window, 'amnestyCoreI18n');

const getDirections = () => {
  const directionalOptions = [
    /* translators: [admin] text alignment. for RTL languages, localise as 'Right' */
    { value: 'start', label: __('Left', 'amnesty') },
    /* translators: [admin] text alignment */
    { value: '', label: __('Default', 'amnesty') },
  ];

  if (!isRightToLeft) {
    /* translators: [admin] text alignment. for RTL languages, localise as 'Left' */
    directionalOptions.push({ value: 'end', label: __('Right', 'amnesty') });
  }

  return directionalOptions;
};

const getQuoteStyles = () => {
  if (!hasI18n) {
    return '';
  }

  const { openDoubleQuote, closeDoubleQuote, openSingleQuote, closeSingleQuote } =
    window.amnestyCoreI18n;

  return `.blockquote {
    quotes: '${openDoubleQuote}' '${closeDoubleQuote}' "${openSingleQuote}" "${closeSingleQuote}";
  }`;
};

export default function Edit({ attributes, setAttributes }) {
  const {
    align = '',
    size = '',
    colour = '',
    capitalise = false,
    lined = true,
    content = '',
    citation = '',
  } = attributes;

  const classes = classnames('blockquote', {
    [`align-${align}`]: !!align,
    [`is-${size}`]: !!size,
    [`is-${colour}`]: !!colour,
    'is-capitalised': capitalise,
    'is-lined': lined,
  });

  const blockProps = useBlockProps({
    className: classes,
  });

  return (
    <>
      <InspectorControls>
        <PanelBody>
          <SelectControl
            /* translators: [admin] */
            label={__('Alignment', 'amnesty')}
            value={align}
            onChange={(newAlign) => setAttributes({ align: newAlign })}
            options={getDirections()}
          />
          <SelectControl
            /* translators: [admin] */
            label={__('Size', 'amnesty')}
            value={size}
            onChange={(newSize) => setAttributes({ size: newSize })}
            options={[
              /* translators: [admin] */
              { value: 'small', label: __('Small', 'amnesty') },
              /* translators: [admin] */
              { value: 'medium', label: __('Medium', 'amnesty') },
              /* translators: [admin] */
              { value: '', label: __('Large', 'amnesty') },
            ]}
          />
          <SelectControl
            /* translators: [admin] */
            label={__('Text Colour', 'amnesty')}
            value={colour}
            onChange={(newColour) => setAttributes({ colour: newColour })}
            options={[
              /* translators: [admin] */
              { value: '', label: __('Black', 'amnesty') },
              /* translators: [admin] */
              { value: 'grey', label: __('Grey', 'amnesty') },
              /* translators: [admin] */
              { value: 'white', label: __('White', 'amnesty') },
            ]}
          />
          {!isRightToLeft && (
            <ToggleControl
              /* translators: [admin] */
              label={__('Capitalise', 'amnesty')}
              /* translators: [admin] */
              help={__('Capitalise the content.', 'amnesty')}
              checked={capitalise}
              onChange={(newCaps) => setAttributes({ capitalise: newCaps })}
            />
          )}
          <ToggleControl
            /* translators: [admin] */
            label={__('Line', 'amnesty')}
            /* translators: [admin] */
            help={__('Toggle display of line embellishment.', 'amnesty')}
            checked={lined}
            onChange={(newLine) => setAttributes({ lined: newLine })}
          />
        </PanelBody>
      </InspectorControls>
      <style>{getQuoteStyles()}</style>
      <div {...blockProps}>
        <div>
          <RichText
            tagName="p"
            /* translators: [admin] */
            placeholder={__('(Insert Quote Text)', 'amnesty')}
            value={content}
            allowedFormats={[]}
            onChange={(newContent) => setAttributes({ content: newContent })}
          />
        </div>
        <div>
          <RichText
            tagName="cite"
            /* translators: [admin] */
            placeholder={__('(Insert Citation)', 'amnesty')}
            value={citation}
            allowedFormats={[]}
            onChange={(newCitation) => setAttributes({ citation: newCitation })}
          />
        </div>
      </div>
    </>
  );
}
