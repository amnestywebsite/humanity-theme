import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/iframe-button', {
  // translators: [admin]
  title: __('Iframe Button', 'amnesty'),
  // translators: [admin]
  description: __('A button which, when clicked, will toggle visibility of an iframe', 'amnesty'),
  icon: 'shortcode',
  category: 'amnesty-core',
  keywords: [
    // translators: [admin]
    __('Iframe', 'amnesty'),
    // translators: [admin]
    __('Button', 'amnesty'),
  ],
  attributes: {
    alignment: {
      type: 'string',
      default: 'none',
    },
    iframeUrl: {
      type: 'string',
    },
    iframeHeight: {
      type: 'number',
      default: 760,
    },
    buttonText: {
      type: 'string',
    },
    title: {
      type: 'string',
    },
  },
  supports: {
    className: false,
    defaultStylePicker: false,
  },
  styles: [
    {
      name: 'default',
      // translators: [admin]
      label: __('Primary (Yellow)', 'amnesty'),
      isDefault: true,
    },
    {
      name: 'dark',
      // translators: [admin]
      label: __('Dark', 'amnesty'),
    },
    {
      name: 'light',
      // translators: [admin]
      label: __('Light', 'amnesty'),
    },
  ],

  edit: DisplayComponent,

  // Returns null due to the component being rendered server side
  save: () => null,
});
