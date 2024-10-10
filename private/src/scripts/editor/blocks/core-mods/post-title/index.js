const { assign } = lodash;
const { addFilter } = wp.hooks;

/**
 * Remove button styles
 */
addFilter('blocks.registerBlockType', 'amnesty-core', (settings, name) => {
  if (name !== 'core/post-title') {
    return settings;
  }

  return assign({}, settings, {
    supports: {
      ...settings.supports,
      anchor: true,
    },
  });
});
