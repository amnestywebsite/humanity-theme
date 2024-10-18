import { httpsOnly } from '../../utils';

import { delay } from 'lodash';
import { Button, TextControl } from '@wordpress/components';
import { Fragment, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

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

const edit = (props) => {
  const { attributes, setAttributes } = props;

  const [isPreviewing, preview] = useState(false);

  if (isPreviewing && attributes.source) {
    delay(loadScript, 1000);
    return (
      <div style={{ minHeight: '20px', border: '1px dashed' }}>
        <div className="flourish-embed" data-src={attributes.source}></div>
      </div>
    );
  }

  return (
    <Fragment>
      <TextControl
        value={httpsOnly(attributes.source)}
        onChange={(source) => setAttributes({ source })}
        // translators: [admin]
        label={__('The Flourish embed source (not the full URL).', 'amnesty')}
        placeholder="e.g. visualisation/123456"
      />
      <Button isPrimary={true} onClick={() => preview(!isPreviewing)}>
        {/* translators: [admin] */ __('Preview Embed', 'amnesty')}
      </Button>
    </Fragment>
  );
};

export default edit;
