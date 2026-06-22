/* eslint-disable no-underscore-dangle */

const { ToggleControl } = wp.components;
const { useState } = wp.element;
const { __ } = wp.i18n;

/**
 * Render the component for managing an entity's featured image visibility
 *
 * Note: the value is inverted because the label is the inverse of the field value
 *
 * @param {object} param0 props passed to the component
 * @param {object} param0.postMeta the entity's meta object
 * @param {function} param0.editMeta callback for manipulating entity meta
 *
 * @return {JSX.Element}
 */
export default function FeaturedImage({ postMeta: meta, editMeta }) {
  const [imageChecked, setImageChecked] = useState(!meta?._hide_featured_image);
  const [imageCaptionChecked, setImageCaptionChecked] = useState(
    !meta?._hide_featured_image_caption,
  );

  const onImageChange = (value) => {
    setImageChecked(value);
    editMeta('_hide_featured_image')(!value);
  };

  const onImageCaptionChange = (value) => {
    setImageCaptionChecked(value);
    editMeta('_hide_featured_image_caption')(!value);
  };

  return (
    <>
      <ToggleControl
        label={__('Show featured image', 'amnesty')}
        help={__('Show the featured image.', 'amnesty')}
        checked={imageChecked}
        onChange={onImageChange}
      />
      {imageChecked && (
        <ToggleControl
          label={__('Show featured image caption', 'amnesty')}
          help={__('Show the image caption for the featured image', 'amnesty')}
          checked={imageCaptionChecked}
          onChange={onImageCaptionChange}
        />
      )}
    </>
  );
}
