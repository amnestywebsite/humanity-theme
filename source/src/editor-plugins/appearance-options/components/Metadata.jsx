import { PanelBody, ToggleControl } from '@wordpress/components';
import { compose, ifCondition } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

import Byline from './Byline.jsx';
import ShareButtons from './ShareButtons.jsx';

/**
 * Render an option to toggle the published date's visibility
 *
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component[]}
 */
const PublishedDate = ({ createMetaUpdate, props }) => (
  <>
    <ToggleControl
      label={/* translators: [admin] */ __('Show published date', 'amnesty')}
      help={/* translators: [admin] */ __('Show the post published date', 'amnesty')}
      checked={props.meta.show_published_date}
      onChange={() =>
        createMetaUpdate(
          'show_published_date',
          !props.meta.show_published_date,
          props.meta,
          props.oldMeta,
        )
      }
    />
    <hr />
  </>
);

/**
 * Render an option to toggle the updated date's visibility
 *
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component[]}
 */
const UpdatedDate = ({ createMetaUpdate, props }) => (
  <>
    <ToggleControl
      label={/* translators: [admin] */ __('Show updated date', 'amnesty')}
      help={/* translators: [admin] */ __('Show the "updated at" date', 'amnesty')}
      checked={props.meta.show_updated_date}
      onChange={() =>
        createMetaUpdate(
          'show_updated_date',
          !props.meta.show_updated_date,
          props.meta,
          props.oldMeta,
        )
      }
    />
    <hr />
  </>
);

/**
 * Render the metadata options
 *
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component}
 */
const Metadata = ({ createMetaUpdate, props }) => (
  <PanelBody title={/* translators: [admin] */ __('Metadata', 'amnesty')} initialOpen={false}>
    <PublishedDate createMetaUpdate={createMetaUpdate} props={props} />
    <UpdatedDate createMetaUpdate={createMetaUpdate} props={props} />
    <ShareButtons createMetaUpdate={createMetaUpdate} props={props} />
    <Byline />
  </PanelBody>
);

export default compose([
  ifCondition(() => wp.data.select('core/editor').getEditedPostAttribute('type') === 'post'),
])(Metadata);
