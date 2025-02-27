import { IconButton } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const { MediaUpload } = wp.blockEditor;

/**
 * Render the override image component
 *
 * @param {Object} param0 passed props
 *
 * @returns {WP.Element}
 */
const BlockImageSelector = ({ imageId, setAttributes }) => {
  if (imageId) {
    return (
      <IconButton
        icon="no-alt"
        /* translators: [admin] */
        label={__('Remove Image', 'amnesty')}
        onClick={() => setAttributes({ imageID: 0 })}
      />
    );
  }

  return (
    <MediaUpload
      allowedTypes={['image']}
      value={imageId}
      onSelect={({ id }) => setAttributes({ imageID: id })}
      render={({ open }) => <IconButton icon="format-image" onClick={open} />}
    />
  );
};

export default BlockImageSelector;
