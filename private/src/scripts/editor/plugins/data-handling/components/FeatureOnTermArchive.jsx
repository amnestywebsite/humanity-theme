const { SelectControl } = wp.components;
const { useEntityRecords } = wp.coreData;
const { useEffect } = wp.element;
const { __ } = wp.i18n;

const options = {
  '': {
    label: __('Select Term', 'amnesty'),
    value: '',
  },
};

/**
 * Render the component for managing an entity's term archive slider visibility
 *
 * @param {object} param0 props passed to the component
 * @param {object} param0.postMeta the entity's meta object
 * @param {function} param0.editMeta callback for manipulating entity meta
 *
 * @return {JSX.Element}
 */
export default function FeatureOnTermArchive({ postMeta: meta, editMeta }) {
  const { records, isResolving } = useEntityRecords('taxonomy', 'category');

  useEffect(() => {
    if (!isResolving && Array.isArray(records)) {
      records.forEach(({ name, slug }) => {
        if (!options[slug]) {
          options[slug] = { label: name, value: slug };
        }
      });
    }
  }, [records, isResolving]);

  return (
    <SelectControl
      __next40pxDefaultSize
      label={__('Feature on content type:', 'amnesty')}
      value={meta?.term_slider}
      options={Object.values(options)}
      onChange={(value) => editMeta('term_slider')(value)}
    />
  );
}
