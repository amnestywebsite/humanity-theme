const { ToggleControl } = wp.components;
const { useState } = wp.element;
const { __ } = wp.i18n;

/**
 * Render the component for managing an entity's share button visibility
 *
 * Note: the value is inverted because the label is the inverse of the field value
 *
 * @param {object} param0 props passed to the component
 * @param {object} param0.postMeta the entity's meta object
 * @param {function} param0.editMeta callback for manipulating entity meta
 *
 * @return {JSX.Element}
 */
export default function RelatedContent({ postMeta: meta, editMeta }) {
  const [checked, setChecked] = useState(!meta?.disable_related_content);

  const onChange = (value) => {
    setChecked(value);
    editMeta('disable_related_content')(!value);
  };

  return (
    <ToggleControl
      label={__('Show Related Content', 'amnesty')}
      help={__('Shows the Related Content section for this entity', 'amnesty')}
      checked={checked}
      onChange={onChange}
    />
  );
}
