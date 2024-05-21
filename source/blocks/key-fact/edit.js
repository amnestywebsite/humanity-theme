const { RichText } = wp.blockEditor;
const { __ } = wp.i18n;

const edit = ({ attributes, setAttributes }) => (
  <div className="factBlock-item">
    <RichText
      className="factBlock-itemTitle"
      tagName="h3"
      // translators: [admin]
      placeholder={__('(Insert Title)', 'amnesty')}
      keepPlaceholderOnFocus={true}
      value={attributes.title}
      allowedFormats={[]}
      onChange={(title) => setAttributes({ title })}
    />
    <RichText
      className="factBlock-itemContent"
      tagName="p"
      // translators: [admin]
      placeholder={__('(Insert Content)', 'amnesty')}
      keepPlaceholderOnFocus={true}
      value={attributes.content}
      allowedFormats={[]}
      onChange={(content) => setAttributes({ content })}
    />
  </div>
);

export default edit;
