import classnames from 'classnames';

const { isInteger } = lodash;
const { BlockAlignmentToolbar, BlockControls, InspectorControls } = wp.blockEditor;
const { Button, RangeControl, TextControl, ToolbarGroup, PanelBody } = wp.components;
const { useEffect, useRef, useState } = wp.element;
const { __ } = wp.i18n;

const toRawNumber = (value = '0') => {
  if (isInteger(value)) {
    return value;
  }

  const trimmed = value.replace(/[^\d]/g, '');
  const inted = parseInt(trimmed, 10);

  return inted;
};

const toFormattedString = (value) => {
  if (!value) {
    return '';
  }

  const { currentLocale = 'en-GB' } = window.amnestyCoreI18n;
  const formatted = toRawNumber(value).toLocaleString(currentLocale.replace('_', '-'));

  return formatted;
};

const edit = ({ attributes, setAttributes }) => {
  const [preview, setPreviewing] = useState(false);
  const [current, setCurrent] = useState(0);
  const [progress, setProgress] = useState(0);

  const countUp = () => {
    const duration = Math.abs(attributes.duration * 1000);
    const end = toRawNumber(attributes.value);
    let startTime = null;

    const step = (timestamp) => {
      if (!startTime) {
        startTime = timestamp;
      }

      const newProgress = Math.min((timestamp - startTime) / duration, 1);
      const newCurrent = Math.floor(progress * end);

      setCurrent(newCurrent);
      setProgress(newProgress);

      if (progress < 1) {
        requestAnimationFrame(step);
      }
    };

    requestAnimationFrame(step);
  };

  const mounted = useRef();
  useEffect(() => {
    if (!mounted?.current) {
      mounted.current = true;

      const number = toRawNumber(attributes.value);

      if (number > 0) {
        setPreviewing(true);
        countUp();
      }
    }
  }, []);

  const togglePreview = () => {
    setCurrent(0);
    setProgress(0);
    setPreviewing(!preview);

    const willBePreviewing = !preview;

    if (willBePreviewing) {
      countUp();
    }
  };

  const blockClasses = classnames(className, {
    [`align${attributes.alignment}`]: !!attributes.alignment,
  });

  // translators: [admin]
  const buttonLabel = preview ? __('Edit Counter', 'amnesty') : __('Preview Counter', 'amnesty');

  return (
    <>
      <InspectorControls>
        <PanelBody>
          <RangeControl
            // translators: [admin]
            label={__('Duration', 'amnesty')}
            // translators: [admin]
            help={__('How long it should take the counter to count up', 'amnesty')}
            min={1}
            max={5}
            step={0.5}
            value={attributes.duration}
            onChange={(duration) => setAttributes({ duration })}
          />
        </PanelBody>
      </InspectorControls>
      <BlockControls>
        <BlockAlignmentToolbar
          value={attributes.alignment}
          onChange={(alignment) => setAttributes({ alignment })}
        />
        <ToolbarGroup>
          <Button label={attributes.buttonLabel} onClick={togglePreview}>
            {preview
              ? /* translators: [admin] */ __('Edit', 'amnesty')
              : /* translators: [admin] */ __('Preview', 'amnesty')}
          </Button>
        </ToolbarGroup>
      </BlockControls>
      <div className={blockClasses}>
        {!preview && (
          <TextControl
            // translators: [admin]
            label={__('Enter the value to which this field should count', 'amnesty')}
            value={toFormattedString(attributes.value)}
            onChange={(value) => setAttributes({ value: toFormattedString(value) })}
            placeholder={0}
          />
        )}
        {preview && (
          <div className="preview" style={{ opacity: progress }}>
            {toFormattedString(current)}
          </div>
        )}
      </div>
    </>
  );
};

export default edit;
