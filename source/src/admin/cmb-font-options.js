document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('#cmb2-metabox-amnesty_font_options_page');

  if (!form) {
    return;
  }

  const initial = form.querySelector('.cmb2-option[name="font_load_type"]:checked').value;

  const repaint = (selector, compare) => {
    form.querySelectorAll(selector).forEach((el) => {
      if (el.dataset.showFor.indexOf(compare) !== -1) {
        // eslint-disable-next-line no-param-reassign
        el.closest('.cmb-row').style.display = 'block';
      } else {
        // eslint-disable-next-line no-param-reassign
        el.closest('.cmb-row').style.display = 'none';
      }
    });
  };

  repaint('[data-show-for]', initial);

  form.addEventListener('click', (e) => {
    if (!e.target.classList.contains('cmb2-options')) {
      return;
    }

    repaint('[data-show-for]', e.target.value);
  });
});
