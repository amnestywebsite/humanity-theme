const { MediaUpload, MediaUploadCheck } = wp.blockEditor;
const { Button } = wp.components;
const { __ } = wp.i18n;

const render = (imageId, onRemove) => ({ open }) => {
  if (!imageId) {
    return (
      <Button isPrimary onClick={open}>
        {/* translators: [admin] */ __('Select background image', 'amnesty')}
      </Button>
    );
  }

  return (
    <>
      <Button isPrimary onClick={open}>
        {/* translators: [admin] */ __('Edit image', 'amnesty')}
      </Button>
      <Button
        isDestructive
        isDefault
        onClick={onRemove}
        style={{ marginLeft: '6px' }}
      >
        {/* translators: [admin] */ __('Remove image', 'amnesty')}
      </Button>
    </>
  );
};

const BackgroundImageSelectorControl = ({ imageId, onRemove, onSelect }) => {
  return (
    <div className="components-base-control">
      <MediaUploadCheck>
        <MediaUpload
          onSelect={onSelect}
          allowedTypes={['image']}
          value={imageId}
          render={render(imageId, onRemove)}
        />
      </MediaUploadCheck>
    </div>
  );
};

export default BackgroundImageSelectorControl;
