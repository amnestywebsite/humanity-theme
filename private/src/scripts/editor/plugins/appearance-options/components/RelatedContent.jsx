const { ToggleControl } = wp.components;
const { __ } = wp.i18n;

/**
 * Renders options specific to related content
 *
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component}
 */
const RelatedContent = ({ createMetaUpdate, props }) => (
  <ToggleControl
    // translators: [admin]
    label={__('Disable Related Content', 'amnesty')}
    // translators: [admin]
    help={__('Removes the Related Content section for this post', 'amnesty')}
    checked={props.meta.disable_related_content}
    onChange={() =>
      createMetaUpdate(
        'disable_related_content',
        !props.meta.disable_related_content,
        props.meta,
        props.oldMeta,
      )
    }
  />
);

export default RelatedContent;
