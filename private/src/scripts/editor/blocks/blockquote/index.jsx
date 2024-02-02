import DisplayComponent from './DisplayComponent.jsx';
import deprecated from './deprecated.jsx';
import transforms from './transforms.jsx';

const { assign } = lodash;
const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

/**
 * Hide the core blockquote from the list, as we have our own version.
 */
wp.hooks.addFilter('blocks.registerBlockType', 'amnesty-core', (settings, name) => {
  if (name === 'core/quote') {
    return assign({}, settings, {
      supports: assign({}, settings.supports, {
        inserter: false,
      }),
    });
  }

  return settings;
});

registerBlockType('amnesty-core/quote', {
  // translators: [admin]
  title: __('Blockquote', 'amnesty'),
  // translators: [admin]
  description: __('Add a blockquote block', 'amnesty'),
  icon: (
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path fill="none" d="M0 0h24v24H0V0z" />
      <path d="M19 18h-6l2-4h-2V6h8v7l-2 5zm-2-2l2-3V8h-4v4h4l-2 4zm-8 2H3l2-4H3V6h8v7l-2 5zm-2-2l2-3V8H5v4h4l-2 4z" />
    </svg>
  ),
  category: 'amnesty-core',
  supports: {
    className: false,
  },
  attributes: {
    align: {
      type: 'string',
    },
    size: {
      type: 'string',
    },
    colour: {
      type: 'string',
    },
    capitalise: {
      type: 'boolean',
    },
    lined: {
      type: 'boolean',
    },
    content: {
      type: 'string',
    },
    citation: {
      type: 'string',
    },
  },

  transforms,

  deprecated,

  edit: DisplayComponent,

  save: () => null,
});
