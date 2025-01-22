const GridItem = (props) => (
  <article className="grid-item" style={{ backgroundImage: `url(${props.featured_image})` }}>
    <div className="grid-itemContent">
      {props.tag && (
        <span className="grid-itemMeta">
          <a>{props.tag.title}</a>
        </span>
      )}
      <h3 className="grid-itemTitle">
        <a>{props.title}</a>
      </h3>
    </div>
  </article>
);

export default GridItem;
