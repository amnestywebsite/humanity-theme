/* eslint-disable no-underscore-dangle */

const { PostTaxonomies } = wp.editor;

function WrappedTaxonomy(children, taxonomy) {
  return (
    <div className="amnesty-data-handling-taxonomy-wrapper">
      <h3>{taxonomy.name}</h3>
      {children}
    </div>
  );
}

/**
 * Render the component for managing an entity's taxonomy terms
 * *
 * @return {JSX.Element}
 */
export default function Taxonomies() {
  return (
    <div className="amnesty-data-handling-taxonomies">
      <PostTaxonomies taxonomyWrapper={WrappedTaxonomy} />
    </div>
  );
}
