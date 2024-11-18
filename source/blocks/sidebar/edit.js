const { defaultSidebars } = window.aiSettings;
import { useBlockProps } from '@wordpress/block-editor';
import { useEntityRecord } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { store as editorStore } from '@wordpress/editor';
import { RawHTML } from '@wordpress/element';

const edit = () => {
  const postMeta = useSelect((select) => {
    const meta = select(editorStore).getEditedPostAttribute('meta');
    const type = select(editorStore).getEditedPostAttribute('type');

    /* eslint-disable-next-line no-underscore-dangle */
    const sidebarId = parseInt(meta?._sidebar_id, 10) || (defaultSidebars?.[type]?.[0] ?? 0);

    return {
      /* eslint-disable no-underscore-dangle */
      contentMaximised: meta?._maximize_post_content,
      sidebarDisabled: meta?._disable_sidebar,
      /* eslint-enable no-underscore-dangle */
      sidebarId,
    };
  }, []);

  const currentSidebar = useEntityRecord('postType', 'sidebar', postMeta.sidebarId);
  const sidebarContent = currentSidebar?.isResolving ? '' : currentSidebar?.record?.content?.raw;

  const blockProps = useBlockProps();

  if (postMeta.contentMaximised) {
    return null;
  }

  // an empty sidebar node is required when the sidebar is disabled,
  // but the content is not set to maximised, for legacy reasons.
  if (postMeta.sidebarDisabled || !sidebarContent) {
    return <div {...blockProps}></div>;
  }

  return (
    <div {...blockProps}>
      <RawHTML>{sidebarContent}</RawHTML>
    </div>
  );
};

export default edit;
