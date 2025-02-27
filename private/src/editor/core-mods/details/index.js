import { assign } from 'lodash';
import { addFilter } from '@wordpress/hooks';

/**
 * Remove button styles
 */
addFilter('blocks.registerBlockType', 'amnesty-core', (settings, name) => {
  if (name !== 'core/details') {
    return settings;
  }

  return assign({}, settings, {
    supports: {
      ...settings.supports,
      anchor: true,
    },
  });
});
