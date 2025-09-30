import { DragDropContext, Droppable } from '@hello-pangea/dnd';
import { Post } from './Post.jsx';

const { __ } = wp.i18n;

/**
 * PostList Component
 *
 * @param object props - Component props.
 *
 * @returns {*}
 */
export const PostList = (props) => {
  const { filtered = false, loading = false, posts = [], action = () => {}, icon = null } = props;

  if (loading) {
    /* translators: [admin] */
    return <p>{__('Loading Posts…', 'amnesty')}</p>;
  }

  if (filtered && posts.length < 1) {
    return (
      <div className="post-list">
        <p>
          {
            /* translators: [admin] */ __(
              'Your query yielded no results, please try again.',
              'amnesty',
            )
          }
        </p>
      </div>
    );
  }

  if (!posts || posts.length < 1) {
    return <p>{/* translators: [admin] */ __('No Posts.', 'amnesty')}</p>;
  }

  function handleOnDragEnd(result) {
    props.reorderPosts(result);
  }

  return (
    <div className="post-list">
      <DragDropContext onDragEnd={handleOnDragEnd}>
        <Droppable droppableId="posts">
          {(provided) => (
            <div {...provided.droppableProps} ref={provided.innerRef}>
              {posts.map((post, index) => (
                <Post key={post.id} {...post} index={index} clickHandler={action} icon={icon} />
              ))}
              {provided.placeholder}
            </div>
          )}
        </Droppable>
      </DragDropContext>
      {props.canPaginate ? (
        <button onClick={props.doPagination} disabled={props.paging}>
          {props.paging
            ? /* translators: [admin] */
              __('Loading…', 'amnesty')
            : /* translators: [admin] */
              __('Load More', 'amnesty')}
        </button>
      ) : null}
    </div>
  );
};

export default PostList;
