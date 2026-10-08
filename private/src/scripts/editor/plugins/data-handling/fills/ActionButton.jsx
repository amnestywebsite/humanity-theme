import AmnestyCandle from '../AmnestyCandle.jsx';

const { Button, Fill } = wp.components;
const { __ } = wp.i18n;

export default function ActionButton({ isPressed, onClick }) {
  return (
    <Fill name="PinnedItems/core">
      <Button
        className="amnesty-action-button"
        icon={AmnestyCandle}
        onClick={onClick}
        label={__('Metadata', 'amnesty')}
        isPressed={isPressed}
      />
    </Fill>
  );
}
