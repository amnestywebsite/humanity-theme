/* eslint-disable no-underscore-dangle */

const { ToggleControl } = wp.components;
const { useEntityProp } = wp.coreData;
const { useSelect } = wp.data;
const { store: editorStore } = wp.editor;
const { useCallback } = wp.element;
const { __ } = wp.i18n;

export default function Byline() {
  const postId = useSelect((select) => select(editorStore).getCurrentPostId(), []);
  const postType = useSelect((select) => select(editorStore).getCurrentPostType(), []);
  const [meta, setMeta] = useEntityProp('postType', postType, 'meta', postId);

  const editHideFeaturedImage = useCallback(
    (isEnabled) => {
      setMeta({ ...meta, _hide_featured_image: isEnabled });
    },
    [meta, setMeta],
  );

  const editHideFeatureCaption = useCallback(
    (isAuthor) => {
      setMeta({ ...meta, _hide_featured_image_caption: isAuthor });
    },
    [meta, setMeta],
  );

  return (
    <>
      <ToggleControl
        label={__('Hide featured image', 'amnesty')}
        help={__('Hide the featured image.', 'amnesty')}
        value={meta._hide_featured_image}
        onChange={editHideFeaturedImage}
      />
      {!meta._hide_featured_image && (
        <ToggleControl
          label={__('Hide featured image caption', 'amnesty')}
          help={__('Hide the image caption for the featured image', 'amnesty')}
          checked={meta._hide_featured_image_caption}
          onChange={editHideFeatureCaption}
        />
      )}
    </>
  );
}
