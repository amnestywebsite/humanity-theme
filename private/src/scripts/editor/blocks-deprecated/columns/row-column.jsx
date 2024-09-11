/**
 * WordPress
 */
const { InnerBlocks } = wp.blockEditor;
const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/block-row-column', {
  // translators: [admin]
  title: __('Row Column', 'amnesty'),
  description: '',
  icon: 'plus',
  category: 'layout',
  parent: ['amnesty-core/block-row'],
  supports: {
    className: false,
    inserter: false,
  },
  attributes: {
    size: {
      type: 'string',
    },
  },

  edit() {
    return (
      <div className="rowColumn">
        <InnerBlocks templateLock={false} />
      </div>
    );
  },

  save() {
    return (
      <div className="rowColumn">
        <InnerBlocks.Content />
      </div>
    );
  },
});
