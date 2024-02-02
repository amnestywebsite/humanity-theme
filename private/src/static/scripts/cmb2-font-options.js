/* global jQuery */
/* eslint-disable prefer-arrow-callback,func-names */
jQuery(function ($) {
  const form = $('#cmb2-metabox-amnesty_font_options_page');

  if (!form) {
    return;
  }

  const initial = form.find('.cmb2-option[name="font_load_type"]:checked').val();

  const repaint = function (selector, compare) {
    form.find(selector).each(function () {
      if (this.dataset.showFor.indexOf(compare) !== -1) {
        $(this).parents('.cmb-row').show();
      } else {
        $(this).parents('.cmb-row').hide();
      }
    });
  };

  repaint('[data-show-for]', initial);

  form.on('click', function (e) {
    if (!e.target.classList.contains('cmb2-option')) {
      return;
    }

    const selected = e.target.value;
    repaint('[data-show-for]', selected);
  });
});
