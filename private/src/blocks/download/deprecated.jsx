import { select } from '@wordpress/data';

const v1 = {
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
      // style: 'dark',
    };
  },
  save: () => null,
};

export default [v1];
