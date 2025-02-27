import { InnerBlocks } from '@wordpress/block-editor';

export default function Edit() {
  return (
    <>
      <div className="rowColumn">
        <InnerBlocks templateLock={false} />
      </div>
    </>
  );
}
