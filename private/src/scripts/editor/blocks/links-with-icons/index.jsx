/**
 * Module-specific
 */
import DisplayComponent from './DisplayComponent.jsx';
import './inner-block.jsx';
import deprecated from './deprecated.jsx';

/**
 * WordPress
 */
const { InnerBlocks } = wp.blockEditor;
const { registerBlockStyle, registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/repeatable-block', {
  // translators: [admin]
  title: __('Links with Icons Group', 'amnesty'),
  // translators: [admin]
  description: __('Add a repeatable links-with-icons block', 'amnesty'),
  icon: 'images-alt',
  category: 'amnesty-core',
  supports: {
    className: true,
    defaultStylePicker: false,
  },

  attributes: {
    backgroundColor: {
      type: 'string',
    },
    orientation: {
      type: 'string',
      default: 'horizontal',
    },
    quantity: {
      type: 'number',
      default: 2,
    },
    hideLines: {
      type: 'boolean',
      default: false,
    },
    dividerIcon: {
      type: 'text',
      default: 'none',
    },
  },
  deprecated,
  edit: DisplayComponent,

  save: () => <InnerBlocks.Content />,
});

registerBlockStyle('amnesty-core/repeatable-block', {
  name: 'square',
  // translators: [admin]
  label: __('Square', 'amnesty'),
});
