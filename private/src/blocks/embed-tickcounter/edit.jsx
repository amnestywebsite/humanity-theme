import { useBlockProps } from '@wordpress/block-editor';
import { Placeholder, TextControl } from '@wordpress/components';
import { select } from '@wordpress/data';
import { __, sprintf } from '@wordpress/i18n';

import { httpsOnly } from '../../utils';

const stripScript = (html) => html.replace(/<script>.+<\/script>/, '');

export default function Edit({ attributes, setAttributes }) {
  return (
    <div {...useBlockProps()}>
      <Placeholder
        /* translators: [admin] */
        label={__('Tickcounter Embed', 'amnesty')}
        instructions={sprintf(
          /* translators: [admin] */
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
          /* translators: [admin] */
          placeholder={__('The Tickcounter embed code', 'amnesty')}
        />
      </Placeholder>
    </div>
  );
}
