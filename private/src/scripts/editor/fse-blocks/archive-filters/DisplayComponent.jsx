const { serverSideRender: ServerSideRender } = wp;

const ArchiveFilters = () => <ServerSideRender block="amnesty-core/archive-filters" />;

export default ArchiveFilters;
