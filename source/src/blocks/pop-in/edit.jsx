import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function Edit() {
  return (
    <aside {...useBlockProps({ className: 'pop-in u-textCenter' })} id="pop-in">
      <div className="section section--small">
        <div className="container container--small">
          <InnerBlocks />
        </div>
      </div>
    </aside>
  );
}
