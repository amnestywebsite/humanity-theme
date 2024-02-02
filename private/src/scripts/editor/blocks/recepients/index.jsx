import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

if (window?.typenow === 'post') {
  registerBlockType('amnesty-core/block-recipients', {
    // translators: [admin]
    title: __('Recipients', 'amnesty'),
    // translators: [admin]
    description: __('Add a list of recipients', 'amnesty'),
    icon: 'list-view',
    category: 'amnesty-core',
    supports: {
      className: false,
      multiple: false,
    },

    attributes: {
      refreshedRecipients: {
        type: 'string',
        source: 'meta',
        meta: 'recipients_refresh',
      },
      recipients: {
        type: 'string',
        source: 'meta',
        meta: 'recipients',
      },
      hasRefreshed: {
        type: 'string',
        source: 'meta',
        meta: 'recipients_refreshed',
      },
    },

    edit: DisplayComponent,

    save: () => null,
  });
}
