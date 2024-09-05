const { serverSideRender: ServerSideRender } = wp;
const { __ } = wp.i18n;

const Pagination = () => (
  <>
    <ServerSideRender block="amnesty-core/pagination" className="pagination" />
    <p>{__('Pagination for the search results will appear here')}</p>
  </>

);

export default Pagination;
