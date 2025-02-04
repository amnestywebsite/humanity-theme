/* eslint-disable no-underscore-dangle */
import { ToggleControl } from '@wordpress/components';
import { compose, ifCondition } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

/**
 * Render the Share Button options
 *
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component[]}
 */
const ShareButtons = ({ createMetaUpdate, props }) => (
  <>
    <ToggleControl
      // translators: [admin]
      label={__('Disable Sharing', 'amnesty')}
      // translators: [admin]
      help={__('Disable Share Buttons for this post', 'amnesty')}
      checked={props.meta._disable_share_icons}
      onChange={() =>
        createMetaUpdate(
          '_disable_share_icons',
          !props.meta._disable_share_icons,
          props.meta,
          props.oldMeta,
        )
      }
    />
    <hr />
  </>
);

export default compose([
  ifCondition(() => wp.data.select('core/editor').getEditedPostAttribute('type') === 'post'),
])(ShareButtons);
