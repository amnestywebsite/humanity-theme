/* eslint-disable no-underscore-dangle */
import { ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Render the featured image options
 *
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component}
 */
const FeaturedImage = ({ createMetaUpdate, props }) => (
  <>
    <ToggleControl
      label={__('Hide featured image', 'amnesty')}
      help={__('Hide the featured image.', 'amnesty')}
      checked={props.meta._hide_featured_image}
      onChange={() =>
        createMetaUpdate(
          '_hide_featured_image',
          !props.meta._hide_featured_image,
          props.meta,
          props.oldMeta,
        )
      }
    />
    <ToggleControl
      label={__('Hide featured image caption', 'amnesty')}
      help={__('Hide the image caption for the featured image', 'amnesty')}
      checked={props.meta._hide_featured_image_caption}
      onChange={() =>
        createMetaUpdate(
          '_hide_featured_image_caption',
          !props.meta._hide_featured_image_caption,
          props.meta,
          props.oldMeta,
        )
      }
    />
  </>
);

export default FeaturedImage;
