import classnames from 'classnames';

const { InspectorControls, MediaUpload, PlainText, URLInputButton } = wp.blockEditor;
const { IconButton, PanelBody, SelectControl, TextControl } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;

export default class BlockEdit extends Component {
  render() {
    const { attributes, setAttributes, className } = this.props;
    const {
      style = 'standard',
      centred = false,
      content,
      imageAlt,
      imageID,
      imageURL,
      label,
      link,
      linkText,
      largeImageURL,
    } = attributes;

    const createUpdateAttribute = (key) => (value) => this.props.setAttributes({ [key]: value });

    classnames('callToAction', {
      [`callToAction--${attributes.background}`]: attributes.background,
    });

    const classes = classnames('customCard', {
      'customCard--wide': style === 'wide',
      'is-centred': centred,
      [`${className}`]: className,
    });

    const buttonClasses = classnames('btn', 'btn--fill', 'btn--large');

    const setURL = (image) => {
      if (!image.sizes || !Object.prototype.hasOwnProperty.call(image.sizes, 'large')) {
        return image.url;
      }

      return image.sizes.large.url;
    };

    const setLargeURL = (image) => {
      if (!image.sizes || !Object.prototype.hasOwnProperty.call(image.sizes, 'action-wide@2x')) {
        return image.url;
      }

      return image.sizes['action-wide@2x'].url;
    };

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody>
            <SelectControl
              // translators: [admin]
              label={__('Size', 'amnesty')}
              value={style}
              onChange={(newStyle) => setAttributes({ style: newStyle })}
              options={[
                // translators: [admin]
                { value: 'standard', label: __('Standard', 'amnesty') },
                // translators: [admin]
                { value: 'wide', label: __('Wide', 'amnesty') },
              ]}
            />
            <TextControl
              // translators: [admin]
              label={__('Scroll To Link', 'amnesty')}
              value={attributes.scrollLink}
              onChange={createUpdateAttribute('scrollLink')}
            />
          </PanelBody>
        </InspectorControls>
        <figure className={classes}>
          <PlainText
            className="customCard-label"
            rows="1"
            // translators: [admin]
            placeholder={__('(Label)', 'amnesty')}
            value={label}
            onChange={(newLabel) => setAttributes({ label: newLabel })}
          />
          <div className="customCard-figure">
            <div className="linkList-options">
              {imageID ? (
                <IconButton
                  icon="no-alt"
                  // translators: [admin]
                  label={__('Remove Image', 'amnesty')}
                  onClick={() => setAttributes({ imageID: 0, imageURL: '', imageAlt: '' })}
                />
              ) : (
                <MediaUpload
                  allowedTypes={['image']}
                  value={imageID}
                  onSelect={(media) =>
                    setAttributes({
                      imageID: media.id,
                      imageURL: setURL(media),
                      largeImageURL: setLargeURL(media),
                      imageAlt: media.alt,
                    })
                  }
                  render={({ open }) => <IconButton icon="format-image" onClick={open} />}
                />
              )}
            </div>
            {style === 'wide' && (
              <img className="customCard-image" src={largeImageURL} alt={imageAlt} />
            )}
            {style === 'standard' && (
              <img className="customCard-image" src={imageURL} alt={imageAlt} />
            )}
          </div>
          <figcaption className="customCard-content">
            <PlainText
              // translators: [admin]
              placeholder={__('Content', 'amnesty')}
              rows="3"
              value={content}
              onChange={(newContent) => setAttributes({ content: newContent })}
            />
            <PlainText
              className={buttonClasses}
              // translators: [admin]
              placeholder={__('Button Text', 'amnesty')}
              rows="1"
              value={linkText}
              onChange={(newLinkText) => setAttributes({ linkText: newLinkText })}
            />
            <URLInputButton url={link} onChange={(newLink) => setAttributes({ link: newLink })} />
          </figcaption>
        </figure>
      </Fragment>
    );
  }
}
