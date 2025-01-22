import './style.scss';
import './editor.scss';

import { assign } from 'lodash';
import { registerBlockType } from '@wordpress/blocks';
import { addFilter } from '@wordpress/hooks';

import edit from './edit.jsx';
import metadata from './block.json';
import deprecated from './deprecated.jsx';
import transforms from './transforms.jsx';

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  transforms,
  edit,
  save: () => null,
});

/**
 * Hide the core blockquote from the list, as we have our own version.
 */
addFilter('blocks.registerBlockType', 'amnesty-core', (settings, name) => {
  if (name === 'core/quote') {
    return assign({}, settings, {
      supports: assign({}, settings.supports, {
        inserter: false,
      }),
    });
  }

  return settings;
});
