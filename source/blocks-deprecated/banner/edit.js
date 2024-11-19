import classnames from 'classnames';
import MediaMetadata from './components/MediaMetadata.jsx';
import MediaMetadataVisibilityControls from './components/MediaMetadataVisibilityControls.jsx';
import PostFeaturedVideo from './components/PostFeaturedVideo.jsx';

const { InspectorControls, MediaUpload, RichText, URLInputButton } = wp.blockEditor;
const { IconButton, PanelBody, SelectControl, TextControl } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { addQueryArgs } = wp.url;

export default class DisplayComponent extends Component {
  state = {
    imageData: null,
    videoData: null,
  };

  fetchMediaMetadata = (id, type) => {
    const key = `${type}Data`;
    const cached = this.state[key]?.id;

    if (id === 0) {
      if (cached) {
        this.setState({ [key]: null });
      }
      return;
    }

    if (cached && cached === id) {
      return;
    }

    const path = addQueryArgs(`/wp/v2/media/${id}`, {
      context: 'edit',
      _fields: 'id,source_url,caption,description',
    });

    wp.apiRequest({ path }).then((resp) => {
      this.setState({
        [key]: {
          id: resp.id,
          url: resp.source_url,
          caption: resp.caption.raw,
          description: resp.description.raw,
        },
      });
    });
  };

  componentDidMount() {
    const { imageID, type, featuredVideoId } = this.props.attributes;

    if (type === 'video' && !this.state.videoData?.url && featuredVideoId) {
      this.fetchMediaMetadata(featuredVideoId, 'video');
    }

    if (type !== 'video' && !this.state.imageData && imageID) {
      this.fetchMediaMetadata(imageID, 'image');
    }
  }

  componentDidUpdate(prevProps) {
    const { imageID, type, featuredVideoId } = this.props.attributes;

    if (type === 'video' && !this.state.videoData?.url && featuredVideoId) {
      this.fetchMediaMetadata(featuredVideoId, 'video');
    }

    if (
      type !== 'video' &&
      (imageID !== prevProps.imageID || this.state.imageData?.id !== imageID)
    ) {
      this.fetchMediaMetadata(imageID, 'image');
    }
  }

  render() {
    const { attributes = {}, setAttributes } = this.props;
    const {
      size = false,
      background = false,
      alignment = false,

      content,
      ctaLink,
      ctaText,
      embed,
      featuredVideoId,
      imageID,
      title,
    } = attributes;

    let { type } = attributes;
    if (!type) {
      type = 'image';
    }

    const classes = classnames('page-hero', 'headerBlock', {
      'page-heroSize--full': !size,
      'page-heroBackground--transparent': !background,
      'page-heroAlignment--left': !alignment,
      'page-hero--video': type === 'video',
      [`page-heroSize--${size}`]: size,
      [`page-heroBackground--${background}`]: background,
      [`page-heroAlignment--${alignment}`]: alignment,
    });

    const sectionStyles = {};
    if (type === 'image' && this.state.imageData?.url) {
      sectionStyles.backgroundImage = `url("${this.state.imageData.url}")`;
    }

    const caption = this.state[`${type}Data`]?.caption;
    const copyright = this.state[`${type}Data`]?.description;

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
            <MediaMetadataVisibilityControls
              type={attributes.type}
              hideCaption={attributes.hideImageCaption}
              hideCopyright={attributes.hideImageCopyright}
              setAttributes={setAttributes}
            />
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
        <section className={classes} style={sectionStyles}>
          {type !== 'video' && (
            <div className="linkList-options">
              {imageID ? (
                <IconButton
                  icon="no-alt"
                  // translators: [admin]
                  label={__('Remove Image', 'amnesty')}
                  onClick={() => setAttributes({ imageID: 0 })}
                />
              ) : (
                <MediaUpload
                  allowedTypes={['image']}
                  value={imageID}
                  onSelect={(media) => setAttributes({ imageID: media.id })}
                  render={({ open }) => <IconButton icon="format-image" onClick={open} />}
                />
              )}
            </div>
          )}

          {this.state.videoData?.url && (
            <div className="page-heroVideoContainer">
              <video className="page-heroVideo">
                <source src={this.state.videoData.url} />
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
                placeholder={__('Banner Title', 'amnesty')}
                format="string"
                onChange={(newTitle) => setAttributes({ title: newTitle })}
              />
              <RichText
                tagName="p"
                className="page-heroContent"
                value={content}
                // translators: [admin]
                placeholder={__('Banner Content', 'amnesty')}
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
                    placeholder={__('Call to action', 'amnesty')}
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
          <MediaMetadata
            media={{ caption, copyright }}
            showMediaCaption={!attributes.hideImageCaption}
            showMediaCopyright={!attributes.hideImageCopyright}
          />
        </section>
      </Fragment>
    );
  }
}
