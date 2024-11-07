import { httpsOnly } from '../../utils';

import { BlockAlignmentToolbar, BlockControls } from '@wordpress/block-editor';
import { Placeholder, TextControl } from '@wordpress/components';
import { select } from '@wordpress/data';
import { __, sprintf } from '@wordpress/i18n';

const { useBlockProps } = wp.blockEditor;
const stripScript = (html) => html.replace(/<script>.+<\/script>/, '');

const edit = ({ attributes, setAttributes }) =>  (
  <>
    <div {...useBlockProps()}>
    <Placeholder
      // translators: [admin]
      label={__('Tickcounter Embed', 'amnesty')}
      instructions={sprintf(
        // translators: [admin]
        __(
          'This embed cannot be previewed in the editor. Preview the %s itself to see the embed in action',
          'amnesty',
        ),
        select('core/editor').getCurrentPostType(),
      )}
    >
      <TextControl
        value={httpsOnly(attributes.source)}
        onChange={(source) => setAttributes({ source: httpsOnly(stripScript(source)) })}
        // translators: [admin]
        placeholder={__('The Tickcounter embed code', 'amnesty')}
      />
    </Placeholder>
    </div>
  </>
);

export default edit;
