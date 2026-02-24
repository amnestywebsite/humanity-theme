/* eslint-disable no-underscore-dangle */

const { SelectControl, ToggleControl } = wp.components;
const { useEntityRecords } = wp.coreData;
const { __ } = wp.i18n;

const options = [
  {
    label: __('Choose a sidebar', 'amnesty'),
    value: '',
  },
];

export default function Sidebar({ postMeta: meta, editMeta }) {
  const { records, isResolving } = useEntityRecords('postType', 'pop-in');

  if (!isResolving && Array.isArray(records)) {
    records.forEach(({ id, title }) => {
      options.push({ label: title.rendered, value: id });
    });
  }

  const showSidebarSelection =
    !meta?._maximize_post_content && !meta?._disable_sidebar && options.length > 1;

  return (
    <>
      <ToggleControl
        label={__('Maximize Content', 'amnesty')}
        help={__(
          'Remove the sidebar and the sidebar area on posts and pages. Generally used to create pages with a full-width page design.',
          'amnesty',
        )}
        checked={meta?._maximize_post_content}
        onChange={(value) => editMeta('_maximize_post_content')(value)}
      />
      <ToggleControl
        label={__('Disable Sidebar', 'amnesty')}
        help={__(
          'Remove the sidebar, but not the sidebar area; this keeps an empty space to the side of the content. Generally used for text heavy pages.',
          'amnesty',
        )}
        checked={meta?._disable_sidebar}
        onChange={(value) => editMeta('_disable_sidebar')(value)}
      />
      {showSidebarSelection && (
        <SelectControl
          label={__('Sidebar', 'amnesty')}
          value={meta?.sidebar_id}
          options={options}
          onChange={(value) => editMeta('sidebar_id')(value)}
        />
      )}
    </>
  );
}
