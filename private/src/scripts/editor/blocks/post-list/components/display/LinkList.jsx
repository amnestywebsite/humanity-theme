const LinkItem = (props) => (
  <li>
    <article className="linkList-item">
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
          <span className="linkList-itemDate">
            <p className="dateTerm">Date: </p>
            <p className="dateDescription"> {props.date} </p>
          </span>
        )}
        {props.showAuthor && (
          <span className="linkList-itemAuthor">
            <p className="authorTerm"> Author: </p>
            <p className="authorDescription"> {props.authorName} </p>
          </span>
        )}
      </div>
    </article>
  </li>
);

export default LinkItem;
