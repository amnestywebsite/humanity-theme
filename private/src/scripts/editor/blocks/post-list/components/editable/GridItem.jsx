const { RichText, URLInputButton, MediaUpload } = wp.blockEditor;
const { IconButton } = wp.components;
const { __ } = wp.i18n;
const { get } = lodash;

const GridItem = (props) => (
  <article className="grid-item" style={{ backgroundImage: `url(${props.featured_image})` }}>
    <span className="grid-itemMeta">
      <RichText
        tagName="span"
        onChange={props.createUpdate('tagText')}
        value={props.tagText}
        // translators: [admin]
        placeholder={__('(Insert Tag)', 'amnesty')}
        keepPlaceholderOnFocus={true}
        allowedFormats={[]}
        format="string"
      />
      <URLInputButton url={props.tagLink} onChange={props.createUpdate('tagLink')} />
    </span>
    <h3 className="grid-itemTitle">
      <a>
        <RichText
          tagName="span"
          onChange={props.createUpdate('title')}
          value={props.title}
          // translators: [admin]
          placeholder={__('(Insert Title)', 'amnesty')}
          keepPlaceholderOnFocus={true}
          allowedFormats={[]}
          format="string"
        />
        <URLInputButton url={props.titleLink} onChange={props.createUpdate('titleLink')} />
      </a>
    </h3>
    <div className="grid-itemContent">
      <RichText
        tagName="p"
        onChange={props.createUpdate('excerpt')}
        value={props.excerpt}
        // translators: [admin]
        placeholder={__('(Insert Content)', 'amnesty')}
        keepPlaceholderOnFocus={true}
        allowedFormats={[]}
        format="string"
      />
    </div>
    <div className="linkList-options">
      {props.featured_image_id && props.featured_image_id !== -1 && (
        <IconButton
          icon="no-alt"
          onClick={() =>
            props.updateMedia({
              featured_image_id: '',
              featured_image: '',
            })
          }
        >
          {/* translators: [admin] */ __('Remove Image', 'amnesty')}
        </IconButton>
      )}
      <MediaUpload
        onSelect={({ id, sizes, url }) =>
          props.updateMedia({
            featured_image_id: id,
            featured_image: get(sizes, "['post-half@2x'].url", url),
          })
        }
        value={props.featured_image_id}
        allowedTypes={['image']}
        render={({ open }) => <IconButton icon="format-image" onClick={open} />}
      />
      <IconButton onClick={props.createRemove} icon="trash" />
    </div>
  </article>
);

export default GridItem;
