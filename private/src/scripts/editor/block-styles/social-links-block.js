const { registerBlockStyle } = wp.blocks;
const { _x } = wp.i18n;

registerBlockStyle('core/social-links', {
  name: 'dark',
  // translators: [admin]
  label: _x('Dark', 'block style', 'amnesty'),
});

registerBlockStyle('core/social-links', {
  name: 'dark-circle',
  // translators: [admin]
  label: _x('Dark Circle', 'block style', 'amnesty'),
});

registerBlockStyle('core/social-links', {
  name: 'light',
  // translators: [admin]
  label: _x('Light', 'block style', 'amnesty'),
});

registerBlockStyle('core/social-links', {
  name: 'light-circle',
  // translators: [admin]
  label: _x('Light Circle', 'block style', 'amnesty'),
});

registerBlockStyle('core/social-links', {
  name: 'logos-only-dark',
  // translators: [admin]
  label: _x('Logos Only Dark', 'block style', 'amnesty'),
});

registerBlockStyle('core/social-links', {
  name: 'logos-only-light',
  // translators: [admin]
  label: _x('Logos Only Light', 'block style', 'amnesty'),
});
