const LinkItem = (props) => {
  const backgroundImage = props.featured_image
    ? { backgroundImage: `url(${props.featured_image})` }
    : '';

  return (
    <li>
      <article className="linkList-item" style={{ ...backgroundImage }}>
        {props.tag && (
          <span className="linkList-itemMeta">
            <a href="#">{props.tag.title}</a>
          </span>
        )}
        <h3 className="linkList-itemTitle">
          <a>{props.title}</a>
        </h3>
        <div className="postInfo-container">
          {props.showPostDate && (
            <p className="linkList-itemDate">
              <span className="dateTerm">Date: </span>
              <span className="dateDescription">{props.date}</span>
            </p>
          )}
          {props.showAuthor && (
            <p className="linkList-itemAuthor">
              <span className="authorTerm">Author: </span>
              <span className="authorDescription">{props.authorName}</span>
            </p>
          )}
        </div>
      </article>
    </li>
  );
};

export default LinkItem;
