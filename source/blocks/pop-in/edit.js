import { InnerBlocks } from '@wordpress/block-editor';

const edit = () => (
  <aside id="pop-in" className="u-textCenter pop-in">
    <div className="section section--small">
      <div className="container container--small">
        <InnerBlocks />
      </div>
    </div>
  </aside>
);

export default edit;
