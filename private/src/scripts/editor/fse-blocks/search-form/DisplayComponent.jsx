const { serverSideRender: ServerSideRender } = wp;

const SearchForm = () => (
  <ServerSideRender block="amnesty-core/search-form" className="horizontal-search" />
);

export default SearchForm;
