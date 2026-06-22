import './block-restrictions';

import { validateBool } from '../../blocks/utils';

const { ToggleControl } = wp.components;
const { useDispatch, useSelect } = wp.data;
const { store: editorStore } = wp.editor;
const { PluginDocumentSettingPanel } = wp.editPost;
const { useCallback } = wp.element;
const { __ } = wp.i18n;

const useRenderTitle = (props) => {
  const { renderTitle } = useSelect(
    (store) => {
      const meta = store('core/editor').getEditedPostAttribute('meta');
      return {
        renderTitle: validateBool(meta?.renderTitle) ? 'yes' : 'no',
      };
    },
    [props],
  );

  const { editPost } = useDispatch('core/editor');
  const onRenderTitleToggle = useCallback(
    (value) => {
      editPost({
        meta: {
          renderTitle: validateBool(value) ? 'yes' : 'no',
        },
      });
    },
    [editPost],
  );

  return { renderTitle, onRenderTitleToggle };
};

export default function PopInSettings(props) {
  const postType = useSelect((select) => select(editorStore).getCurrentPostType(), []);
  const { renderTitle, onRenderTitleToggle } = useRenderTitle(props);

  if (postType !== 'pop-in') {
    return null;
  }

  return (
    <>
      <PluginDocumentSettingPanel
        name="pop-in"
        title={/* translators: [admin] */ __('Pop-in Settings', 'amnesty')}
      >
        <ToggleControl
          // translators: [admin]
          label={__('Render Title', 'amnesty')}
          // translators: [admin]
          help={__('Should the title be rendered when showing this pop-in to the user?', 'amnesty')}
          checked={validateBool(renderTitle)}
          onChange={(value) => onRenderTitleToggle(value)}
        />
      </PluginDocumentSettingPanel>
    </>
  );
}
