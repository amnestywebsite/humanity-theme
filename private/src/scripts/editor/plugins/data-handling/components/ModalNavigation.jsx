const {
  // eslint-disable-next-line @wordpress/no-unsafe-wp-apis
  __experimentalToggleGroupControl: ToggleGroupControl,
  // eslint-disable-next-line @wordpress/no-unsafe-wp-apis
  __experimentalToggleGroupControlOption: ToggleGroupControlOption,
} = wp.components;
const { __ } = wp.i18n;

export default function ModalNavigation({ items, current, onChange }) {
  return (
    <ToggleGroupControl
      __next40pxDefaultSize
      isBlock
      isDeselectable={false}
      label={__('Options', 'amnesty')}
      hideLabelFromVision
      value={current}
      onChange={onChange}
      style={{ marginBlockEnd: '50px' }}
    >
      {items.map(({ value, label }) => (
        <ToggleGroupControlOption key={value} value={value} label={label} />
      ))}
    </ToggleGroupControl>
  );
}
