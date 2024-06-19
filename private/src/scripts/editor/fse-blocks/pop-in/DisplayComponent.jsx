const { InnerBlocks } = wp.blockEditor;

const PopIn = () => (
  <aside id="pop-in" className="u-textCenter pop-in">
    <div className="section section--small">
      <div className="container container--small">
        <InnerBlocks />
      </div>
    </div>
  </aside>
);

export default PopIn;
