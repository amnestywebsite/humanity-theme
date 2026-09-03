const { MediaUpload } = wp.blockEditor;
const { Button } = wp.components;
const { __ } = wp.i18n;

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
      <Button
        icon="no-alt"
        // translators: [admin]
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
      render={({ open }) => <Button icon="format-image" onClick={open} />}
    />
  );
};

export default BlockImageSelector;
