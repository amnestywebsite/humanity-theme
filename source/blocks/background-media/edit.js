const { useBlockProps, InnerBlocks } = wp.blockEditor;

const TEMPLATE = [
  ['amnesty-core/background-media-column', { deletable: false }],
  ['amnesty-core/background-media-column', { deletable: false }],
];

const edit = () => (
  <div {...useBlockProps()}>
    <InnerBlocks template={TEMPLATE} templateLock="all" renderAppender={false} />
  </div>
);

export default edit;
