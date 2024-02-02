const { registerBlockStyle } = wp.blocks;
const { _x } = wp.i18n;

registerBlockStyle('core/table', {
  name: 'responsive',
  // translators: [admin]
  label: _x('Responsive', 'block style', 'amnesty'),
});
