const { InnerBlocks } = wp.blockEditor;

const TEMPLATE = [
  ['amnesty-core/background-media-column', { deletable: false }],
  ['amnesty-core/background-media-column', { deletable: false }],
];

const DisplayComponent = ({ className }) => (
  <div className={className}>
    <InnerBlocks template={TEMPLATE} templateLock="all" renderAppender={false} />
  </div>
);

export default DisplayComponent;
