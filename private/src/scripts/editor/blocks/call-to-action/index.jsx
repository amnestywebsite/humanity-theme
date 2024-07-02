import DisplayComponent from './DisplayComponent.jsx';
import transforms from './transforms.jsx';
import deprecated from './deprecated.jsx';

const { InnerBlocks } = wp.blockEditor;
const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/block-call-to-action', {
  // translators: [admin]
  title: __('Call To Action', 'amnesty'),
  icon: 'megaphone',
  category: 'amnesty-core',
  // translators: [admin]
  keywords: [__('Call To Action', 'amnesty')],
  supports: {
    className: false,
    multiple: true,
  },
  attributes: {
    preheading: {
      type: 'string',
    },
    title: {
      type: 'string',
    },
    content: {
      type: 'string',
    },
    background: {
      type: 'string',
    },
    actionType: {
      type: 'string',
    },
  },

  transforms,

  deprecated,

  edit: DisplayComponent,

  save: () => <InnerBlocks.Content />,
});
