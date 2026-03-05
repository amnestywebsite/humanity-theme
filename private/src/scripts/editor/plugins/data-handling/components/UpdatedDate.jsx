const { ToggleControl } = wp.components;
const { __ } = wp.i18n;

/**
 * Render the component for managing an entity's updated date visibility
 *
 * @param {object} param0 props passed to the component
 * @param {object} param0.postMeta the entity's meta object
 * @param {function} param0.editMeta callback for manipulating entity meta
 *
 * @return {JSX.Element}
 */
export default function UpdatedDate({ postMeta: meta, editMeta }) {
  return (
    <ToggleControl
      label={__('Show updated date', 'amnesty')}
      help={__('Show the date the entity was updated', 'amnesty')}
      checked={meta?.show_updated_date}
      onChange={(value) => editMeta('show_updated_date')(value)}
    />
  );
}
