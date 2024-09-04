const { serverSideRender: ServerSideRender } = wp;

const SiteHeader = () => (
  <ServerSideRender block="amnesty-core/post-search" className="post-search" />
);

export default SiteHeader;
