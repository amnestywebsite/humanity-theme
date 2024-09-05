const { serverSideRender: ServerSideRender } = wp;
const { __ } = wp.i18n;
const { RecursionProvider, useHasRecursion } = wp.blockEditor;

const PostSearch = () => (
  <>
  <ServerSideRender block="amnesty-core/post-search" className="post-search" />
  <h1>Post Search</h1>
  </>
);

export default PostSearch;
