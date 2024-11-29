import { InnerBlocks } from '@wordpress/block-editor';

const edit = () => {
  return (
    <>
      <div class="rowColumn">
        <InnerBlocks templateLock={false} />
      </div>
    </>
  );
};

export default edit;
