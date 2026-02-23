const { ToggleControl } = wp.components;
const { useEntityProp } = wp.coreData;
const { useSelect } = wp.data;
const { store: editorStore } = wp.editor;
const { useCallback } = wp.element;
const { __ } = wp.i18n;

export default function UpdatedDate() {
  const postId = useSelect((select) => select(editorStore).getCurrentPostId(), []);
  const postType = useSelect((select) => select(editorStore).getCurrentPostType(), []);
  const [meta, setMeta] = useEntityProp('postType', postType, 'meta', postId);

  const editShowUpdatedDate = useCallback(
    (show) => {
      setMeta({ ...meta, show_updated_date: show });
    },
    [meta, setMeta],
  );

  return (
    <ToggleControl
      label={__('Show updated date', 'amnesty')}
      help={__('Show the post "updated at" date', 'amnesty')}
      checked={meta.show_updated_date}
      onChange={editShowUpdatedDate}
    />
  );
}
