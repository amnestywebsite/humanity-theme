import DisplayComponent from './ColumnDisplayComponent.jsx';
import deprecated from './deprecated.jsx';

const { InnerBlocks } = wp.blockEditor;
const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/background-media-column', {
  // translators: [admin]
  title: __('Background Media Column', 'amnesty'),
  // translators: [admin]
  description: __('Column layout for Background Media block', 'amnesty'),
  category: 'amnesty-core',
  parent: ['amnesty-core/background-media'],
  supports: {
    alignWide: false,
    className: false,
    customClassName: false,
    defaultStylePicker: false,
    inserter: false,
    reusable: false,
  },
  attributes: {
    uniqId: {
      type: 'string',
      default: '',
      source: 'attribute',
      selector: 'div',
      attribute: 'id',
    },
    horizontalAlignment: {
      type: 'string',
    },
    verticalAlignment: {
      type: 'string',
    },
    image: {
      type: 'number',
      default: 0,
    },
    background: {
      type: 'string',
    },
    opacity: {
      type: 'number',
      default: 1,
    },
    focalPoint: {
      type: 'object',
      default: {
        x: 0.5,
        y: 0.5,
      },
    },
  },

  edit: DisplayComponent,

  deprecated,

  // eslint-disable-next-line react/display-name
  save: () => <InnerBlocks.Content />,
});
