const { ToggleControl } = wp.components;
const { useEntityProp } = wp.coreData;
const { useSelect } = wp.data;
const { store: editorStore } = wp.editor;
const { useCallback } = wp.element;
const { __ } = wp.i18n;

export default function PublishedDate() {
  const postId = useSelect((select) => select(editorStore).getCurrentPostId(), []);
  const postType = useSelect((select) => select(editorStore).getCurrentPostType(), []);
  const [meta, setMeta] = useEntityProp('postType', postType, 'meta', postId);

  const editShowPublishedDate = useCallback(
    (show) => {
      setMeta({ ...meta, show_published_date: show });
    },
    [meta, setMeta],
  );

  return (
    <ToggleControl
      label={__('Show published date', 'amnesty')}
      help={__('Show the post published date', 'amnesty')}
      checked={meta.show_published_date}
      onChange={editShowPublishedDate}
    />
  );
}
