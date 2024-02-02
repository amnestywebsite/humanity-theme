const { assign } = lodash;
const { userRoles } = window;
const { addFilter } = wp.hooks;

const blocks = ['amnesty-core/code', 'core/file'];

/**
 * Prevent non-admins from using the raw code block
 */
addFilter('blocks.registerBlockType', 'amnesty-core', (settings, name) => {
  if (blocks.indexOf(name) === -1) {
    return settings;
  }

  if (!userRoles || userRoles.indexOf('administrator') !== -1) {
    return settings;
  }

  return assign({}, settings, {
    supports: assign({}, settings.supports, {
      inserter: false,
    }),
  });
});
