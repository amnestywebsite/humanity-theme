import classnames from 'classnames';

const { InspectorControls, RichText } = wp.blockEditor;
const { PanelBody, SelectControl, ToggleControl } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;

class DisplayComponent extends Component {
  static isRightToLeft = document.documentElement.getAttribute('dir') === 'rtl';

  constructor(...args) {
    super(...args);
    this.hasI18n = Object.prototype.hasOwnProperty.call(window, 'amnestyCoreI18n');
  }

  getDirections() {
    const directionalOptions = [
      /* translators: [admin] text alignment. for RTL languages, localise as 'Right' */
      { value: 'start', label: __('Left', 'amnesty') },
      /* translators: [admin] text alignment */
      { value: '', label: __('Default', 'amnesty') },
    ];

    if (!this.isRightToLeft) {
      /* translators: [admin] text alignment. for RTL languages, localise as 'Left' */
      directionalOptions.push({ value: 'end', label: __('Right', 'amnesty') });
    }

    return directionalOptions;
  }

  getQuoteStyles() {
    if (!this.hasI18n) {
      return '';
    }

    const { openDoubleQuote, closeDoubleQuote, openSingleQuote, closeSingleQuote } =
      window.amnestyCoreI18n;

    return `.blockquote {
      quotes: '${openDoubleQuote}' '${closeDoubleQuote}' "${openSingleQuote}" "${closeSingleQuote}";
    }`;
  }

  render() {
    const { attributes, setAttributes } = this.props;
    const {
      align = '',
      size = '',
      colour = '',
      capitalise = false,
      lined = true,
      content = '',
      citation = '',
    } = attributes;

    const classes = classnames('blockquote', {
      [`align-${align}`]: !!align,
      [`is-${size}`]: !!size,
      [`is-${colour}`]: !!colour,
      'is-capitalised': capitalise,
      'is-lined': lined,
    });

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody>
            <SelectControl
              // translators: [admin]
              label={__('Alignment', 'amnesty')}
              value={align}
              onChange={(newAlign) => setAttributes({ align: newAlign })}
              options={this.getDirections()}
            />
            <SelectControl
              // translators: [admin]
              label={__('Size', 'amnesty')}
              value={size}
              onChange={(newSize) => setAttributes({ size: newSize })}
              options={[
                // translators: [admin]
                { value: 'small', label: __('Small', 'amnesty') },
                // translators: [admin]
                { value: 'medium', label: __('Medium', 'amnesty') },
                // translators: [admin]
                { value: '', label: __('Large', 'amnesty') },
              ]}
            />
            <SelectControl
              // translators: [admin]
              label={__('Text Colour', 'amnesty')}
              value={colour}
              onChange={(newColour) => setAttributes({ colour: newColour })}
              options={[
                // translators: [admin]
                { value: '', label: __('Black', 'amnesty') },
                // translators: [admin]
                { value: 'grey', label: __('Grey', 'amnesty') },
                // translators: [admin]
                { value: 'white', label: __('White', 'amnesty') },
              ]}
            />
            {!this.isRightToLeft && (
              <ToggleControl
                // translators: [admin]
                label={__('Capitalise', 'amnesty')}
                // translators: [admin]
                help={__('Capitalise the content.', 'amnesty')}
                checked={capitalise}
                onChange={(newCaps) => setAttributes({ capitalise: newCaps })}
              />
            )}
            <ToggleControl
              // translators: [admin]
              label={__('Line', 'amnesty')}
              // translators: [admin]
              help={__('Toggle display of line embellishment.', 'amnesty')}
              checked={lined}
              onChange={(newLine) => setAttributes({ lined: newLine })}
            />
          </PanelBody>
        </InspectorControls>
        <style>{this.getQuoteStyles()}</style>
        <div className={classes}>
          <div>
            <RichText
              tagName="p"
              // translators: [admin]
              placeholder={__('(Insert Quote Text)', 'amnesty')}
              value={content}
              allowedFormats={[]}
              onChange={(newContent) => setAttributes({ content: newContent })}
            />
          </div>
          <div>
            <RichText
              tagName="cite"
              // translators: [admin]
              placeholder={__('(Insert Citation)', 'amnesty')}
              value={citation}
              allowedFormats={[]}
              onChange={(newCitation) => setAttributes({ citation: newCitation })}
            />
          </div>
        </div>
      </Fragment>
    );
  }
}

export default DisplayComponent;
