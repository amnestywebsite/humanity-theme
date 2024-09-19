/**
 * Third-party
 */
import classnames from 'classnames';

/**
 * WordPress
 */
const { pick } = lodash;
const { React } = window;
const { InspectorControls, MediaUpload, RichText, URLInputButton } = wp.blockEditor;
const { Button, CheckboxControl, PanelBody, SelectControl, RangeControl } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
export default class BlockEdit extends Component {
  constructor(...params) {
    super(...params);

    this.bigTextRef = React.createRef();
  }

  componentDidMount() {
    const { attributes, setAttributes } = this.props;
    const { style } = attributes;

    if (style === 'square') {
      setAttributes({
        buttonText: document.body.classList.contains('rtl') ? '&larr;' : '&rarr;',
        iconSize: 'xsmall',
        imageLocation: 'top',
      });
    }

    if (style === 'default' || style === '') {
      setAttributes({
        style: 'icon',
      });
    }
  }

  componentDidUpdate() {
    if (['', 'default'].indexOf(this.props.attributes.style) !== -1) {
      this.props.setAttributes({ style: 'icon' });
    }
  }

  getImageButton = (openEvent) => {
    const { attributes } = this.props;
    const { imageID, imageData, iconSize = 'medium' } = attributes;

    if (imageID && imageData) {
      const imgClasses = classnames('linksWithIcons-imageWrapper', {
        [`is-${iconSize}`]: iconSize !== 'medium',
      });

      let src = imageData?.full?.url;
      if (Object.prototype.hasOwnProperty.call(imageData, 'lwi-block-sm')) {
        src = imageData['lwi-block-sm'].url;
      }

      return (
        <div className={imgClasses}>
          <img className="linksWithIcons-image" src={src} onClick={openEvent} />
        </div>
      );
    }

    return (
      <div className="linksWithIcons-uploadContainer">
        <Button className="button button-large" onClick={openEvent}>
          {/* translators: [admin] */ __('Pick an image', 'amnesty')}
        </Button>
      </div>
    );
  };

  getOptionalField = () => {
    const { attributes, setAttributes } = this.props;
    const { style = 'icon', underlined = false, imageID, bigtext, bigTextCss } = attributes;

    if (style === 'text') {
      const txtClasses = classnames('linksWithIcons-bigtext', {
        'has-underline': underlined,
      });

      return (
        <RichText
          className={txtClasses}
          // translators: [admin]
          placeholder={__('(Insert Fact)', 'amnesty')}
          value={bigtext}
          allowedFormats={[]}
          onChange={(newBigtext) => setAttributes({ bigtext: newBigtext })}
          style={bigTextCss}
          ref={this.bigTextRef}
        />
      );
    }

    if (style !== 'none') {
      return (
        <MediaUpload
          className="linksWithIcons-upload"
          allowedTypes={['image']}
          value={imageID}
          render={({ open }) => this.getImageButton(open)}
          onSelect={(media) =>
            setAttributes({
              imageID: media.id,
              imageAlt: media.alt,
              imageData: pick(media.sizes, [
                'full',
                'lwi-block-sm',
                'lwi-block-md',
                'lwi-block-lg',
                'lwi-block-sm@2x',
                'lwi-block-md@2x',
                'lwi-block-lg@2x',
                'logomark',
              ]),
            })
          }
        />
      );
    }

    return null;
  };

  renderControls() {
    const { attributes, setAttributes } = this.props;
    const {
      buttonStyle = 'white',
      hasButton = true,
      iconSize = 'medium',
      imageLocation = '',
      style = 'icon',
      uncredited = false,
      underlined = false,
      bigTextCss,
    } = attributes;

    let factFontSize = bigTextCss.fontSize;
    if (typeof factFontSize === 'string') {
      factFontSize = parseInt(bigTextCss.fontSize, 10);
    }

    return (
      <InspectorControls>
        <PanelBody>
          <SelectControl
            // translators: [admin]
            label={__('Style', 'amnesty')}
            value={style}
            onChange={(newStyle) => setAttributes({ style: newStyle })}
            options={[
              // translators: [admin]
              { value: 'icon', label: __('Use Image', 'amnesty') },
              // translators: [admin]
              { value: 'text', label: __('Use Text', 'amnesty') },
              // translators: [admin]
              { value: 'none', label: __('Plain', 'amnesty') },
            ]}
          />
          {['icon', 'square'].includes(style) && (
            <SelectControl
              // translators: [admin]
              label={__('Icon Size', 'amnesty')}
              value={iconSize}
              onChange={(newSize) => setAttributes({ iconSize: newSize })}
              options={[
                // translators: [admin]
                { value: 'xsmall', label: __('Extra Small', 'amnesty') },
                // translators: [admin]
                { value: 'small', label: __('Small', 'amnesty') },
                // translators: [admin]
                { value: 'medium', label: __('Medium', 'amnesty') },
                // translators: [admin]
                { value: 'large', label: __('Large', 'amnesty') },
              ]}
            />
          )}
          {['icon', 'square'].includes(style) && (
            <SelectControl
              // translators: [admin]
              label={__('Icon Position', 'amnesty')}
              value={imageLocation}
              onChange={(newImageLocation) => setAttributes({ imageLocation: newImageLocation })}
              options={[
                // translators: [admin]
                { value: '', label: __('Middle', 'amnesty') },
                // translators: [admin]
                { value: 'top', label: __('Top', 'amnesty') },
              ]}
            />
          )}
          {style === 'icon' && (
            <CheckboxControl
              // translators: [admin]
              label={__('Hide Image Credit Display', 'amnesty')}
              checked={uncredited}
              onChange={(newCredit) => setAttributes({ uncredited: newCredit })}
            />
          )}
          {style === 'text' && (
            <Fragment>
              <CheckboxControl
                // translators: [admin]
                label={__('Has Underline', 'amnesty')}
                checked={underlined}
                onChange={(newUnderline) => setAttributes({ underlined: newUnderline })}
              />
              <RangeControl
                // translators: [admin]
                label={__('Font Size', 'amnesty')}
                value={factFontSize}
                onChange={(customFontSize) =>
                  setAttributes({ bigTextCss: { fontSize: customFontSize } })
                }
                min={30}
                max={120}
              />
            </Fragment>
          )}
          <CheckboxControl
            // translators: [admin]
            label={__('Display Action', 'amnesty')}
            checked={hasButton}
            onChange={(newHasButton) => setAttributes({ hasButton: newHasButton })}
          />
          {hasButton && style !== 'square' && (
            <SelectControl
              // translators: [admin]
              label={__('Button Style', 'amnesty')}
              options={[
                // translators: [admin]
                { label: __('Primary (Yellow)', 'amnesty'), value: 'primary' },
                // translators: [admin]
                { label: __('Dark', 'amnesty'), value: 'dark' },
                // translators: [admin]
                { label: __('Light', 'amnesty'), value: 'white' },
              ]}
              value={buttonStyle}
              onChange={(newButtonStyle) => setAttributes({ buttonStyle: newButtonStyle })}
            />
          )}
        </PanelBody>
      </InspectorControls>
    );
  }

  renderAsSquare() {
    const { attributes, setAttributes } = this.props;
    const {
      hasButton = true,
      imageLocation = 'top',

      title,
      buttonText,
      buttonLink,
    } = attributes;

    return (
      <Fragment>
        {this.renderControls()}
        <div className="linksWithIcons">
          {imageLocation === 'top' && this.getOptionalField()}
          <RichText
            className="linksWithIcons-title"
            // translators: [admin]
            placeholder={__('(Insert Title)', 'amnesty')}
            value={title}
            format="string"
            allowedFormats={[]}
            onChange={(newTitle) => setAttributes({ title: newTitle })}
          />
          {imageLocation !== 'top' && this.getOptionalField()}
          {hasButton && (
            <div className="linksWithIcons-buttonWrapper">
              <span dangerouslySetInnerHTML={{ __html: buttonText }} />
              <URLInputButton
                url={buttonLink}
                onChange={(newLink) => setAttributes({ buttonLink: newLink })}
              />
            </div>
          )}
        </div>
        <div className="linksWithIcons-spacer"></div>
      </Fragment>
    );
  }

  render() {
    const { attributes, setAttributes } = this.props;
    const {
      hasButton = true,
      buttonStyle = 'white',
      imageLocation = '',
      style = 'icon',

      title,
      body,
      buttonText,
      buttonLink,
    } = attributes;

    if (style === 'square') {
      return this.renderAsSquare();
    }

    return (
      <Fragment>
        {this.renderControls()}
        <div className="linksWithIcons">
          {imageLocation === 'top' && this.getOptionalField()}
          <RichText
            className="linksWithIcons-title"
            // translators: [admin]
            placeholder={__('(Insert Title)', 'amnesty')}
            value={title}
            format="string"
            allowedFormats={[]}
            onChange={(newTitle) => setAttributes({ title: newTitle })}
          />
          {imageLocation !== 'top' && this.getOptionalField()}
          <RichText
            className="linksWithIcons-body"
            // translators: [admin]
            placeholder={__('(Insert Body Text)', 'amnesty')}
            value={body}
            format="string"
            allowedFormats={[]}
            onChange={(newBody) => setAttributes({ body: newBody })}
          />
          {hasButton && (
            <div className="linksWithIcons-buttonWrapper">
              <RichText
                className={classnames('btn', `btn--${buttonStyle}`)}
                tagName="span"
                // translators: [admin]
                placeholder={__('(Insert Link Text)', 'amnesty')}
                value={buttonText}
                format="string"
                allowedFormats={[]}
                onChange={(newText) => setAttributes({ buttonText: newText })}
              />
              <URLInputButton
                url={buttonLink}
                onChange={(newLink) => setAttributes({ buttonLink: newLink })}
              />
            </div>
          )}
        </div>
        <div className="linksWithIcons-spacer"></div>
      </Fragment>
    );
  }
}
