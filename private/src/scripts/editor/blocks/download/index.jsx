/**
 * Internal dependencies
 */
import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { select } = wp.data;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/block-download', {
  // translators: [admin]
  title: __('Resource Download', 'amnesty'),
  // translators: [admin]
  description: __('Add a button to download resource(s)', 'amnesty'),
  icon: 'download',
  category: 'amnesty-core',
  supports: {
    className: false,
    multiple: true,
  },

  attributes: {
    files: {
      type: 'array',
      default: [],
    },
    buttonText: {
      type: 'string',
      /* translators: [front] Download block https://wordpresstheme.amnesty.org/blocks/015-download-resource/ */
      default: __('Download', 'amnesty'),
    },
    style: {
      type: 'string',
      default: 'dark',
    },
    alignment: {
      type: 'string',
      default: 'none',
    },
  },

  deprecated: [
    {
      supports: {
        className: false,
        multiple: false,
      },
      attributes: {
        fileId: {
          type: 'integer',
          source: 'meta',
          meta: 'download_id',
        },
        downloadButtonText: {
          type: 'string',
          source: 'meta',
          meta: 'download_text',
        },
      },
      isEligible: (attributes) => {
        const meta = select('core/editor').getCurrentPostAttribute('meta');

        if (!meta) {
          return false;
        }

        if (attributes.files && attributes.files.length) {
          return false;
        }

        const { download_id: fileId } = meta;

        return !!fileId;
      },
      migrate: () => {
        const meta = select('core/editor').getCurrentPostAttribute('meta');

        if (!meta) {
          return {};
        }

        const { download_id: fileId, download_text: downloadButtonText } = meta;

        return {
          files: [fileId],
          buttonText: downloadButtonText,
          style: 'dark',
        };
      },
      save: () => null,
    },
  ],

  edit: DisplayComponent,

  save: () => null,
});
