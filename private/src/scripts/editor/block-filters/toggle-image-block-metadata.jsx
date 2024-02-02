/* eslint-disable react/display-name */
const { assign } = lodash;
const { createHigherOrderComponent } = wp.compose;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, ToggleControl } = wp.components;
const { useEffect, useRef, useState } = wp.element;
const { addFilter } = wp.hooks;
const { __ } = wp.i18n;

// add attribute for toggling visibility of image caption & credit
addFilter('blocks.registerBlockType', 'amnesty-core', (settings, name) => {
  if (name !== 'core/image') {
    return settings;
  }

  return assign({}, settings, {
    attributes: {
      ...settings.attributes,
      hideImageCaption: {
        type: 'boolean',
        default: true,
      },
      hideImageCopyright: {
        type: 'boolean',
        default: false,
      },
    },
  });
});

const fetchImageData = (id, setState) => {
  wp.apiRequest({
    path: `/wp/v2/media/${id}?_fields=id,description,caption&context=edit`,
  }).then((resp) => {
    setState({
      id: resp.id,
      caption: resp.caption.raw,
      description: resp.description.raw,
    });
  });
};

const ImageBlockWrapper = createHigherOrderComponent((DisplayComponent) => (props) => {
  if (props.name !== 'core/image') {
    return <DisplayComponent {...props} />;
  }

  const { attributes, setAttributes } = props;
  const [imageData, setImageData] = useState(null);
  const blockRef = useRef(null);

  const shouldShowImageCaption =
    imageData?.caption &&
    !attributes.hideImageCaption &&
    imageData?.caption !== imageData?.description;
  const shouldShowImageCredit = imageData?.description && !attributes.hideImageCopyright;
  const shouldShowMetadata = shouldShowImageCaption || shouldShowImageCredit;

  useEffect(() => {
    if (!imageData || imageData?.id !== props.attributes.id) {
      fetchImageData(props.attributes.id, setImageData);
    }
  }, [props.attributes.id, imageData]);

  const metadataStyles = {};

  if (props.attributes.caption && shouldShowMetadata) {
    const caption = blockRef.current.querySelector('.wp-element-caption');
    const captionHeight = caption?.getBoundingClientRect()?.height;
    const captionMargins =
      parseFloat(getComputedStyle(caption).marginTop.replace(/[^\d.]/g, '')) +
      parseFloat(getComputedStyle(caption).marginBottom.replace(/[^\d.]/g, ''));
    const captionTotalHeight = captionHeight + captionMargins;

    metadataStyles.bottom = `${captionTotalHeight}px`;
  }

  return (
    <>
      <div ref={blockRef} className="block-editor-block-list__block wp-block wp-block-image">
        <DisplayComponent {...props} />
        {shouldShowMetadata && (
          <div className="image-metadata" style={metadataStyles}>
            {shouldShowImageCaption && (
              <span className="image-metadataItem image-caption">{imageData.caption}</span>
            )}
            {shouldShowImageCredit && (
              <span className="image-metadataItem image-copyright">{imageData.description}</span>
            )}
          </div>
        )}
      </div>
      <InspectorControls>
        <PanelBody>
          <ToggleControl
            // translators: [admin]
            label={__('Hide Image Caption', 'amnesty')}
            checked={attributes.hideImageCaption}
            onChange={(hideImageCaption) => setAttributes({ hideImageCaption })}
          />
          <ToggleControl
            // translators: [admin]
            label={__('Hide Image Credit', 'amnesty')}
            checked={attributes.hideImageCopyright}
            onChange={(hideImageCopyright) => setAttributes({ hideImageCopyright })}
          />
        </PanelBody>
      </InspectorControls>
    </>
  );
});

addFilter('editor.BlockEdit', 'amnesty-core', ImageBlockWrapper);
