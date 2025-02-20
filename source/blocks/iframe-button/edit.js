import classnames from 'classnames';
import { httpsOnly } from '../utils';

const { BlockAlignmentToolbar, BlockControls, InspectorControls, RichText } = wp.blockEditor;
const { Button, PanelBody, TextControl } = wp.components;
const { createRef, useEffect, useState } = wp.element;
const { __ } = wp.i18n;

const edit = (props) => {
  const { attributes, className, setAttributes } = props;
  const [previewing, setIsPreviewing] = useState(false);
  const [embedUrl, setEmbedUrl] = useState('');
  const mounted = createRef();
  const inputRef = createRef();

  const classes = classnames('iframeButton wp-block-button', className, {
    [`is-${attributes.alignment}-aligned`]: attributes.alignment !== 'none',
  });

  useEffect(() => {
    if (!mounted.current) {
      mounted.current = true;
      setEmbedUrl(attributes.iframeUrl);
    }
  }, [attributes.iframeUrl]);

  const embed = () => {
    setAttributes({ iframeUrl: httpsOnly(embedUrl) });
    setIsPreviewing(true);
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
          <TextControl
            // translators: [admin]
            label={__('Iframe Height', 'amnesty')}
            value={attributes.iframeHeight}
            type="number"
            step={50}
            onChange={(iframeHeight) => setAttributes({ iframeHeight })}
          />
          <TextControl
            // translators: [admin]
            label={__('Iframe Title', 'amnesty')}
            // translators: [admin]
            help={__('Set the text alternative for the iframe', 'amnesty')}
            value={attributes.title}
            onChange={(title) => setAttributes({ title })}
          />
        </PanelBody>
      </InspectorControls>
      <BlockControls>
        <BlockAlignmentToolbar
          value={attributes.alignment}
          onChange={(alignment) => setAttributes({ alignment })}
        />
      </BlockControls>
      <div className={classes}>
        <RichText
          className="wp-block-button__link"
          value={attributes.buttonText}
          // translators: [admin]
          placeholder={__('Act Now', 'amnesty')}
          keepPlaceholderOnFocus={true}
          identifier="text"
          allowedFormats={[]}
          withoutInteractiveFormatting={true}
          onChange={(buttonText) => setAttributes({ buttonText })}
        />
      </div>
      <div className="iframeButton-inputWrapper">
        <input
          ref={inputRef}
          type="url"
          className="components-placeholder__input"
          // translators: [admin]
          aria-label={__('Iframe URL', 'amnesty')}
          // translators: [admin]
          placeholder={__('Enter URL to embed here…', 'amnesty')}
          value={embedUrl}
          onChange={() => setEmbedUrl(inputRef.current.value)}
        />
        <Button isLarge isPrimary onClick={embed}>
          {/* translators: [admin] */ __('Embed', 'amnesty')}
        </Button>
        <Button isLarge onClick={() => setIsPreviewing(!previewing)}>
          {previewing
            ? /* translators: [admin] */ __('Hide Preview', 'amnesty')
            : /* translators: [admin] */ __('Preview', 'amnesty')}
        </Button>
      </div>
      {previewing && httpsOnly(attributes.iframeUrl) && (
        <div className="iframeButton-iframeWrapper">
          <iframe src={httpsOnly(attributes.iframeUrl)} height={attributes.iframeHeight}></iframe>
        </div>
      )}
    </>
  );
};

export default edit;
