import { httpsOnly } from '../../utils';

import { Button, TextControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

const { useBlockProps } = wp.blockEditor;

const getIframeSrc = (rawValue, props) => {
  const { setAttributes } = props;

  const source = rawValue.replace(/^.*<iframe.*?src=(["'])([^\1]*?)\1.*$/, '$2');

  if (source) {
    setAttributes({ source: httpsOnly(source) });
  }
};

const edit = (props) => {
  const blockProps = useBlockProps();
  const { attributes } = props;

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
    <>
    <div {...blockProps}>
      <TextControl
        value={httpsOnly(attributes.source)}
        onChange={(source) => getIframeSrc(source, props)}
        // translators: [admin]
        placeholder={__('The Sutori embed code', 'amnesty')}
      />
      <Button isPrimary={true} onClick={() => preview(!isPreviewing)}>
        {/* translators: [admin] */ __('Preview Embed', 'amnesty')}
      </Button>
    </div>
    </>
  );
};

export default edit;
