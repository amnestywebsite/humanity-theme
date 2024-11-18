import ServerSideRender from '@wordpress/server-side-render';

const edit = () => (
  <ServerSideRender block="amnesty-core/search-form" className="horizontal-search" />
);

export default edit;
