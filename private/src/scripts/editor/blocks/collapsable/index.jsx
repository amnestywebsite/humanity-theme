import DisplayComponent from './DisplayComponent.jsx';

const { assign } = lodash;
const { InnerBlocks } = wp.blockEditor;
const { registerBlockType, createBlock } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/collapsable', {
  // translators: [admin]
  title: __('Collapsable', 'amnesty'),
  icon: 'arrow-down-alt2',
  category: 'amnesty-core',
  keywords: [
    // translators: [admin]
    __('collapsible', 'amnesty'),
    // translators: [admin]
    __('accordion', 'amnesty'),
    // translators: [admin]
    __('drawer', 'amnesty'),
    // translators: [admin]
    __('open', 'amnesty'),
    // translators: [admin]
    __('closed', 'amnesty'),
  ],
  supports: {
    className: true,
    inserter: false,
  },
  attributes: {
    anchor: {
      type: 'string',
      default: '',
    },
    collapsed: {
      type: 'boolean',
      default: false,
    },
    title: {
      type: 'string',
      default: '',
    },
  },
  transforms: {
    to: [
      {
        type: 'block',
        blocks: ['core/details'],
        transform: ({ collapsed, title }, innerBlocks) =>
          createBlock(
            'core/details',
            {
              showContent: !collapsed,
              summary: title,
            },
            innerBlocks,
          ),
      },
    ],
  },
  edit: DisplayComponent,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'CollapsableBlockSave' }),
});
