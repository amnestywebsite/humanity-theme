import classnames from 'classnames';

const { isInteger } = lodash;
const { BlockAlignmentToolbar, BlockControls, InspectorControls } = wp.blockEditor;
const { Button, RangeControl, TextControl, ToolbarGroup, PanelBody } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { currentLocale = 'en-GB', enforceGroupingSeparators } = window.amnestyCoreI18n;

const toRawNumber = (value = '0') => {
  if (isInteger(value)) {
    return value;
  }

  const trimmed = value.replace(/[^\d]/g, '');
  const inted = parseInt(trimmed, 10);

  return inted;
};

// format a value as a locale-aware number
const toFormattedString = (value) => {
  if (!value) {
    return '';
  }

  const options = {};

  if (enforceGroupingSeparators) {
    options.useGrouping = true;
  }

  const formatted = toRawNumber(value).toLocaleString(currentLocale.replace('_', '-'), options);

  return formatted;
};

export default class DisplayComponent extends Component {
  state = {
    preview: false,
    current: 0,
    progress: 0,
  };

  componentDidMount() {
    const { value } = this.props.attributes;
    const number = toRawNumber(value);

    if (number > 0) {
      this.setState({ preview: true });
      this.countUp();
    }
  }

  togglePreview = () => {
    this.setState(
      {
        preview: !this.state.preview,
        current: 0,
        progress: 0,
      },
      () => {
        if (this.state.preview) {
          this.countUp();
        }
      },
    );
  };

  countUp = () => {
    const duration = Math.abs(this.props.attributes.duration * 1000);
    const end = toRawNumber(this.props.attributes.value);
    let startTime = null;

    const step = (timestamp) => {
      if (!startTime) {
        startTime = timestamp;
      }

      const progress = Math.min((timestamp - startTime) / duration, 1);
      const current = Math.floor(progress * end);

      this.setState({ current, progress });

      if (progress < 1) {
        requestAnimationFrame(step);
      }
    };

    requestAnimationFrame(step);
  };

  render() {
    const { current, preview, progress } = this.state;
    const { attributes, className, setAttributes } = this.props;

    const blockClasses = classnames(className, {
      [`align${attributes.alignment}`]: !!attributes.alignment,
    });

    // translators: [admin]
    const buttonLabel = preview ? __('Edit Counter', 'amnesty') : __('Preview Counter', 'amnesty');

    return (
      <Fragment>
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
            <Button label={buttonLabel} onClick={this.togglePreview}>
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
      </Fragment>
    );
  }
}
