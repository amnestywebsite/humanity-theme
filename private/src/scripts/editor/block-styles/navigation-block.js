const { registerBlockStyle } = wp.blocks;
const { _x } = wp.i18n;

registerBlockStyle('core/navigation', {
  name: 'grey-background',
  // translators: [admin]
  label: _x('Grey', 'block style', 'amnesty'),
});
