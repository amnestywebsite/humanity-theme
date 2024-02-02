import classnames from 'classnames';

const { InnerBlocks, InspectorControls, RichText, useBlockProps } = wp.blockEditor;
const { PanelBody, PanelRow, TextControl, ToggleControl } = wp.components;
const { Fragment } = wp.element;
const { applyFilters } = wp.hooks;
const { __ } = wp.i18n;

const ALLOWED_BLOCKS = applyFilters('amnesty.blocks.collapsable.allowedBlocks', [
  'amnesty-core/block-call-to-action',
  'amnesty-core/block-download',
  'amnesty-core/block-section',
  'amnesty-core/countdown-timer',
  'amnesty-core/counter',
  'amnesty-core/custom-card',
  'amnesty-core/embed-flourish',
  'amnesty-core/embed-infogram',
  'amnesty-core/embed-sutori',
  'amnesty-core/embed-tickcounter',
  'amnesty-core/quote',
  'amnesty-core/tweet-block',
  'core/buttons',
  'core/embed',
  'core/heading',
  'core/image',
  'core/list',
  'core/paragraph',
  'core/spacer',
]);

const DisplayComponent = ({ attributes, className, setAttributes }) => {
  const extraProps = {
    className: classnames(className, {
      'is-collapsed': attributes.collapsed,
    }),
  };

  const blockProps = useBlockProps(extraProps);

  return (
    <Fragment>
      <InspectorControls>
        {/* translators: [admin] */}
        <PanelBody title={__('Settings', 'amnesty')}>
          <PanelRow>
            <ToggleControl
              // translators: [admin]
              label={__('Collapsed by default', 'amnesty')}
              // translators: [admin]
              help={__('Whether the block contents should be hidden by default', 'amnesty')}
              checked={attributes.collapsed}
              onChange={() => setAttributes({ collapsed: !attributes.collapsed })}
            />
          </PanelRow>
          <PanelRow>
            <TextControl
              label={__('HTML anchor')}
              // translators: [admin]
              help={__('Label this block with an HTML anchor (#example).', 'amnesty')}
              value={attributes.anchor}
              onChange={(anchor) => setAttributes({ anchor })}
            />
          </PanelRow>
        </PanelBody>
      </InspectorControls>
      <figure {...blockProps}>
        <figcaption>
          <RichText
            tagName="h2"
            // translators: [admin]
            placeholder={__('Find out more', 'amnesty')}
            keepPlaceholderOnFocus={true}
            value={attributes.title}
            onChange={(title) => setAttributes({ title })}
          />
          <span
            className="dashicons dashicon dashicons-arrow-down-alt2"
            onClick={() => setAttributes({ collapsed: !attributes.collapsed })}
          />
        </figcaption>
        <div className={`${className}-inner`}>
          <InnerBlocks allowedBlocks={ALLOWED_BLOCKS} template={[['core/paragraph']]} />
        </div>
      </figure>
    </Fragment>
  );
};

export default DisplayComponent;
