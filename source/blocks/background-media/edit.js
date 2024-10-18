import { InnerBlocks } from '@wordpress/block-editor';

const TEMPLATE = [
  ['amnesty-core/background-media-column', { deletable: false }],
  ['amnesty-core/background-media-column', { deletable: false }],
];

const edit = ({ className }) => (
  <div className={className}>
    <InnerBlocks template={TEMPLATE} templateLock="all" renderAppender={false} />
  </div>
);

export default edit;
