const { TextControl } = wp.components;
const { PluginPostStatusInfo } = wp.editPost;
const { __ } = wp.i18n;

/**
 * Render the Document Reference (amnesty.eu)
 *
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component|null}
 */
const DocumentReference = ({ props }) => {
  if (props.type !== 'post') {
    return null;
  }

  return (
    <PluginPostStatusInfo>
      <TextControl
        className="amnesty-docref disabled"
        // translators: [admin]
        label={__('Document Reference:', 'amnesty')}
        value={props.meta.document_ref}
        onChange={() => {}} // no-op
      />
    </PluginPostStatusInfo>
  );
};

export default DocumentReference;
