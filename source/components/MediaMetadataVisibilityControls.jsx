const { ToggleControl } = wp.components;
const { __ } = wp.i18n;

const MediaMetadataVisibilityControls = ({ type = 'image', hideCaption, hideCopyright, setAttributes }) => {
  // translators: [admin]
  let captionLabel = __('Hide Image Caption', 'amnesty');
  // translators: [admin]
  let copyrightLabel = __('Hide Image Credit', 'amnesty');

  if (type === 'video') {
    // translators: [admin]
    captionLabel = __('Hide Video Caption', 'amnesty');
    // translators: [admin]
    copyrightLabel = __('Hide Video Credit', 'amnesty');
  }

  return (
    <>
      <ToggleControl
        label={captionLabel}
        checked={hideCaption}
        onChange={() => setAttributes({ hideImageCaption: !hideCaption })}
      />
      <ToggleControl
        label={copyrightLabel}
        checked={hideCopyright}
        onChange={() => setAttributes({ hideImageCopyright: !hideCopyright })}
      />
    </>
  );
};

export default MediaMetadataVisibilityControls;
