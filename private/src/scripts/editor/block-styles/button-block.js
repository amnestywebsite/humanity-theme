const { omit } = lodash;
const { registerBlockStyle } = wp.blocks;
const { addFilter } = wp.hooks;
const { _x } = wp.i18n;

/**
 * Remove button styles
 */
addFilter('blocks.registerBlockType', 'amnesty-core', (settings, name) => {
  if (name !== 'core/button') {
    return settings;
  }

  return omit(settings, ['styles']);
});

registerBlockStyle('core/button', {
  name: 'dark',
  // translators: [admin]
  label: _x('Dark', 'block style', 'amnesty'),
});

registerBlockStyle('core/button', {
  name: 'link',
  // translators: [admin]
  label: _x('Back Link', 'block style', 'amnesty'),
});

registerBlockStyle('core/button', {
  name: 'search',
  // translators: [admin]
  label: _x('Search', 'block style', 'amnesty'),
});
