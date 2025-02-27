import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

const TEMPLATE = [
  ['amnesty-core/background-media-column', { deletable: false }],
  ['amnesty-core/background-media-column', { deletable: false }],
];

export default function Edit() {
  return (
    <div {...useBlockProps()}>
      <InnerBlocks template={TEMPLATE} templateLock="all" renderAppender={false} />
    </div>
  );
}
