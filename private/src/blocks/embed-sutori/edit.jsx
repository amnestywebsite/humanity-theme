import { useBlockProps } from '@wordpress/block-editor';
import { Button, TextControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import { httpsOnly } from '../../utils';

const getIframeSrc = (rawValue, setAttributes) => {
  const source = rawValue.replace(/^.*<iframe.*?src=(["'])([^\1]*?)\1.*$/, '$2');

  if (source) {
    setAttributes({ source: httpsOnly(source) });
  }
};

export default function Edit({ attributes, setAttributes }) {
  const blockProps = useBlockProps();
  const [isPreviewing, preview] = useState(false);

  if (isPreviewing && httpsOnly(attributes.source)) {
    return (
      <div {...blockProps} style={{ minHeight: '20px', border: '1px dashed' }}>
        <div className="sutori-embed">
          <iframe src={httpsOnly(attributes.source)} height="600px" width="100%"></iframe>
        </div>
      </div>
    );
  }

  return (
    <div {...blockProps}>
      <TextControl
        value={httpsOnly(attributes.source)}
        onChange={(source) => getIframeSrc(source, setAttributes)}
        /* translators: [admin] */
        placeholder={__('The Sutori embed code', 'amnesty')}
      />
      <Button variant="primary" onClick={() => preview(!isPreviewing)}>
        {/* translators: [admin] */ __('Preview Embed', 'amnesty')}
      </Button>
    </div>
  );
}
