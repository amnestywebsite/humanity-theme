import DisplayComponent from './DisplayComponent.jsx';
import deprecated from './deprecated.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

const blockAttributes = {
  style: {
    type: 'string',
  },
  centred: {
    type: 'boolean',
  },
  label: {
    type: 'string',
  },
  content: {
    type: 'string',
  },
  imageID: {
    type: 'integer',
  },
  imageURL: {
    type: 'string',
  },
  imageAlt: {
    type: 'string',
  },
  link: {
    type: 'string',
  },
  scrollLink: {
    type: 'string',
  },
  linkText: {
    type: 'string',
  },
  largeImageURL: {
    type: 'string',
  },
};

registerBlockType('amnesty-core/custom-card', {
  // translators: [admin]
  title: __('Custom Card', 'amnesty'),
  // translators: [admin]
  description: __('Custom Card block', 'amnesty'),
  icon: 'megaphone',
  category: 'amnesty-core',
  supports: {
    className: false,
    align: true,
  },
  attributes: blockAttributes,
  styles: [
    {
      name: 'style-1',
      // translators: [admin]
      label: __('Style 1'),
      isDefault: true,
    },
    {
      name: 'style-2',
      // translators: [admin]
      label: __('Style 2'),
    },
    {
      name: 'style-3',
      // translators: [admin]
      label: __('Style 3'),
    },
  ],

  deprecated,

  edit: DisplayComponent,
  save: () => null,
});
