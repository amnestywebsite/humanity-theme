import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/block-responsive-iframe', {
  // translators: [admin]
  title: __('Responsive Iframe', 'amnesty'),
  icon: 'welcome-widgets-menus',
  category: 'amnesty-core',
  keywords: [
    // translators: [admin]
    __('Iframe', 'amnesty'),
    // translators: [admin]
    __('Responsive', 'amnesty'),
    // translators: [admin]
    __('Fluid', 'amnesty'),
  ],
  attributes: {
    embedUrl: {
      type: 'string',
    },
    height: {
      type: 'string',
    },
    minHeight: {
      type: 'string',
    },
    width: {
      type: 'string',
    },
    caption: {
      type: 'string',
    },
    alignment: {
      type: 'string',
    },
    title: {
      type: 'string',
    },
  },

  edit: DisplayComponent,

  // Returns null due to the component being rendered server side
  save: () => null,
});
