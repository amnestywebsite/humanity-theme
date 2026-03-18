const { PostTaxonomies, PostTaxonomiesCheck } = wp.editor;

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
