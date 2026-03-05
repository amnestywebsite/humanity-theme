/* eslint-disable no-underscore-dangle */

const { TextControl, ToggleControl } = wp.components;
const { __ } = wp.i18n;

/**
 * Render the component for managing an entity's byline visibility
 *
 * @param {object} param0 props passed to the component
 * @param {object} param0.postMeta the entity's meta object
 * @param {function} param0.editMeta callback for manipulating entity meta
 *
 * @return {JSX.Element}
 */
export default function Byline({ postMeta: meta, editMeta }) {
  return (
    <>
      <ToggleControl
        label={__('Show byline', 'amnesty')}
        help={__(
          'Content will be unattributed, and the author information will remain private, unless the byline is enabled and populated',
          'amnesty',
        )}
        checked={meta?._display_author_info}
        onChange={(value) => editMeta('_display_author_info')(value)}
      />
      {meta?._display_author_info && (
        <ToggleControl
          label={__('Use author profile for byline', 'amnesty')}
          checked={meta?.byline_is_author}
          onChange={(value) => editMeta('byline_is_author')(value)}
        />
      )}
      {meta?._display_author_info && !meta?.byline_is_author && (
        <>
          <TextControl
            __next40pxDefaultSize
            label={__('Byline Name', 'amnesty')}
            help={__('The individual, group, or entity to be named as the author', 'amnesty')}
            value={meta?.byline_entity}
            onChange={(value) => editMeta('byline_entity')(value)}
          />
          <TextControl
            __next40pxDefaultSize
            label={__('Byline Context', 'amnesty')}
            help={__(
              'Additional context for the byline; e.g. job title, short summary, location, etc',
              'amnesty',
            )}
            value={meta?.byline_context}
            onChange={(value) => editMeta('byline_context')(value)}
          />
        </>
      )}
    </>
  );
}
