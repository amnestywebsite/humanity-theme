/* eslint-disable no-underscore-dangle */

const { TextControl, ToggleControl } = wp.components;
const { useEntityProp } = wp.coreData;
const { useSelect } = wp.data;
const { store: editorStore } = wp.editor;
const { useCallback } = wp.element;
const { __ } = wp.i18n;

export default function Byline() {
  const postId = useSelect((select) => select(editorStore).getCurrentPostId(), []);
  const postType = useSelect((select) => select(editorStore).getCurrentPostType(), []);
  const [meta, setMeta] = useEntityProp('postType', postType, 'meta', postId);

  const editIsEnabled = useCallback(
    (isEnabled) => {
      setMeta({ ...meta, _display_author_info: isEnabled });
    },
    [meta, setMeta],
  );

  const editIsAuthor = useCallback(
    (isAuthor) => {
      setMeta({ ...meta, byline_is_author: isAuthor });
    },
    [meta, setMeta],
  );

  const editBylineEntity = useCallback(
    (entity) => {
      setMeta({ ...meta, byline_entity: entity });
    },
    [meta, setMeta],
  );

  const editBylineContext = useCallback(
    (context) => {
      setMeta({ ...meta, byline_context: context });
    },
    [meta, setMeta],
  );

  return (
    <>
      <ToggleControl
        label={__('Enable public byline', 'amnesty')}
        help={__(
          'Content will be unattributed, and the author information will remain private, unless the byline is enabled and populated',
          'amnesty',
        )}
        value={meta._display_author_info}
        onChange={editIsEnabled}
      />
      {meta._display_author_info && (
        <ToggleControl
          label={__('Use author profile for byline', 'amnesty')}
          checked={meta.byline_is_author}
          onChange={editIsAuthor}
        />
      )}
      {meta._display_author_info && !meta.byline_is_author && (
        <>
          <TextControl
            label={__('Byline Name', 'amnesty')}
            help={__('The individual, group, or entity to be named as the author', 'amnesty')}
            value={meta.byline_entity}
            onChange={editBylineEntity}
          />
          <TextControl
            label={__('Byline Context', 'amnesty')}
            help={__(
              'Additional context for the byline; e.g. job title, short summary, location, etc',
              'amnesty',
            )}
            value={meta.byline_context}
            onChange={editBylineContext}
          />
        </>
      )}
    </>
  );
}
