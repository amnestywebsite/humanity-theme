import classnames from 'classnames';

const { InspectorControls, MediaUpload, PlainText, URLInputButton } = wp.blockEditor;
const { IconButton, PanelBody, SelectControl } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;

export default class BlockEdit extends Component {
  componentDidMount = () => {
    const { attributes, setAttributes } = this.props;
    const { imageID, imageURL, style } = attributes;

    if (!imageID) {
      return;
    }

    wp.apiRequest({ path: `wp/v2/media/${imageID}` }).then((data) => {
      const standardURL = data.media_details.sizes['action-wide'].source_url;
      const wideURL = data.media_details.sizes['lwi-block-md@2x'].source_url;
      if (style === 'standard' && imageURL !== standardURL) {
        setAttributes({ imageURL: standardURL });
      }

      if (style === 'wide' && imageURL !== wideURL) {
        setAttributes({ imageURL: wideURL });
      }
    });
  };

  componentDidUpdate = () => {
    const { attributes, setAttributes } = this.props;
    const { imageID, style } = attributes;
    if (!imageID) {
      return;
    }
    wp.apiRequest({ path: `wp/v2/media/${imageID}` }).then((data) => {
      if (style === 'standard') {
        setAttributes({ imageURL: data.media_details.sizes['action-wide'].source_url });
      } else if (style === 'wide') {
        setAttributes({ imageURL: data.media_details.sizes['lwi-block-md@2x'].source_url });
      }
    });
  };

  render() {
    const { attributes, setAttributes } = this.props;
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
    } = attributes;

    const classes = classnames('actionBlock', {
      'actionBlock--wide': style === 'wide',
      'is-centred': centred,
    });

    const buttonClasses = classnames('btn', 'btn--fill', 'btn--large');
    const setURL = (image) => {
      if (!image.sizes || !Object.prototype.hasOwnProperty.call(image.sizes, 'action-wide')) {
        return image.url;
      }
      const standardIMG = 'action-wide';

      return image.sizes[standardIMG].url;
    };
    const setLargeURL = (image) => {
      if (!image.sizes || !Object.prototype.hasOwnProperty.call(image.sizes, 'lwi-block-md@2x')) {
        return image.url;
      }
      const largeIMG = 'lwi-block-md@2x';
      return image.sizes[largeIMG].url;
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
          </PanelBody>
        </InspectorControls>
        <figure className={classes}>
          <div className="actionBlock-figure">
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
            {imageURL && <img className="actionBlock-image" src={imageURL} alt={imageAlt} />}
            <PlainText
              className="actionBlock-label"
              rows="1"
              // translators: [admin]
              placeholder={__('(Label)', 'amnesty')}
              value={label}
              onChange={(newLabel) => setAttributes({ label: newLabel })}
            />
          </div>
          <figcaption className="actionBlock-content">
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
