const { addFilter } = wp.hooks;

addFilter('amnesty.cmb2.change.type', 'amnesty/cmb2', () => (event, $form) => {
  const type = event.target.value;

  const $groups = Array.from($form.querySelectorAll('.cmb-type-group[data-show-on="type"]'));
  if ($groups.length) {
    $groups.forEach(($group) => {
      $group.classList.add('closed');
      $group.classList.add('is-hidden');

      const { showIf, showNot } = $group.dataset;
      if (!showIf && !showNot) {
        return;
      }

      if (showIf === type || (showNot && showNot !== type)) {
        $group.classList.remove('is-hidden');
        $group.classList.remove('closed');
      }
    });
  }

  const $fields = Array.from($form.querySelectorAll('.cmb-row[data-show-on="type"]'));
  if ($fields.length) {
    $fields.forEach(($field) => {
      $field.classList.add('is-hidden');

      const { showIf, showNot } = $field.dataset;
      if (!showIf && !showNot) {
        return;
      }

      if (showIf === type || (showNot && showNot !== type)) {
        $field.classList.remove('is-hidden');
      }
    });
  }
});
