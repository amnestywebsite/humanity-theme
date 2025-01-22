import classnames from 'classnames';

import { InnerBlocks } from '@wordpress/block-editor';

const v1 = {
  save({ className, innerBlocks }) {
    const leftImage = innerBlocks[0]?.attributes?.image?.id;
    const rightImage = innerBlocks[1]?.attributes?.image?.id;

    const blockClasses = classnames(className, {
      'has-imageLeft': !!leftImage,
      'has-imageRight': !!rightImage,
    });

    return (
      <div className={blockClasses}>
        <InnerBlocks.Content />
      </div>
    );
  },
};

const deprecated = [v1];

export default deprecated;
