const { ToggleControl } = wp.components;
const { __ } = wp.i18n;

export default function RelatedContent({ postMeta: meta, editMeta }) {
  return (
    <ToggleControl
      label={__('Hide Related Content', 'amnesty')}
      help={__('Removes the Related Content section for this entity', 'amnesty')}
      checked={meta?.disable_related_content}
      onChange={(value) => editMeta('disable_related_content')(value)}
    />
  );
}
