const { __ } = wp.i18n;

const PetitionItem = (props) => (
  <article className="grid-item petition-item">
    <figure>
      {props.featured_image && <img className="petition-itemImage" src={props.featured_image} />}
      {props.tag && (
        <span className="petition-itemImageCaption">
          <a>{props.tag.title}</a>
        </span>
      )}
    </figure>
    <div className="petition-item-content">
      {props.excerpt && (
        <div className="petition-itemExcerpt" dangerouslySetInnerHTML={{ __html: props.excerpt }} />
      )}
      <h3 className="petition-itemTitle">
        <a>{props.title}</a>
      </h3>
      <button className="btn petition-itemCta">
        {/* translators: [front] Action button label text */ __('Act Now', 'amnesty')}
      </button>
    </div>
  </article>
);

export default PetitionItem;
