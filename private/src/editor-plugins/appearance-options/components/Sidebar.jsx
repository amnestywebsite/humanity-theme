/* eslint-disable no-underscore-dangle */
import { PanelBody, SelectControl, ToggleControl } from '@wordpress/components';
import { useEntityRecords } from '@wordpress/core-data';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Render the Sidebar options
 *
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component|null}
 */
const Sidebar = ({ createMetaUpdate, props }) => {
  const { records, isResolving } = useEntityRecords('postType', 'sidebar');
  const [options, setOptions] = useState([]);

  useEffect(() => {
    const optsArray = [
      {
        label: __('Use global default sidebar', 'amnesty'),
        value: null,
      },
    ];

    if (!isResolving && Array.isArray(records)) {
      records.forEach((record) => {
        optsArray.push({
          label: record.title.raw,
          value: record.id,
        });
      });
    }

    setOptions(optsArray);
  }, [records, isResolving]);

  if (!['page', 'post'].includes(props.type)) {
    return null;
  }

  return (
    <PanelBody title={/* translators: [admin] */ __('Sidebar', 'amnesty')} initialOpen={false}>
      <ToggleControl
        // translators: [admin]
        label={__('Maximize Content', 'amnesty')}
        help={
          // translators: [admin]
          __(
            'Remove the sidebar and the sidebar area on posts and pages. Generally used to create pages with a full-width page design.',
            'amnesty',
          )
        }
        checked={props.meta._maximize_post_content}
        onChange={() =>
          createMetaUpdate(
            '_maximize_post_content',
            !props.meta._maximize_post_content,
            props.meta,
            props.oldMeta,
          )
        }
      />
      <ToggleControl
        // translators: [admin]
        label={__('Disable Sidebar', 'amnesty')}
        help={
          // translators: [admin]
          __(
            'Remove the sidebar, but not the sidebar area; this keeps an empty space to the side of the content. Generally used for text heavy pages.',
            'amnesty',
          )
        }
        checked={props.meta._disable_sidebar}
        onChange={() =>
          createMetaUpdate(
            '_disable_sidebar',
            !props.meta._disable_sidebar,
            props.meta,
            props.oldMeta,
          )
        }
      />
      <SelectControl
        onChange={(sidebarId) =>
          createMetaUpdate('_sidebar_id', sidebarId, props.meta, props.oldMeta)
        }
        options={options}
      />
    </PanelBody>
  );
};

export default Sidebar;
