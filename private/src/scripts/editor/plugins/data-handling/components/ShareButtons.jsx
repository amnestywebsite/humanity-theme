/* eslint-disable no-underscore-dangle */

const { ToggleControl } = wp.components;
const { useEntityProp } = wp.coreData;
const { useSelect } = wp.data;
const { store: editorStore } = wp.editor;
const { useCallback } = wp.element;
const { __ } = wp.i18n;

export default function ShareButtons() {
  const postId = useSelect((select) => select(editorStore).getCurrentPostId(), []);
  const postType = useSelect((select) => select(editorStore).getCurrentPostType(), []);
  const [meta, setMeta] = useEntityProp('postType', postType, 'meta', postId);

  const editDisableShareButtons = useCallback(
    (disable) => {
      setMeta({ ...meta, _disable_share_icons: disable });
    },
    [meta, setMeta],
  );

  return (
    <ToggleControl
      label={__('Disable Sharing', 'amnesty')}
      help={__('Disable Share Buttons for this item', 'amnesty')}
      checked={meta._disable_share_icons}
      onChange={editDisableShareButtons}
    />
  );
}
