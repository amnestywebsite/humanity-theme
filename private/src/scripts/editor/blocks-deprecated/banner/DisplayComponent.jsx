import classnames from 'classnames';
import PostFeaturedVideo from '../../components/PostFeaturedVideo.jsx';

const { InspectorControls, MediaUpload, RichText, URLInputButton } = wp.blockEditor;
const { IconButton, PanelBody, SelectControl, TextControl, ToggleControl } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;

export default class DisplayComponent extends Component {
  state = {
    imageData: null,
    videoUrl: false,
  };

  componentDidMount() {
    const { imageID, type, featuredVideoId } = this.props.attributes;

    if (type === 'video' && !this.state.videoUrl && featuredVideoId) {
      this.fetchVideoUrl();
    }

    if (type !== 'video' && !this.state.imageData && imageID) {
      this.fetchImageData();
    }
  }

  componentDidUpdate(prevProps) {
    const { imageID, type, featuredVideoId } = this.props.attributes;

    if (type === 'video' && !this.state.videoUrl && featuredVideoId) {
      this.fetchVideoUrl();
    }

    if (type !== 'video' && imageID !== prevProps.imageID) {
      this.fetchImageData();
    }
  }

  fetchImageData = () => {
    const { imageID } = this.props.attributes;
    const cached = this.state?.imageData?.id;

    if (cached && cached === imageID) {
      return;
    }

    wp.apiRequest({
      path: `/wp/v2/media/${imageID}?_fields=id,description,caption&context=edit`,
    }).then((resp) => {
      this.setState({
        imageData: {
          id: resp.id,
          caption: resp.caption.raw,
          description: resp.description.raw,
        },
      });
    });
  };

  fetchVideoUrl = () => {
    const { featuredVideoId } = this.props.attributes;

    wp.apiRequest({
      path: `/wp/v2/media/${featuredVideoId}`,
    }).then((resp) => {
      this.setState({
        videoUrl: resp.source_url,
      });
    });
  };

  static setImageUrl = (image) => {
    if (!image.sizes || !Object.hasOwnProperty.call(image.sizes, 'large')) {
      return image.url;
    }

    return image.sizes.large.url;
  };

  render() {
    const { attributes = {}, setAttributes } = this.props;
    const { videoUrl } = this.state;
    const {
      type = 'image',
      size = false,
      background = false,
      alignment = false,

      content,
      ctaLink,
      ctaText,
      embed,
      featuredVideoId,
      imageID,
      imageURL,
      title,
    } = attributes;

    const classes = classnames('page-hero', 'headerBlock', {
      'page-heroSize--full': !size,
      'page-heroBackground--transparent': !background,
      'page-heroAlignment--left': !alignment,
      'page-hero--video': type === 'video',
      [`page-heroSize--${size}`]: size,
      [`page-heroBackground--${background}`]: background,
      [`page-heroAlignment--${alignment}`]: alignment,
    });

    const shouldShowImageCaption =
      this.state.imageData?.caption &&
      !attributes.hideImageCaption &&
      this.state.imageData?.caption !== this.state.imageData?.description;

    const shouldShowImageCredit =
      this.state.imageData?.description && !attributes.hideImageCopyright;

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
            <SelectControl
              // translators: [admin]
              label={__('Alignment', 'amnesty')}
              options={[
                {
                  /* translators: [admin] text alignment. for RTL languages, localise as 'Right' */
                  label: __('Left', 'amnesty'),
                  value: 'left',
                },
                {
                  // translators: [admin] text alignment.
                  label: __('Centre', 'amnesty'),
                  value: 'center',
                },
                {
                  /* translators: [admin] text alignment. for RTL languages, localise as 'Left' */
                  label: __('Right', 'amnesty'),
                  value: 'right',
                },
              ]}
              value={alignment}
              onChange={(newAlignment) => setAttributes({ alignment: newAlignment })}
            />
            <SelectControl
              // translators: [admin]
              label={__('Background Colour', 'amnesty')}
              options={[
                // translators: [admin]
                { value: '', label: __('Translucent black', 'amnesty') },
                // translators: [admin]
                { value: 'none', label: __('None', 'amnesty') },
                // translators: [admin]
                { value: 'light', label: __('White', 'amnesty') },
                // translators: [admin]
                { value: 'dark', label: __('Black', 'amnesty') },
              ]}
              value={background}
              onChange={(newBackground) => setAttributes({ background: newBackground })}
            />
            <SelectControl
              // translators: [admin]
              label={__('Size', 'amnesty')}
              options={[
                // translators: [admin]
                { value: '', label: __('Normal', 'amnesty') },
                // translators: [admin]
                { value: 'small', label: __('Small', 'amnesty') },
              ]}
              value={size}
              onChange={(newSize) => setAttributes({ size: newSize })}
            />
            <SelectControl
              // translators: [admin]
              label={__('Background Type', 'amnesty')}
              options={[
                // translators: [admin]
                { value: '', label: __('Image', 'amnesty') },
                // translators: [admin]
                { value: 'video', label: __('Video', 'amnesty') },
              ]}
              value={type}
              onChange={(newType) => setAttributes({ type: newType })}
            />
            {type !== 'video' && (
              <>
                <ToggleControl
                  // translators: [admin]
                  label={__('Hide Image Caption', 'amnesty')}
                  checked={attributes.hideImageCaption}
                  onChange={(hideImageCaption) => setAttributes({ hideImageCaption })}
                />
                <ToggleControl
                  // translators: [admin]
                  label={__('Hide Image Credit', 'amnesty')}
                  checked={attributes.hideImageCopyright}
                  onChange={(hideImageCopyright) => setAttributes({ hideImageCopyright })}
                />
              </>
            )}
            <TextControl
              // translators: [admin]
              label={__('Embed URL', 'amnesty')}
              // translators: [admin]
              help={__(
                'Setting this will override the call to action link and will now open a modal with the embed.',
                'amnesty',
              )}
              value={embed}
              onChange={(newEmbed) => setAttributes({ embed: newEmbed })}
            />
          </PanelBody>
          {type === 'video' && (
            <PanelBody title={/* translators: [admin] */ __('Featured Video', 'amnesty')}>
              <PostFeaturedVideo
                featuredVideoId={featuredVideoId}
                onUpdate={(newVideoID) => setAttributes({ featuredVideoId: newVideoID })}
              />
            </PanelBody>
          )}
        </InspectorControls>
        <section className={classes} style={{ backgroundImage: `url(${imageURL})` }}>
          {type !== 'video' && (
            <div className="linkList-options">
              {imageID ? (
                <IconButton
                  icon="no-alt"
                  // translators: [admin]
                  label={__('Remove Image', 'amnesty')}
                  onClick={() => setAttributes({ imageID: 0, imageURL: '' })}
                />
              ) : (
                <MediaUpload
                  allowedTypes={['image']}
                  value={imageID}
                  onSelect={(media) =>
                    setAttributes({
                      imageID: media.id,
                      imageURL: DisplayComponent.setImageUrl(media),
                    })
                  }
                  render={({ open }) => <IconButton icon="format-image" onClick={open} />}
                />
              )}
            </div>
          )}

          {videoUrl && (
            <div className="page-heroVideoContainer">
              <video className="page-heroVideo">
                <source src={videoUrl} />
              </video>
            </div>
          )}
          <div className="container">
            <div className="hero-content">
              <RichText
                tagName="h1"
                className="page-heroTitle"
                value={title}
                // translators: [admin]
                placeholder={__('(Banner Title)', 'amnesty')}
                keepPlaceholderOnFocus={true}
                format="string"
                onChange={(newTitle) => setAttributes({ title: newTitle })}
              />
              <RichText
                tagName="p"
                className="page-heroContent"
                value={content}
                // translators: [admin]
                placeholder={__('(Banner Content)', 'amnesty')}
                keepPlaceholderOnFocus={true}
                format="string"
                onChange={(newContent) => setAttributes({ content: newContent })}
              />
              <div className="page-heroCta">
                <div className="btn btn--large">
                  {embed && <i className="play-icon"></i>}
                  <RichText
                    tagName="span"
                    value={ctaText}
                    // translators: [admin]
                    placeholder={__('(Call to action)', 'amnesty')}
                    keepPlaceholderOnFocus={true}
                    format="string"
                    onChange={(newCtaText) => setAttributes({ ctaText: newCtaText })}
                  />
                  {(!embed || embed.length < 1) && (
                    <URLInputButton
                      url={ctaLink}
                      onChange={(newCtaLink) => setAttributes({ ctaLink: newCtaLink })}
                    />
                  )}
                </div>
              </div>
            </div>
          </div>
          {this.state.imageData && (
            <div className="image-metadata">
              {shouldShowImageCaption && (
                <span className="image-metadataItem image-caption">
                  {this.state.imageData.caption}
                </span>
              )}
              {shouldShowImageCredit && (
                <span className="image-metadataItem image-copyright">
                  {this.state.imageData.description}
                </span>
              )}
            </div>
          )}
        </section>
      </Fragment>
    );
  }
}
