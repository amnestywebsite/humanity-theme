const { SelectControl } = wp.components;
const { useEntityRecords } = wp.coreData;
const { __ } = wp.i18n;

const options = [
  {
    label: __('Select Term', 'amnesty'),
    value: '',
  },
];

export default function FeatureOnTermArchive({ postMeta: meta, editMeta }) {
  const { records, isResolving } = useEntityRecords('taxonomy', 'category');

  if (!isResolving && Array.isArray(records)) {
    records.forEach(({ name, slug }) => {
      options.push({ label: name, value: slug });
    });
  }

  return (
    <SelectControl
      label={__('Feature on content type:', 'amnesty')}
      value={meta?.term_slider}
      options={options}
      onChange={(value) => editMeta('term_slider')(value)}
    />
  );
}
