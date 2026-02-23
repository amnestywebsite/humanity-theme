/* eslint-disable no-underscore-dangle */

const { SelectControl, ToggleControl } = wp.components;
const { useEntityProp, useEntityRecords } = wp.coreData;
const { useSelect } = wp.data;
const { store: editorStore } = wp.editor;
const { useCallback } = wp.element;
const { __ } = wp.i18n;

const options = [
  {
    label: __('Choose a sidebar', 'amnesty'),
    value: '',
  },
];

export default function Sidebar() {
  const postId = useSelect((select) => select(editorStore).getCurrentPostId(), []);
  const postType = useSelect((select) => select(editorStore).getCurrentPostType(), []);
  const [meta, setMeta] = useEntityProp('postType', postType, 'meta', postId);
  const { records, isResolving } = useEntityRecords('postType', 'pop-in');

  if (!isResolving && Array.isArray(records)) {
    records.forEach(({ id, title }) => {
      options.push({ label: title.rendered, value: id });
    });
  }

  const editMaximiseContent = useCallback(
    (show) => {
      setMeta({ ...meta, _maximize_post_content: show });
    },
    [meta, setMeta],
  );

  const editDisableSidebar = useCallback(
    (show) => {
      setMeta({ ...meta, _disable_sidebar: show });
    },
    [meta, setMeta],
  );

  const editSidebarId = useCallback(
    (show) => {
      setMeta({ ...meta, sidebar_id: show });
    },
    [meta, setMeta],
  );

  const showSidebarSelection =
    !meta._maximize_post_content && !meta._disable_sidebar && options.length > 1;

  return (
    <>
      <ToggleControl
        label={__('Maximize Content', 'amnesty')}
        help={__(
          'Remove the sidebar and the sidebar area on posts and pages. Generally used to create pages with a full-width page design.',
          'amnesty',
        )}
        checked={meta._maximize_post_content}
        onChange={editMaximiseContent}
      />
      <ToggleControl
        label={__('Disable Sidebar', 'amnesty')}
        help={__(
          'Remove the sidebar, but not the sidebar area; this keeps an empty space to the side of the content. Generally used for text heavy pages.',
          'amnesty',
        )}
        checked={meta._disable_sidebar}
        onChange={editDisableSidebar}
      />
      {showSidebarSelection && (
        <SelectControl
          label={__('Sidebar', 'amnesty')}
          value={meta.sidebar_id}
          options={options}
          onChange={editSidebarId}
        />
      )}
    </>
  );
}
