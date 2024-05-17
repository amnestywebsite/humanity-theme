import { httpsOnly } from '../../utils';

const { BlockAlignmentToolbar, BlockControls } = wp.blockEditor;
const { Placeholder, TextControl } = wp.components;
const { select } = wp.data;
const { Fragment } = wp.element;
const { __, sprintf } = wp.i18n;

const stripScript = (html) => html.replace(/<script>.+<\/script>/, '');

const DisplayComponent = (props) => {
  const { attributes, setAttributes } = props;

  return (
    <Fragment>
      <BlockControls>
        <BlockAlignmentToolbar
          value={attributes.alignment}
          onChange={(alignment) => setAttributes({ alignment })}
        />
      </BlockControls>
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
    </Fragment>
  );
};

export default DisplayComponent;
