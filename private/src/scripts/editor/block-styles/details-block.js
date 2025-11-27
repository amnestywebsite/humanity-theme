const { registerBlockStyle } = wp.blocks;
const { _x } = wp.i18n;

registerBlockStyle('core/details', {
  name: 'small',
  // translators: [admin]
  label: _x('Small', 'block style', 'amnesty'),
});
