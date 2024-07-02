import classnames from 'classnames';
import memoize from 'memize';
import './fact.jsx';

const { times } = lodash;
const { InnerBlocks, InspectorControls, RichText } = wp.blockEditor;
const { registerBlockType } = wp.blocks;
const { PanelBody, RangeControl, SelectControl } = wp.components;
const { Fragment } = wp.element;
const { __ } = wp.i18n;

const ALLOWED_BLOCKS = ['amnesty-core/key-fact'];
const getLayoutTemplate = memoize((blocks) => times(blocks, () => ALLOWED_BLOCKS));

registerBlockType('amnesty-core/key-facts', {
  // translators: [admin]
  title: __('Key Facts', 'amnesty'),
  // translators: [admin]
  description: __('Add a key facts block', 'amnesty'),
  icon: 'lightbulb',
  category: 'amnesty-core',
  supports: {
    className: false,
    inserter: false,
  },
  attributes: {
    title: {
      type: 'string',
    },
    quantity: {
      type: 'number',
    },
    background: {
      type: 'string',
    },
  },
  edit({ attributes, setAttributes }) {
    const { title, quantity = 2, background = '' } = attributes;

    const classes = classnames('factBlock', {
      'has-background': !!background,
      [`has-${background}-background-color`]: !!background,
    });

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody>
            <RangeControl
              // translators: [admin]
              label={__('Quantity', 'amnesty')}
              value={quantity}
              onChange={(newQuantity) => setAttributes({ quantity: newQuantity })}
              min={1}
              max={4}
            />
            <SelectControl
              // translators: [admin]
              label={__('Background Colour', 'amnesty')}
              value={background}
              onChange={(newBgColor) => setAttributes({ background: newBgColor })}
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
            value={title}
            allowedFormats={[]}
            onChange={(newTitle) => setAttributes({ title: newTitle })}
            format="string"
          />
          <InnerBlocks
            template={getLayoutTemplate(quantity)}
            templateLock="insert"
            allowedBlocks={ALLOWED_BLOCKS}
          />
        </div>
      </Fragment>
    );
  },

  save({ attributes }) {
    const { title = '', background = '' } = attributes;

    const classes = classnames('factBlock', {
      'has-background': !!background,
      [`has-${background}-background-color`]: !!background,
    });

    const label = title.replace(' ', '-').toLowerCase();

    return (
      <aside className={classes} aria-labelledby={label}>
        <h2 id={label} className="factBlock-title" aria-hidden="true">
          {title}
        </h2>
        <ol>
          <InnerBlocks.Content />
        </ol>
      </aside>
    );
  },
});
