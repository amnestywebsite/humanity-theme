const { assign, omit } = lodash;
const { addFilter } = wp.hooks;

/**
 * Remove the html source from the html core block
 */
addFilter('blocks.registerBlockType', 'amnesty-core', (settings, name) => {
  if (name !== 'core/html') {
    return settings;
  }

  return assign({}, omit(settings, ['attributes']), {
    attributes: {
      content: {
        type: 'string',
      },
    },
  });
});
