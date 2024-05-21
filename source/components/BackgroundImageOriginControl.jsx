const { SelectControl } = wp.components;
const { __ } = wp.i18n;

const BackgroundImageOriginControl = ({ origin, setOrigin }) => {
  return (
    <SelectControl
      // translators: [admin]
      label={__('Background Image Origin', 'amnesty')}
      options={[
        // translators: [admin]
        { label: __('Top', 'amnesty'), value: 'top' },
        // translators: [admin]
        { label: __('Right', 'amnesty'), value: 'right' },
        // translators: [admin]
        { label: __('Bottom', 'amnesty'), value: 'bottom' },
        // translators: [admin]
        { label: __('Left', 'amnesty'), value: 'left' },
        // translators: [admin]
        { label: __('Centre', 'amnesty'), value: 'center' },
        // translators: [admin]
        { label: __('Initial', 'amnesty'), value: 'initial' },
      ]}
      value={origin}
      onChange={setOrigin}
    />
  );
};

export default BackgroundImageOriginControl;
