const { BlockIcon } = wp.blockEditor;
const { __ } = wp.i18n;

const Appender = ({ onClick }) => (
  <button className="add-more-button" onClick={onClick}>
    <BlockIcon icon="plus-alt" />
    <span>{/* translators: [admin] */ __('Add another item', 'amnesty')}</span>
  </button>
);

export default Appender;
