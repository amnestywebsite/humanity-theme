const { SelectControl } = wp.components;
const { useEntityProp, useEntityRecords } = wp.coreData;
const { useSelect } = wp.data;
const { store: editorStore } = wp.editor;
const { useCallback } = wp.element;
const { __ } = wp.i18n;

const options = [
  {
    label: __('Select Term', 'amnesty'),
    value: '',
  },
];

export default function FeatureOnTermArchive() {
  const postId = useSelect((select) => select(editorStore).getCurrentPostId(), []);
  const postType = useSelect((select) => select(editorStore).getCurrentPostType(), []);
  const [meta, setMeta] = useEntityProp('postType', postType, 'meta', postId);
  const { records, isResolving } = useEntityRecords('taxonomy', 'category');

  if (!isResolving && Array.isArray(records)) {
    records.forEach(({ name, slug }) => {
      options.push({ label: name, value: slug });
    });
  }

  const editFeatureOnTermArchive = useCallback(
    (isAuthor) => {
      setMeta({ ...meta, term_slider: isAuthor });
    },
    [meta, setMeta],
  );

  return (
    <SelectControl
      label={__('Feature on content type:', 'amnesty')}
      value={meta.term_slider}
      options={options}
      onChange={editFeatureOnTermArchive}
    />
  );
}
