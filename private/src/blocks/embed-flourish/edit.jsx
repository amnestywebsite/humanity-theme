import { delay } from 'lodash';
import { useBlockProps } from '@wordpress/block-editor';
import { Button, TextControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import { httpsOnly } from '../../utils';

// load flourish embed script
const loadScript = () => {
  if (document.getElementById('flourish-script')) {
    return;
  }

  const script = document.createElement('script');
  script.id = 'flourish-script';
  script.async = true;
  document.body.appendChild(script);
  script.src = 'https://public.flourish.studio/resources/embed.js';
};

export default function Edit({ attributes, setAttributes }) {
  const blockProps = useBlockProps();
  const [isPreviewing, preview] = useState(false);

  if (isPreviewing && attributes.source) {
    delay(loadScript, 1000);
    return (
      <div {...blockProps}>
        <div style={{ minHeight: '20px', border: '1px dashed' }}>
          <div className="flourish-embed" data-src={attributes.source}></div>
        </div>
      </div>
    );
  }

  return (
    <div {...blockProps}>
      <TextControl
        value={httpsOnly(attributes.source)}
        onChange={(source) => setAttributes({ source })}
        /* translators: [admin] */
        label={__('The Flourish embed source (not the full URL).', 'amnesty')}
        placeholder="e.g. visualisation/123456"
      />
      <Button variant="primary" onClick={() => preview(!isPreviewing)}>
        {/* translators: [admin] */ __('Preview Embed', 'amnesty')}
      </Button>
    </div>
  );
}
