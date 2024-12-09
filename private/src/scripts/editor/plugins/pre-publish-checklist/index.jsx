import PrePublishRenderer from './DisplayComponent.jsx';

const { registerPlugin } = wp.plugins;

/**
 * Register the plugin panel with gutenberg
 */
registerPlugin('amnesty/pre-publish-checklist', {
  render: PrePublishRenderer,
});
