const { ToggleControl } = wp.components;
const { __ } = wp.i18n;

/**
 * Render the component for managing an entity's share button visibility
 *
 * @param {object} param0 props passed to the component
 * @param {object} param0.postMeta the entity's meta object
 * @param {function} param0.editMeta callback for manipulating entity meta
 *
 * @return {JSX.Element}
 */
export default function PublishedDate({ postMeta: meta, editMeta }) {
  return (
    <ToggleControl
      label={__('Show published date', 'amnesty')}
      help={__('Show the date the entity was published', 'amnesty')}
      checked={meta?.show_published_date}
      onChange={(value) => editMeta('show_published_date')(value)}
    />
  );
}
