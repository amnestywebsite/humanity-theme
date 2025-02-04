import { compose, ifCondition } from '@wordpress/compose';
import { withSelect, withDispatch } from '@wordpress/data';
import { registerPlugin } from '@wordpress/plugins';

import reviseData from '../../utils/reviseData';
import AppearanceOptions from './AppearanceOptions.jsx';

const plugin = compose([
  withSelect((select) => {
    // Grab the post meta that has been edited.
    const postMeta = select('core/editor').getEditedPostAttribute('meta') || {};
    // Grab the post meta that was present on load.
    const oldPostMeta = select('core/editor').getCurrentPostAttribute('meta') || {};

    const template = (select('core/editor').getEditedPostAttribute('template') || '')
      .replace('templates/', '')
      .replace('.php', '');

    return {
      template,
      meta: { ...oldPostMeta, ...postMeta },
      oldMeta: oldPostMeta,
      type: select('core/editor').getEditedPostAttribute('type'),
    };
  }),
  withDispatch((dispatch) => ({
    createMetaUpdate(key, value, newMeta = {}, oldMeta = {}) {
      const meta = {
        ...reviseData(oldMeta, newMeta),
        [key]: value,
      };

      dispatch('core/editor').editPost({ meta });
    },
  })),
  ifCondition(() =>
    ['post', 'page'].includes(wp.data.select('core/editor').getEditedPostAttribute('type')),
  ),
])(AppearanceOptions);

registerPlugin('amnesty-appearance', {
  icon: 'admin-appearance',
  render: plugin,
});
