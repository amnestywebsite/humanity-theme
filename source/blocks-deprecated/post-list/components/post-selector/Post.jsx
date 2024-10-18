import { Draggable } from 'react-beautiful-dnd';
/**
 * Post Component.
 *
 * @param {string} postTitle - Current post title.
 * @param {function} clickHandler - this is the handling function for the add/remove function
 * @param {Integer} postId - Current post ID
 * @param {string|boolean} featured_image - Posts featured image
 * @param icon
 *
 * @returns {*} Post HTML.
 */
export const Post = ({
  title: { rendered: postTitle } = {},
  clickHandler,
  id: postId,
  featured_image: featuredImage = false,
  icon,
  index,
}) => {
  const style = {};
  if (featuredImage) {
    style.backgroundImage = `url("${featuredImage}")`;
  }

  const postIdString = postId.toString();

  return (
    <Draggable key={postId} draggableId={postIdString} index={index}>
      {(provided) => (
        <article
          ref={provided.innerRef}
          {...provided.draggableProps}
          {...provided.dragHandleProps}
          className="post"
        >
          <figure className="post-figure" style={style}></figure>
          <div className="post-body">
            <h3 className="post-title">{postTitle}</h3>
          </div>
          {icon && <button onClick={() => clickHandler(postId)}>{icon}</button>}
        </article>
      )}
    </Draggable>
  );
};

export default Post;
