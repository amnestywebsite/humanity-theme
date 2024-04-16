import classnames from 'classnames';
import blockIcon from './icon.jsx';
import DisplayComponent from './DisplayComponent.jsx';
import deprecated from './deprecated.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/counter', {
  // translators: [admin]
  title: __('Counter', 'amnesty'),
  // translators: [admin]
  description: __(
    'Add a numeric field which, when scrolled into view, will count up from zero',
    'amnesty',
  ),
  keywords: [
    // translators: [admin]
    __('Stat', 'amnesty'),
    // translators: [admin]
    __('Counter', 'amnesty'),
  ],
  icon: blockIcon,
  category: 'amnesty-core',

  attributes: {
    alignment: {
      type: 'string',
    },
    duration: {
      type: 'number',
      default: 2,
    },
    value: {
      type: 'string',
    },
  },

  deprecated,
  edit: DisplayComponent,

  save: () => null,
});
