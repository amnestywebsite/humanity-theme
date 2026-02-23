const { ToggleControl } = wp.components;
const { useEntityProp } = wp.coreData;
const { useSelect } = wp.data;
const { store: editorStore } = wp.editor;
const { useCallback } = wp.element;
const { __ } = wp.i18n;

export default function RelatedContent() {
  const postId = useSelect((select) => select(editorStore).getCurrentPostId(), []);
  const postType = useSelect((select) => select(editorStore).getCurrentPostType(), []);
  const [meta, setMeta] = useEntityProp('postType', postType, 'meta', postId);

  const editDisableRelatedContent = useCallback(
    (show) => {
      setMeta({ ...meta, disable_related_content: show });
    },
    [meta, setMeta],
  );

  return (
    <ToggleControl
      label={__('Disable Related Content', 'amnesty')}
      help={__('Removes the Related Content section for this post', 'amnesty')}
      checked={meta.disable_related_content}
      onChange={editDisableRelatedContent}
    />
  );
}
