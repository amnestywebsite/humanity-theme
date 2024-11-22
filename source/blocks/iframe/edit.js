import { httpsOnly } from '../../utils';

import { InspectorControls, RichText, BlockControls, AlignmentToolbar, useBlockProps } from '@wordpress/block-editor';
import { Button, PanelBody, Placeholder, TextControl } from '@wordpress/components';
import { useRef } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

const edit = ({ attributes, isSelected, setAttributes }) => {
  const inputRef = useRef();

  const onSubmit = (event) => {
    event.preventDefault();
    setAttributes({ embedUrl: httpsOnly(inputRef.current.value) });
  };

  const doReset = (event) => {
    event.preventDefault();
    setAttributes({ embedUrl: '' });
  };

  const placeholder = () => {
    // translators: [admin]
    const label = __('Iframe URL', 'amnesty');

    return (
      <Placeholder label={label} className="wp-block-embed">
        <form onSubmit={onSubmit}>
          <input
            ref={inputRef}
            type="url"
            className="components-placeholder__input"
            aria-label={label}
            // translators: [admin]
            placeholder={__('Enter URL to embed here…', 'amnesty')}
          />
          <Button isLarge type="submit" className='button button-primary'>
            {/* translators: [admin] */ __('Embed', 'amnesty')}
          </Button>
        </form>
      </Placeholder>
    );
  };

  const embedContainer = () => {
    const width = parseInt(attributes.width, 10) || 0;
    const height = parseInt(attributes.height, 10) || 0;
    let minHeight = parseInt(attributes.minHeight, 10) || height;
    let minWidth = parseInt(attributes.minWidth, 10) || width;

    if (!width && !height) {
      minHeight = Math.max(minHeight, 350);
    }

    if (!width) {
      minWidth = `100%`;
    } else {
      minWidth = `${width}px`;
    }

    return (
      <figure className="wp-block-embed">
        <div
          className={"fluid-iframe"}
          style={{ minHeight }}>
          <iframe
            src={httpsOnly(attributes.embedUrl)}
            style={{ height: `${minHeight}px`, width: minWidth }}
          />
        </div>
        <div className={"iframe-caption"}>
          {(attributes.caption || isSelected) && (
            <RichText
              tagName="figcaption"
              // translators: [admin]
              placeholder={__('Write caption…', 'amnesty')}
              value={attributes.caption}
              onChange={(caption) => setAttributes({ caption })}
              inlineToolbar
              format="string"
            />
          )}
        </div>
      </figure>
    );
  };

  const controls = (
    <InspectorControls>
      <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
        <TextControl
          // translators: [admin]
          label={__('Width', 'amnesty')}
          value={attributes.width}
          type="number"
          step={10}
          help={
            !attributes.width && attributes.height
              ? // translators: [admin]
                __('Required when specifying a height', 'amnesty')
              : ''
          }
          onChange={(width) => setAttributes({ width })}
        />
        <TextControl
          // translators: [admin]
          label={__('Height', 'amnesty')}
          value={attributes.height}
          type="number"
          step={10}
          help={
            !attributes.height && attributes.width
              ? // translators: [admin]
                __('Required when specifying a width', 'amnesty')
              : ''
          }
          onChange={(height) => setAttributes({ height })}
        />
        <TextControl
          // translators: [admin]
          label={__('Minimum Height', 'amnesty')}
          value={attributes.minHeight}
          type="number"
          step={10}
          min={0}
          max={1000}
          help={
            !attributes.height && !attributes.width
              ? // translators: [admin]
                __('Required if not using width/height, optional otherwise', 'amnesty')
              : ''
          }
          onChange={(minHeight) => setAttributes({ minHeight })}
        />
        <hr />
        <TextControl
          // translators: [admin]
          label={__('Iframe Title', 'amnesty')}
          // translators: [admin]
          help={__('Set the text alternative for the iframe', 'amnesty')}
          value={attributes.title}
          onChange={(title) => setAttributes({ title })}
        />
        <hr />
        {attributes.embedUrl && (
          <Button onClick={doReset} primary className='button button-primary'>
            {/* translators: [admin] */ __('Reset Embed Url', 'amnesty')}
          </Button>
        )}
      </PanelBody>
    </InspectorControls>
  );

  return (
    <>
      {controls}
      <div {...useBlockProps()} style={{ padding: '1px' }}>
        {attributes.embedUrl ? embedContainer() : placeholder()}
      </div>
    </>
  );
};

export default edit;
