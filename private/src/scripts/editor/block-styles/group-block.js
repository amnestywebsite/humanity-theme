const { registerBlockStyle } = wp.blocks;
const { _x } = wp.i18n;

registerBlockStyle('core/group', {
  name: 'square-border',
  // translators: [admin]
  label: _x('Square Border', 'block style', 'amnesty'),
});
