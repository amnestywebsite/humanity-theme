const { useBlockProps, InnerBlocks } = wp.blockEditor;

const edit = () => (
  <aside {...useBlockProps({
    className: 'pop-in u-textCenter',
  })} id="pop-in">
    <div className="section section--small">
      <div className="container container--small">
        <InnerBlocks />
      </div>
    </div>
  </aside>
);

export default edit;
