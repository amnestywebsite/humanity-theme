import classnames from 'classnames';
import PostFeaturedVideo from './PostFeaturedVideo.jsx';
import DisplayCreditData from './components/DisplayCreditData.jsx';

const { InnerBlocks, InspectorControls, RichText, URLInputButton } = wp.blockEditor;
const { PanelBody, SelectControl, ToggleControl, withFilters } = wp.components;
const { compose } = wp.compose;
const { withSelect } = wp.data;
const { PostFeaturedImage } = wp.editor;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;

class DisplayComponent extends Component {
  state = {
    imageData: null,
    videoUrl: false,
  };

  componentDidMount() {
    const { featuredImageId } = this.props;
    const { type, featuredVideoId } = this.props.attributes;

    if (type === 'video' && !this.state.videoUrl && featuredVideoId) {
      this.fetchVideoUrl();
    }

    if (type !== 'video' && !this.state.imageData && featuredImageId) {
      this.fetchImageData();
    }
  }

  componentDidUpdate(prevProps) {
    const { featuredImageId } = this.props;
    const { type, featuredVideoId } = this.props.attributes;

    if (type === 'video' && !this.state.videoUrl && featuredVideoId) {
      this.fetchVideoUrl();
    }

    if (type !== 'video' && featuredImageId !== prevProps.featuredImageId) {
      this.fetchImageData();
    }
  }

  /**
   * Higher order component that takes the attribute key,
   * this then returns a function which takes a value,
   * when called it updates the attribute with the key.
   * @param key
   * @returns {function(*): *}
   */
  createUpdateAttribute = (key) => (value) => this.props.setAttributes({ [key]: value });

  fetchImageData = () => {
    const { featuredImageId } = this.props;
    const cached = this.state?.imageData?.id;

    if (cached && cached === featuredImageId) {
      return;
    }

    wp.apiRequest({
      path: `/wp/v2/media/${featuredImageId}?_fields=id,description,caption&context=edit`,
    }).then((resp) => {
      this.setState({
        imageData: {
          caption: resp.caption.raw,
          description: resp.description.raw,
        },
      });
    });
  };

  fetchVideoUrl = () => {
    const { featuredVideoId } = this.props.attributes;
    const { addQueryArgs } = wp.url;

    const path = addQueryArgs(`/wp/v2/media/${featuredVideoId}`, {
      context: 'edit',
      _fields: 'id,source_url,caption,description',
    });

    wp.apiRequest({
      path,
    }).then((resp) => {
      this.setState({
        videoUrl: resp.source_url,
        videoCaption: resp.caption.raw,
        videoDescription: resp.description.raw,
      });
    });
  };

  render() {
    const { attributes = {}, setAttributes } = this.props;
    const { media, video } = this.props;

    const classes = classnames('page-hero', {
      'page-heroSize--full': attributes.size === 'large',
      'page-heroBackground--transparent': !attributes.background,
      'page-heroAlignment--left': !attributes.alignment,
      [`page-heroSize--${attributes.size}`]: attributes.size,
      [`page-heroBackground--${attributes.background || 'dark'}`]: attributes.background || true,
      [`page-heroAlignment--${attributes.alignment}`]: attributes.alignment || false,
      'page-hero--video': attributes.type === 'video',
    });

    const contentClasses = classnames('hero-content', {
      'has-donation-block': this.props.hasInnerBlock.length > 0,
    });

    const sectionStyles = {};
    if (media?.source_url) {
      sectionStyles.backgroundImage = `url("${media.source_url}")`;
    }

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
              value={attributes.alignment}
              onChange={this.createUpdateAttribute('alignment')}
            />
            <SelectControl
              // translators: [admin]
              label={__('Background Colour', 'amnesty')}
              options={[
                {
                  // translators: [admin]
                  label: __('White', 'amnesty'),
                  value: 'light',
                },
                {
                  // translators: [admin]
                  label: __('Black', 'amnesty'),
                  value: 'dark',
                },
              ]}
              value={attributes.background || 'dark'}
              onChange={this.createUpdateAttribute('background')}
            />
            <SelectControl
              // translators: [admin]
              label={__('Size', 'amnesty')}
              options={[
                {
                  // translators: [admin]
                  label: __('Normal', 'amnesty'),
                  value: 'small',
                },
                {
                  // translators: [admin]
                  label: __('Large', 'amnesty'),
                  value: 'large',
                },
              ]}
              value={attributes.size}
              onChange={this.createUpdateAttribute('size')}
            />

            <SelectControl
              // translators: [admin]
              label={__('Background Type', 'amnesty')}
              options={[
                {
                  // translators: [admin]
                  label: __('Image', 'amnesty'),
                  value: '',
                },
                {
                  // translators: [admin]
                  label: __('Video', 'amnesty'),
                  value: 'video',
                },
              ]}
              value={attributes.type}
              onChange={this.createUpdateAttribute('type')}
            />
            <>
              <ToggleControl
                // translators: [admin]
                label={
                  attributes.type === 'video'
                    ? // translators: [admin]
                      __('Hide Video Caption', 'amnesty')
                    : // translators: [admin]
                      __('Hide Image Caption', 'amnesty')
                }
                checked={attributes.hideImageCaption}
                onChange={() => setAttributes({ hideImageCaption: !attributes.hideImageCaption })}
              />
              <ToggleControl
                // translators: [admin]
                label={
                  attributes.type === 'video'
                    ? // translators: [admin]
                      __('Hide Video Credit', 'amnesty')
                    : // translators: [admin]
                      __('Hide Image Credit', 'amnesty')
                }
                checked={attributes.hideImageCopyright}
                onChange={() =>
                  setAttributes({ hideImageCopyright: !attributes.hideImageCopyright })
                }
              />
            </>
          </PanelBody>
          <PanelBody
            title={
              attributes.type === 'video'
                ? // translators: [admin]
                  __('Background Image', 'amnesty')
                : // translators: [admin]
                  __('Featured Image', 'amnesty')
            }
          >
            <PostFeaturedImage />
          </PanelBody>
          {attributes.type === 'video' && (
            <PanelBody title={/* translators: [admin] */ __('Featured Video', 'amnesty')}>
              <PostFeaturedVideo
                featuredVideoId={attributes.featuredVideoId}
                onUpdate={this.createUpdateAttribute('featuredVideoId')}
              />
            </PanelBody>
          )}
        </InspectorControls>
        <section className={classes} style={sectionStyles}>
          {this.state.videoUrl && (
            <div className="page-heroVideoContainer">
              <video className="page-heroVideo">
                <source src={this.state.videoUrl} />
              </video>
            </div>
          )}
          <div className="container">
            <div className={contentClasses}>
              <h1>
                <RichText
                  tagName="span"
                  className="page-heroTitle"
                  value={attributes.title}
                  // translators: [admin]
                  placeholder={__('(Header Title)', 'amnesty')}
                  keepPlaceholderOnFocus={true}
                  format="string"
                  onChange={this.createUpdateAttribute('title')}
                />
              </h1>
              <RichText
                tagName="p"
                className="page-heroContent"
                value={attributes.content}
                // translators: [admin]
                placeholder={__('(Header Content)', 'amnesty')}
                keepPlaceholderOnFocus={true}
                format="string"
                onChange={this.createUpdateAttribute('content')}
              />
              <div className="page-heroCta">
                <div className="btn btn--large">
                  {attributes.embed && <i className="play-icon"></i>}
                  <RichText
                    tagName="span"
                    value={attributes.ctaText}
                    // translators: [admin]
                    placeholder={__('(Call to action)', 'amnesty')}
                    keepPlaceholderOnFocus={true}
                    format="string"
                    onChange={this.createUpdateAttribute('ctaText')}
                  />
                  <URLInputButton
                    url={attributes.ctaLink}
                    onChange={this.createUpdateAttribute('ctaLink')}
                  />
                </div>
              </div>
            </div>
            <InnerBlocks allowedBlocks={['amnesty-wc/donation']} orientation="horizontal" />
          </div>
            <DisplayCreditData
              type={attributes.type}
              media={media}
              videoCaption={this.state.videoCaption}
              videoDescription={this.state.videoDescription}
              hideImageCaption={attributes.hideImageCaption}
              hideImageCopyright={attributes.hideImageCopyright}
            />
        </section>
      </Fragment>
    );
  }
}

const applyWithSelect = () =>
  withSelect((select, blockData) => {
    const { getMedia, getPostType } = select('core');
    const { getEditedPostAttribute } = select('core/editor');
    const featuredImageId = getEditedPostAttribute('featured_media');
    const hasInnerBlock = select('core/block-editor').getBlocks(blockData.clientId);

    /**
     * getMedia && getPostType are initially undefined.
     * This is causing an API request to /wp-admin/undefined/ on load.
     * Unfortunately, there doesn't appear to be an easy way around this,
     * but we should investigate potential solutions.
     * - @jonmcp
     * FAO: @jrmd
     *
     */
    return {
      media: featuredImageId ? getMedia(featuredImageId) : null,
      postType: getPostType(getEditedPostAttribute('type')),
      featuredImageId,
      hasInnerBlock,
    };
  });

export default compose(
  applyWithSelect(),
  withFilters('editor.PostFeaturedImage'),
)(DisplayComponent);
