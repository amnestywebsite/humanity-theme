const setupFormControl = (block) => {
  const choices = block.querySelector('.checkboxGroup');
  const download = block.querySelector('.btn--download');

  choices.addEventListener('change', (event) => {
    const { target } = event;
    const {
      dataset: { value },
    } = target;

    // make sure that the URI is for the current origin
    const path = new URL(value).pathname;
    const href = new URL(path, window.location.origin);

    download.setAttribute('href', href.toString());
    download.setAttribute('download', href.toString().split('/').pop());
  });
};

export default () => {
  const blocks = Array.from(document.querySelectorAll('.download-block'));

  if (!blocks.length) {
    return;
  }

  const multiple = blocks.filter(({ classList }) => classList.contains('is-multiple'));
  multiple.forEach(setupFormControl);
};
