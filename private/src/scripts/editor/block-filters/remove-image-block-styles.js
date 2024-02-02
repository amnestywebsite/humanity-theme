wp.domReady(() => {
  const styles = wp.data.select('core/blocks').getBlockStyles('core/image');

  styles.forEach((style) => {
    wp.data.dispatch('core/blocks').removeBlockStyles('core/image', style.name);
  });
});
