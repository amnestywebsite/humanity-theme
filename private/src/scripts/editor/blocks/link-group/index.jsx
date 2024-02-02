import DisplayComponent from './DisplayComponent.jsx';
import deprecated from './deprecated.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/link-group', {
  // translators: [admin]
  title: __('Link Block', 'amnesty'),
  // translators: [admin]
  description: __(
    'A collection of links, used for things such as further reading suggestions.',
    'amnesty',
  ),
  icon: 'list-view',
  category: 'amnesty-core',
  keywords: [
    // translators: [admin]
    __('Links', 'amnesty'),
    // translators: [admin]
    __('Further Reading', 'amnesty'),
  ],

  attributes: {
    align: {
      type: 'string',
      default: '',
    },
    title: {
      type: 'string',
      default: '',
    },
    items: {
      type: 'array',
      default: [],
    },
  },

  deprecated,

  edit: DisplayComponent,

  save: () => null,
});
