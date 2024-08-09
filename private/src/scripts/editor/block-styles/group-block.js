const { registerBlockStyle } = wp.blocks;
const { _x } = wp.i18n;

registerBlockStyle('core/group', {
  name: 'square-border',
  // translators: [admin]
  label: _x('Square Border', 'block style', 'amnesty'),
});

registerBlockStyle('core/group', {
  name: 'light',
  // translators: [admin]
  label: _x('Light Background', 'block style', 'amnesty'),
});

registerBlockStyle('core/group', {
  name: 'dark',
  // translators: [admin]
  label: _x('Dark Background', 'block style', 'amnesty'),
});
