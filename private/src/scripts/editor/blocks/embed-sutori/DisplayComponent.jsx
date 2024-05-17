import { httpsOnly } from '../../utils';

const { Button, TextControl } = wp.components;
const { Fragment, useState } = wp.element;
const { __ } = wp.i18n;

const getIframeSrc = (rawValue, props) => {
  const { setAttributes } = props;

  const source = rawValue.replace(/^.*<iframe.*?src=(["'])([^\1]*?)\1.*$/, '$2');

  if (source) {
    setAttributes({ source: httpsOnly(source) });
  }
};

const DisplayComponent = (props) => {
  const { attributes } = props;

  const [isPreviewing, preview] = useState(false);

  if (isPreviewing && httpsOnly(attributes.source)) {
    return (
      <div style={{ minHeight: '20px', border: '1px dashed' }}>
        <div className="sutori-embed">
          <iframe src={httpsOnly(attributes.source)} height="600px" width="100%"></iframe>
        </div>
      </div>
    );
  }

  return (
    <Fragment>
      <TextControl
        value={httpsOnly(attributes.source)}
        onChange={(source) => getIframeSrc(source, props)}
        // translators: [admin]
        placeholder={__('The Sutori embed code', 'amnesty')}
      />
      <Button isPrimary={true} onClick={() => preview(!isPreviewing)}>
        {/* translators: [admin] */ __('Preview Embed', 'amnesty')}
      </Button>
    </Fragment>
  );
};

export default DisplayComponent;
