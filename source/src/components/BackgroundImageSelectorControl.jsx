import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

function makeRenderer(imageId, onRemove) {
  const render = ({ open }) => {
    if (!imageId) {
      return (
        <Button variant="primary" onClick={open}>
          {/* translators: [admin] */ __('Select background image', 'amnesty')}
        </Button>
      );
    }

    return (
      <>
        <Button variant="primary" onClick={open}>
          {/* translators: [admin] */ __('Edit image', 'amnesty')}
        </Button>
        <Button isDestructive variant="secondary" onClick={onRemove} style={{ marginLeft: '6px' }}>
          {/* translators: [admin] */ __('Remove image', 'amnesty')}
        </Button>
      </>
    );
  };

  return render;
}

export default function BackgroundImageSelectorControl({ imageId, onRemove, onSelect }) {
  return (
    <div className="components-base-control">
      <MediaUploadCheck>
        <MediaUpload
          onSelect={onSelect}
          allowedTypes={['image']}
          value={imageId}
          render={makeRenderer(imageId, onRemove)}
        />
      </MediaUploadCheck>
    </div>
  );
}
