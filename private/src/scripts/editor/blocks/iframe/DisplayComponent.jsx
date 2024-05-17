import { httpsOnly } from '../../utils';

const { InspectorControls, RichText, BlockControls, AlignmentToolbar } = wp.blockEditor;
const { Button, PanelBody, Placeholder, TextControl } = wp.components;
const { Component, Fragment, createRef } = wp.element;
const { __ } = wp.i18n;

class DisplayComponent extends Component {
  constructor(...args) {
    super(...args);
    this.inputRef = createRef();
    this.updateEmbedUrl = this.createUpdateAttribute('embedUrl');
  }

  /**
   * Higher order component that takes the attribute key,
   * this then returns a function which takes a value,
   * when called it updates the attribute with the key.
   * @param key
   * @returns {function(*): *}
   */
  createUpdateAttribute = (key) => (value) => this.props.setAttributes({ [key]: value });

  onSubmit = (event) => {
    event.preventDefault();
    this.updateEmbedUrl(httpsOnly(this.inputRef.current.value));
  };

  doReset = (event) => {
    event.preventDefault();
    this.updateEmbedUrl('');
  };

  placeholder = () => {
    // translators: [admin]
    const label = __('Iframe URL', 'amnesty');

    return (
      <Placeholder label={label} className="wp-block-embed">
        <form onSubmit={this.onSubmit}>
          <input
            ref={this.inputRef}
            type="url"
            className="components-placeholder__input"
            aria-label={label}
            // translators: [admin]
            placeholder={__('Enter URL to embed here…', 'amnesty')}
          />
          <Button isLarge type="submit">
            {/* translators: [admin] */ __('Embed', 'amnesty')}
          </Button>
        </form>
      </Placeholder>
    );
  };

  embedContainer = () => {
    const { attributes, isSelected } = this.props;
    const width = parseInt(attributes.width, 10) || 0;
    const height = parseInt(attributes.height, 10) || 0;
    let minHeight = parseInt(attributes.minHeight, 10) || height;
    let minWidth = parseInt(attributes.minWidth, 10) || width;

    if (!width && !height) {
      minHeight = Math.max(minHeight, 350);
    }

    if (!width) {
      minWidth = `100%`;
    } else {
      minWidth = `${width}px`;
    }

    return (
      <figure className="wp-block-embed">
        <div
          className={`fluid-iframe ${attributes.alignment ? attributes.alignment : ''}`}
          style={{
            minHeight,
          }}
        >
          <iframe
            src={httpsOnly(attributes.embedUrl)}
            style={{
              height: `${minHeight}px`,
              width: minWidth,
            }}
          />
        </div>
        <div className={`iframe-caption ${attributes.alignment ? attributes.alignment : ''}`}>
          {(attributes.caption || isSelected) && (
            <RichText
              tagName="figcaption"
              // translators: [admin]
              placeholder={__('Write caption…', 'amnesty')}
              keepPlaceholderOnFocus={true}
              value={attributes.caption}
              onChange={this.createUpdateAttribute('caption')}
              inlineToolbar
              format="string"
            />
          )}
        </div>
      </figure>
    );
  };

  render() {
    const { attributes, setAttributes } = this.props;

    const { alignment } = attributes;

    const controls = (
      <InspectorControls>
        <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
          <TextControl
            // translators: [admin]
            label={__('Width', 'amnesty')}
            value={attributes.width}
            type="number"
            step={10}
            help={
              !attributes.width && attributes.height
                ? // translators: [admin]
                  __('Required when specifying a height', 'amnesty')
                : ''
            }
            onChange={this.createUpdateAttribute('width')}
          />
          <TextControl
            // translators: [admin]
            label={__('Height', 'amnesty')}
            value={attributes.height}
            type="number"
            step={10}
            help={
              !attributes.height && attributes.width
                ? // translators: [admin]
                  __('Required when specifying a width', 'amnesty')
                : ''
            }
            onChange={this.createUpdateAttribute('height')}
          />
          <TextControl
            // translators: [admin]
            label={__('Minimum Height', 'amnesty')}
            value={attributes.minHeight}
            type="number"
            step={10}
            min={0}
            max={1000}
            help={
              !attributes.height && !attributes.width
                ? // translators: [admin]
                  __('Required if not using width/height, optional otherwise', 'amnesty')
                : ''
            }
            onChange={this.createUpdateAttribute('minHeight')}
          />
          <hr />
          <TextControl
            // translators: [admin]
            label={__('Iframe Title', 'amnesty')}
            // translators: [admin]
            help={__('Set the text alternative for the iframe', 'amnesty')}
            value={attributes.title}
            onChange={this.createUpdateAttribute('title')}
          />
          <hr />
          {attributes.embedUrl && (
            <Button onClick={this.doReset} isPrimary>
              {/* translators: [admin] */ __('Reset Embed Url', 'amnesty')}
            </Button>
          )}
        </PanelBody>
      </InspectorControls>
    );

    return (
      <Fragment>
        {controls}
        <BlockControls>
          <AlignmentToolbar
            value={alignment}
            onChange={(newAlignments) => setAttributes({ alignment: newAlignments })}
          />
        </BlockControls>
        <div style={{ padding: '1px' }}>
          {attributes.embedUrl ? this.embedContainer() : this.placeholder()}
        </div>
      </Fragment>
    );
  }
}

export default DisplayComponent;
