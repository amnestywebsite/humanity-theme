import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { Button, PanelBody, TextControl } from '@wordpress/components';
import { createRef } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import { httpsOnly } from '../../utils';
import EmbedContainer from './components/EmbedContainer.jsx';
import FramePlaceholder from './components/FramePlaceholder.jsx';

const BlockInspectorControls = ({ attributes, setAttributes, resetEmbed }) => {
  let widthHelpText = '';
  if (!attributes.width && attributes.height) {
    /* translators: [admin] */
    widthHelpText = __('Required when specifying a height', 'amnesty');
  }

  let heightHelpText = '';
  if (!attributes.height && attributes.width) {
    /* translators: [admin] */
    heightHelpText = __('Required when specifying a width', 'amnesty');
  }

  let minHeightHelpText = '';
  if (!attributes.width && !attributes.height) {
    /* translators: [admin] */
    minHeightHelpText = __('Required if not using width/height, optional otherwise', 'amnesty');
  }

  return (
    <InspectorControls>
      <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
        <TextControl
          /* translators: [admin] */
          label={__('Width in percentage', 'amnesty')}
          value={attributes.width}
          type="number"
          step={1}
          help={widthHelpText}
          onChange={(width) => setAttributes({ width })}
        />
        <TextControl
          /* translators: [admin] */
          label={__('Height in pixels', 'amnesty')}
          value={attributes.height}
          type="number"
          step={10}
          help={heightHelpText}
          onChange={(height) => setAttributes({ height })}
        />
        <TextControl
          /* translators: [admin] */
          label={__('Minimum Height', 'amnesty')}
          value={attributes.minHeight}
          type="number"
          step={10}
          min={0}
          max={1000}
          help={minHeightHelpText}
          onChange={(minHeight) => setAttributes({ minHeight })}
        />
        <hr />
        <TextControl
          /* translators: [admin] */
          label={__('Iframe Title', 'amnesty')}
          /* translators: [admin] */
          help={__('Set the text alternative for the iframe', 'amnesty')}
          value={attributes.title}
          onChange={(title) => setAttributes({ title })}
        />
        <hr />
        {attributes.embedUrl && (
          <Button onClick={resetEmbed} variant="primary">
            {/* translators: [admin] */ __('Reset Embed Url', 'amnesty')}
          </Button>
        )}
      </PanelBody>
    </InspectorControls>
  );
};

export default function Edit(props) {
  const { attributes, setAttributes } = props;
  const blockProps = useBlockProps();
  const inputRef = createRef();

  const onSubmit = (event) => {
    event.preventDefault();
    setAttributes({ embedUrl: httpsOnly(inputRef.current.value) });
  };

  const resetEmbed = (event) => {
    event.preventDefault();
    setAttributes({ embedUrl: '' });
  };

  const embedProps = {
    ...props,
    ...blockProps,
  };

  const frameProps = {
    ...props,
    ...blockProps,
    inputRef,
    onSubmit,
  };

  return (
    <>
      <BlockInspectorControls {...props} resetEmbed={resetEmbed} />
      <div {...blockProps} style={{ padding: '1px' }}>
        {attributes.embedUrl && <EmbedContainer {...embedProps} />}
        {!attributes.embedUrl && <FramePlaceholder {...frameProps} />}
      </div>
    </>
  );
}
