const { ToggleControl } = wp.components;
const { __ } = wp.i18n;

export default function UpdatedDate({ postMeta: meta, editMeta }) {
  return (
    <ToggleControl
      label={__('Show updated date', 'amnesty')}
      help={__('Show the post "updated at" date', 'amnesty')}
      checked={meta?.show_updated_date}
      onChange={(value) => editMeta('show_updated_date')(value)}
    />
  );
}
