/* eslint-disable no-underscore-dangle */

const { ToggleControl } = wp.components;
const { __ } = wp.i18n;

export default function FeaturedImage({ postMeta: meta, editMeta }) {
  return (
    <>
      <ToggleControl
        label={__('Hide featured image', 'amnesty')}
        help={__('Hide the featured image.', 'amnesty')}
        value={meta?._hide_featured_image}
        onChange={(value) => editMeta('_hide_featured_image')(value)}
      />
      {!meta?._hide_featured_image && (
        <ToggleControl
          label={__('Hide featured image caption', 'amnesty')}
          help={__('Hide the image caption for the featured image', 'amnesty')}
          checked={meta?._hide_featured_image_caption}
          onChange={(value) => editMeta('_hide_featured_image_caption')(value)}
        />
      )}
    </>
  );
}
