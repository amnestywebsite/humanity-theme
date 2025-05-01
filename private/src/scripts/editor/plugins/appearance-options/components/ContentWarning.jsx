/* eslint-disable camelcase */

const { PanelRow, TextareaControl } = wp.components;
const { compose, ifCondition } = wp.compose;
const { useDispatch, useSelect } = wp.data;
const { useCallback } = wp.element;
const { __ } = wp.i18n;

const ContentWarning = () => {
  const { content_warning: contentWarning } = useSelect(
    (select) => select('core/editor').getEditedPostAttribute('meta'),
    [],
  );

  const { editPost } = useDispatch('core/editor');

  const setContentWarning = useCallback(
    (value) => {
      editPost({ meta: { content_warning: value } });
    },
    [editPost],
  );

  return (
    <PanelRow>
      <TextareaControl
        className="post-contentWarning"
        label={__('Content Warning', 'amnesty')}
        onChange={setContentWarning}
        value={contentWarning}
      />
    </PanelRow>
  );
};

export default compose([
  ifCondition(() => wp.data.select('core/editor').getEditedPostAttribute('type') === 'post'),
])(ContentWarning);
