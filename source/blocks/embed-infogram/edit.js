import { TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const { useBlockProps } = wp.blockEditor;

const edit = ({ attributes, setAttributes }) => (
  <>
    <div {...useBlockProps()}>
    <TextControl
      // translators: [admin]
      label={__('The embed code', 'amnesty')}
      value={attributes.identifier}
      onChange={(identifier) => setAttributes({ identifier })}
      placeholder="e.g. 75e11a7d-a5bb-45b2-9764-b5fdbdfdf489"
    />
    <TextControl
      // translators: [admin]
      label={__('The embed type', 'amnesty')}
      value={attributes.type}
      onChange={(type) => setAttributes({ type })}
      placeholder="e.g. 'interactive'"
    />
    <TextControl
      // translators: [admin]
      label={__('The embed title', 'amnesty')}
      value={attributes.title}
      onChange={(title) => setAttributes({ title })}
      // translators: [admin]
      placeholder={__('Ending the Death Penalty', 'amnesty')}
    />
    </div>
  </>
);

export default edit;