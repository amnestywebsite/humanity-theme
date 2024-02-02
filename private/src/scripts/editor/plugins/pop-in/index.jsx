import './block-restrictions';

import { validateBool } from '../../blocks/utils';

const { ToggleControl } = wp.components;
const { compose, ifCondition } = wp.compose;
const { select, useDispatch, useSelect } = wp.data;
const { Fragment, useCallback } = wp.element;
const { PluginDocumentSettingPanel } = wp.editPost;
const { __ } = wp.i18n;
const { registerPlugin } = wp.plugins;

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

const PopInSettings = (props) => {
  const { renderTitle, onRenderTitleToggle } = useRenderTitle(props);

  return (
    <Fragment>
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
    </Fragment>
  );
};

registerPlugin('amnesty-core-pop-in', {
  render: compose([
    ifCondition(() => select('core/editor').getEditedPostAttribute('type') === 'pop-in'),
  ])(PopInSettings),
});
