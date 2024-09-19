const { serverSideRender: ServerSideRender } = wp;

const ArchiveHeader = () => (
  <ServerSideRender block="amnesty-core/archive-header" className="archive-header" />
);

export default ArchiveHeader;
