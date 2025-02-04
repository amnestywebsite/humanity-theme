/* eslint-disable camelcase */

import { PanelRow, TextControl, ToggleControl } from '@wordpress/components';
import { compose, ifCondition } from '@wordpress/compose';
import { useDispatch, useSelect } from '@wordpress/data';
import { Fragment, useCallback } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

const useByline = () => {
  const { _display_author_info, byline_is_author, byline_entity, byline_context } = useSelect(
    (select) => select('core/editor').getEditedPostAttribute('meta'),
    [],
  );

  const { editPost } = useDispatch('core/editor');

  const setBylineEnabled = useCallback(
    (value) => {
      editPost({ meta: { _display_author_info: value } });
    },
    [editPost],
  );

  const setBylineIsAuthor = useCallback(
    (value) => {
      editPost({ meta: { byline_is_author: value } });
    },
    [editPost],
  );

  const setBylineEntity = useCallback(
    (value) => {
      editPost({ meta: { byline_entity: value } });
    },
    [editPost],
  );

  const setBylineContext = useCallback(
    (value) => {
      editPost({ meta: { byline_context: value } });
    },
    [editPost],
  );

  return {
    bylineEnabled: _display_author_info,
    bylineIsAuthor: byline_is_author,
    bylineEntity: byline_entity,
    bylineContext: byline_context,
    setBylineEnabled,
    setBylineIsAuthor,
    setBylineEntity,
    setBylineContext,
  };
};

const Byline = () => {
  const {
    bylineEnabled,
    bylineIsAuthor,
    bylineEntity,
    bylineContext,
    setBylineEnabled,
    setBylineIsAuthor,
    setBylineEntity,
    setBylineContext,
  } = useByline();

  return (
    <>
      <PanelRow>
        <ToggleControl
          // translators: [admin]
          label={__('Enable public byline', 'amnesty')}
          help={
            /* translators: [admin] */
            __(
              'Content will be unattributed, and the author information will remain private, unless the byline is enabled and populated',
              'amnesty',
            )
          }
          checked={bylineEnabled}
          onChange={setBylineEnabled}
        />
      </PanelRow>
      {bylineEnabled && (
        <PanelRow>
          <ToggleControl
            // translators: [admin]
            label={__('Use author profile for byline', 'amnesty')}
            checked={bylineIsAuthor}
            onChange={setBylineIsAuthor}
          />
        </PanelRow>
      )}
      {bylineEnabled && !bylineIsAuthor && (
        <Fragment>
          <PanelRow>
            <TextControl
              // translators: [admin]
              label={__('Byline Name', 'amnesty')}
              // translators: [admin]
              help={__('The individual, group, or entity to be named as the author', 'amnesty')}
              value={bylineEntity}
              onChange={setBylineEntity}
            />
          </PanelRow>
          <PanelRow>
            <TextControl
              // translators: [admin]
              label={__('Byline Context', 'amnesty')}
              // translators: [admin]
              help={__(
                'Additional context for the byline; e.g. job title, short summary, location, etc',
                'amnesty',
              )}
              value={bylineContext}
              onChange={setBylineContext}
            />
          </PanelRow>
        </Fragment>
      )}
    </>
  );
};

export default compose([
  ifCondition(() => wp.data.select('core/editor').getEditedPostAttribute('type') === 'post'),
])(Byline);
