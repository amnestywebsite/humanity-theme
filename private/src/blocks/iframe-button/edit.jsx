import classnames from 'classnames';
import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { Button, PanelBody, TextControl } from '@wordpress/components';
import { createRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import { httpsOnly } from '../../utils';

export default function Edit({ attributes, setAttributes }) {
  const [previewing, setIsPreviewing] = useState(false);
  const [embedUrl, setEmbedUrl] = useState(httpsOnly(attributes.iframeUrl));
  const inputRef = createRef();

  const classes = classnames('iframeButton wp-block-button');
  const blockProps = useBlockProps({ className: classes });

  const embed = () => {
    setAttributes({ iframeUrl: httpsOnly(embedUrl) });
    setIsPreviewing(true);
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
          <TextControl
            /* translators: [admin] */
            label={__('Iframe Height', 'amnesty')}
            value={attributes.iframeHeight}
            type="number"
            step={50}
            onChange={(iframeHeight) => setAttributes({ iframeHeight })}
          />
          <TextControl
            /* translators: [admin] */
            label={__('Iframe Title', 'amnesty')}
            /* translators: [admin] */
            help={__('Set the text alternative for the iframe', 'amnesty')}
            value={attributes.title}
            onChange={(title) => setAttributes({ title })}
          />
        </PanelBody>
      </InspectorControls>
      <div {...blockProps}>
        <div className={classes}>
          <RichText
            className="wp-block-button__link"
            value={attributes.buttonText}
            /* translators: [admin] */
            placeholder={__('Act Now', 'amnesty')}
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
            /* translators: [admin] */
            aria-label={__('Iframe URL', 'amnesty')}
            /* translators: [admin] */
            placeholder={__('Enter URL to embed here…', 'amnesty')}
            value={embedUrl}
            onChange={() => setEmbedUrl(inputRef.current.value)}
          />
          <Button isLarge primary onClick={embed} className="button button-primary">
            {/* translators: [admin] */ __('Embed', 'amnesty')}
          </Button>
          <Button
            isLarge
            onClick={() => setIsPreviewing(!previewing)}
            className="button button-primary"
          >
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
      </div>
    </>
  );
}
