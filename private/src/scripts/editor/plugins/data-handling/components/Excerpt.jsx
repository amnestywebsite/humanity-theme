const { useSelect } = wp.data;
const { PostExcerpt, PostExcerptCheck } = wp.editor;
const prefsStore = wp.preferences.store.name;

/**
 * Render the component for managing an entity's excerpt
 *
 * @return {JSX.Element}
 */
export default function Excerpt() {
  const inactivePanels = useSelect((select) => select(prefsStore).get('core', 'inactivePanels'));

  if (inactivePanels.includes('post-excerpt')) {
    return null;
  }

  return (
    <PostExcerptCheck>
      <PostExcerpt />
    </PostExcerptCheck>
  );
}
