/* eslint-disable no-underscore-dangle */
import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import PostSelect from '../../../blocks/post-list/components/post-selector/PostSelector.jsx';

/**
 * Render the Sidebar options
 *
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component|null}
 */
const Sidebar = ({ createMetaUpdate, props }) => {
  if (!['page', 'post'].includes(props.type)) {
    return null;
  }

  return (
    <PanelBody title={/* translators: [admin] */ __('Sidebar', 'amnesty')} initialOpen={false}>
      <ToggleControl
        // translators: [admin]
        label={__('Maximize Content', 'amnesty')}
        help={
          // translators: [admin]
          __(
            'Remove the sidebar and the sidebar area on posts and pages. Generally used to create pages with a full-width page design.',
            'amnesty',
          )
        }
        checked={props.meta._maximize_post_content}
        onChange={() =>
          createMetaUpdate(
            '_maximize_post_content',
            !props.meta._maximize_post_content,
            props.meta,
            props.oldMeta,
          )
        }
      />
      <ToggleControl
        // translators: [admin]
        label={__('Disable Sidebar', 'amnesty')}
        help={
          // translators: [admin]
          __(
            'Remove the sidebar, but not the sidebar area; this keeps an empty space to the side of the content. Generally used for text heavy pages.',
            'amnesty',
          )
        }
        checked={props.meta._disable_sidebar}
        onChange={() =>
          createMetaUpdate(
            '_disable_sidebar',
            !props.meta._disable_sidebar,
            props.meta,
            props.oldMeta,
          )
        }
      />
      <PostSelect
        onChange={(sidebarId) =>
          createMetaUpdate('_sidebar_id', sidebarId, props.meta, props.oldMeta)
        }
        value={props.meta._sidebar_id}
      />
    </PanelBody>
  );
};

export default Sidebar;
