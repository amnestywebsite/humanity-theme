const { registerBlockStyle } = wp.blocks;
const { _x } = wp.i18n;

// Removes unwanted inspector controls
/* eslint-disable no-param-reassign */
const observer = new MutationObserver(() => {
  const InspectorControls = document.querySelectorAll('.bbTabs_options');

  InspectorControls.forEach((element) => {
    element.style.display = 'none';
    return observer.disconnect;
  });
});

// Listens for childList to be populated
observer.observe(document, {
  childList: true,
  subtree: true,
});

registerBlockStyle('bigbite/tabs', {
  name: 'light',
  // translators: [admin]
  label: _x('Light', 'block style', 'amnesty'),
});

registerBlockStyle('bigbite/tabs', {
  name: 'grey',
  isDefault: true,
  // translators: [admin]
  label: _x('Grey', 'block style', 'amnesty'),
});
