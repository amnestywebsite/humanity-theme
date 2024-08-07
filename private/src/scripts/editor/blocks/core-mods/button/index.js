const { select, subscribe } = wp.data;
const { __ } = wp.i18n;

subscribe(() => {
  const selected = select('core/block-editor').getSelectedBlock();

  if (!selected || selected.name !== 'core/button') {
    return;
  }

  // translators: [admin]
  const search = __('Border settings');
  const xpath = `//button[contains(@class, 'components-button')][contains(text(),'${search}')]`;

  const { singleNodeValue: button } = document.evaluate(
    xpath,
    document,
    null,
    XPathResult.FIRST_ORDERED_NODE_TYPE,
    null,
  );

  if (!button) {
    return;
  }

  const toHide = button.parentElement.parentElement;
  toHide.parentElement.style.display = 'none';
});
