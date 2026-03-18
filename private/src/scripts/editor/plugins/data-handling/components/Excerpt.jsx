const { useSelect } = wp.data;
const { PostExcerpt, PostExcerptCheck } = wp.editor;
const editorStore = wp.editPost.store.name;

/**
 * Render the component for managing an entity's excerpt
 *
 * @return {JSX.Element}
 */
export default function Excerpt() {
  const panels = useSelect((select) => select(editorStore).getPreference('panels'));

  if (!panels['post-excerpt'].enabled) {
    return null;
  }

  return (
    <PostExcerptCheck>
      <PostExcerpt />
    </PostExcerptCheck>
  );
}
