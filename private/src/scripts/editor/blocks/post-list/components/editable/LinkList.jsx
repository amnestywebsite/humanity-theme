const { RichText, URLInputButton } = wp.blockEditor;
const { IconButton } = wp.components;
const { __ } = wp.i18n;

const LinkItem = (props) => (
  <li>
    <article className="linkList-item">
      <span className="linkList-itemMeta">
        <RichText
          tagName="a"
          onChange={props.createUpdate('tagText')}
          value={props.tagText}
          // translators: [admin]
          placeholder={__('(Tag Name)', 'amnesty')}
          keepPlaceholderOnFocus={true}
          allowedFormats={[]}
          format="string"
        />
        <URLInputButton url={props.tagLink} onChange={props.createUpdate('tagLink')} />
      </span>
      <h3 className="linkList-itemTitle">
        <RichText
          tagName="a"
          onChange={props.createUpdate('title')}
          value={props.title}
          // translators: [admin]
          placeholder={__('(Insert Title)', 'amnesty')}
          keepPlaceholderOnFocus={true}
          allowedFormats={[]}
          format="string"
        />
        <URLInputButton url={props.titleLink} onChange={props.createUpdate('titleLink')} />
      </h3>
      <div className="linkList-options">
        <IconButton onClick={props.createRemove} icon="trash" />
      </div>
    </article>
  </li>
);

export default LinkItem;
