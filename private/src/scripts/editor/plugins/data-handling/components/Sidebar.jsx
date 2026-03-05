/* eslint-disable no-underscore-dangle */

import Spacer from './Spacer.jsx';

const { SelectControl, ToggleControl } = wp.components;
const { useEntityRecords } = wp.coreData;
const { useState } = wp.element;
const { __ } = wp.i18n;

const options = [
  {
    label: __('Choose a sidebar', 'amnesty'),
    value: '',
  },
];

/**
 * Render the component for managing an entity's sidebar visibility
 *
 * Note: the values are inverted because the labels are the inverse of the field values
 *
 * @param {object} param0 props passed to the component
 * @param {object} param0.postMeta the entity's meta object
 * @param {function} param0.editMeta callback for manipulating entity meta
 *
 * @return {JSX.Element}
 */
export default function Sidebar({ postMeta: meta, editMeta }) {
  const { records, isResolving } = useEntityRecords('postType', 'sidebar');

  if (!isResolving && Array.isArray(records)) {
    records.forEach(({ id, title }) => {
      options.push({ label: title.rendered, value: id });
    });
  }

  const [sidebarContentChecked, setSidebarContentChecked] = useState(!meta?._disable_sidebar);
  const [sidebarAreaChecked, setSidebarAreaChecked] = useState(!meta?._maximize_post_content);

  const onSidebarContentChange = (value) => {
    setSidebarContentChecked(value);
    editMeta('_disable_sidebar')(!value);
  };

  const onSidebarAreaChange = (value) => {
    setSidebarAreaChecked(value);
    editMeta('_maximize_post_content')(!value);
  };

  const showSidebarSelection = !sidebarContentChecked && !sidebarAreaChecked && options.length > 1;

  return (
    <>
      <ToggleControl
        label={__('Show sidebar content', 'amnesty')}
        help={__(
          'Shows the content within the sidebar area. If this option is off, the sidebar area will remain, but no content will be shown within it. Generally used for text heavy pages.',
          'amnesty',
        )}
        checked={sidebarContentChecked}
        onChange={onSidebarContentChange}
      />
      <ToggleControl
        label={__('Show sidebar area', 'amnesty')}
        help={__(
          'Shows the sidebar area. If this option is off, the sidebar area will be completely hidden. Generally used to create pages with a full-width page design.',
          'amnesty',
        )}
        checked={sidebarAreaChecked}
        onChange={onSidebarAreaChange}
      />
      {showSidebarSelection && (
        <>
          <SelectControl
            __next40pxDefaultSize
            label={__('Active sidebar', 'amnesty')}
            value={meta?.sidebar_id}
            options={options}
            onChange={(value) => editMeta('sidebar_id')(value)}
          />
          <Spacer />
        </>
      )}
    </>
  );
}
