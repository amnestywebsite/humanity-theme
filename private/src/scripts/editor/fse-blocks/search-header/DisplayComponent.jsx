const { serverSideRender: ServerSideRender } = wp;

const SearchHeader = () => <ServerSideRender block="amnesty-core/search-header" />;

export default SearchHeader;
