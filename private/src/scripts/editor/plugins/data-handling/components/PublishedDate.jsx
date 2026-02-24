const { ToggleControl } = wp.components;
const { __ } = wp.i18n;

export default function PublishedDate({ postMeta: meta, editMeta }) {
  return (
    <ToggleControl
      label={__('Show published date', 'amnesty')}
      help={__('Show the post published date', 'amnesty')}
      checked={meta?.show_published_date}
      onChange={(value) => editMeta('show_published_date')(value)}
    />
  );
}
