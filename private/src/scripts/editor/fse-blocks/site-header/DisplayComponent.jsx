const { serverSideRender: ServerSideRender } = wp;

const SiteHeader = () => (
  <ServerSideRender block="amnesty-core/site-header" className="site-header" />
);

export default SiteHeader;
