const { RichText } = wp.blockEditor;
const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/key-fact', {
  title: 'Key Fact',
  parent: ['amnesty-core/key-facts'],
  category: 'layout',
  supports: {
    className: false,
  },

  attributes: {
    title: {
      type: 'string',
    },
    content: {
      type: 'string',
    },
  },

  edit({ attributes, setAttributes }) {
    const { title, content } = attributes;

    return (
      <div className="factBlock-item">
        <RichText
          className="factBlock-itemTitle"
          tagName="h3"
          // translators: [admin]
          placeholder={__('(Insert Title)', 'amnesty')}
          keepPlaceholderOnFocus={true}
          value={title}
          allowedFormats={[]}
          onChange={(newTitle) => setAttributes({ title: newTitle })}
        />
        <RichText
          className="factBlock-itemContent"
          tagName="p"
          // translators: [admin]
          placeholder={__('(Insert Content)', 'amnesty')}
          keepPlaceholderOnFocus={true}
          value={content}
          allowedFormats={[]}
          onChange={(newContent) => setAttributes({ content: newContent })}
        />
      </div>
    );
  },

  save({ attributes }) {
    const { title, content } = attributes;

    return (
      <li className="factBlock-item">
        <h3 className="factBlock-itemTitle">{title}</h3>
        <p className="factBlock-itemContent">{content}</p>
      </li>
    );
  },
});
