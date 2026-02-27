import Byline from './Byline.jsx';
import ShareButtons from './ShareButtons.jsx';

const { useCallback } = React;
const { PanelBody, TextControl, ToggleControl } = wp.components;
const { compose, ifCondition } = wp.compose;
const { useEntityProp } = wp.coreData;
const { useSelect } = wp.data;
const { store: editorStore } = wp.editor;
const { __ } = wp.i18n;

function IndexNumber() {
  const postId = useSelect((select) => select(editorStore).getCurrentPostId(), []);
  const postType = useSelect((select) => select(editorStore).getCurrentPostType(), []);
  const [meta, setMeta] = useEntityProp('postType', postType, 'meta', postId);

  const editIndexNumber = useCallback(
    (indexNumber) => {
      setMeta({ ...meta, amnesty_index_number: indexNumber });
    },
    [meta, setMeta],
  );

  return (
    <TextControl
      label={__('Index Number', 'amnesty')}
      value={meta?.amnesty_index_number ?? ''}
      onChange={editIndexNumber}
    />
  );
}

/**
 * Render an option to toggle the published date's visibility
 *
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component[]}
 */
const PublishedDate = ({ createMetaUpdate, props }) => (
  <>
    <ToggleControl
      label={/* translators: [admin] */ __('Show published date', 'amnesty')}
      help={/* translators: [admin] */ __('Show the post published date', 'amnesty')}
      checked={props.meta.show_published_date}
      onChange={() =>
        createMetaUpdate(
          'show_published_date',
          !props.meta.show_published_date,
          props.meta,
          props.oldMeta,
        )
      }
    />
    <hr />
  </>
);

/**
 * Render an option to toggle the updated date's visibility
 *
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component[]}
 */
const UpdatedDate = ({ createMetaUpdate, props }) => (
  <>
    <ToggleControl
      label={/* translators: [admin] */ __('Show updated date', 'amnesty')}
      help={/* translators: [admin] */ __('Show the "updated at" date', 'amnesty')}
      checked={props.meta.show_updated_date}
      onChange={() =>
        createMetaUpdate(
          'show_updated_date',
          !props.meta.show_updated_date,
          props.meta,
          props.oldMeta,
        )
      }
    />
    <hr />
  </>
);

/**
 * Render the metadata options
 *
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component}
 */
const Metadata = ({ createMetaUpdate, props }) => (
  <PanelBody title={/* translators: [admin] */ __('Metadata', 'amnesty')} initialOpen={false}>
    <IndexNumber />
    <div style={{ height: '24px' }} />
    <PublishedDate createMetaUpdate={createMetaUpdate} props={props} />
    <UpdatedDate createMetaUpdate={createMetaUpdate} props={props} />
    <ShareButtons createMetaUpdate={createMetaUpdate} props={props} />
    <Byline />
  </PanelBody>
);

export default compose([
  ifCondition(() => wp.data.select('core/editor').getEditedPostAttribute('type') === 'post'),
])(Metadata);
