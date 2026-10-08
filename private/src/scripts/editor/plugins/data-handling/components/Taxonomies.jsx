const { PostTaxonomies, PostTaxonomiesCheck } = wp.editor;

/**
 * Wrapper component for the core taxonomy component
 *
 * @param {JSX.Element} children the taxonomy component tree
 * @param {object}      taxonomy the taxonomy object
 *
 * @return {JSX.Element}
 */
function TaxonomyWrapper(children, taxonomy) {
  return (
    <div className={`amnesty-data-handling-taxonomy taxonomy-${taxonomy.slug}`}>
      <h2>{taxonomy.name}</h2>
      {children}
    </div>
  );
}

/**
 * Render the component for managing an entity's taxonomies
 *
 * @return {JSX.Element}
 */
export default function Taxonomies() {
  return (
    <PostTaxonomiesCheck>
      <PostTaxonomies taxonomyWrapper={TaxonomyWrapper} />
    </PostTaxonomiesCheck>
  );
}
