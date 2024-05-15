import classnames from 'classnames';
import MediaMetadata from '../../components/MediaMetadata.jsx';
import MediaMetadataVisibilityControls from '../../components/MediaMetadataVisibilityControls.jsx';
import PostFeaturedVideo from '../../components/PostFeaturedVideo.jsx';

const { InnerBlocks, InspectorControls, RichText, URLInputButton } = wp.blockEditor;
const { PanelBody, SelectControl, withFilters } = wp.components;
const { compose } = wp.compose;
const { withSelect } = wp.data;
const { PostFeaturedImage } = wp.editor;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { addQueryArgs } = wp.url;

class DisplayComponent extends Component {
  state = {
    imageData: null,
    videoData: null,
  };

  fetchMediaMetadata = (id, type) => {
    const key = `${type}Data`;
    const cached = this.state[key]?.id;

    if (id === 0) {
      this.setState({
        [key]: null,
      });
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
    const { featuredImageId } = this.props;
    const { type, featuredVideoId } = this.props.attributes;

    if (type === 'video' && !this.state.videoData?.url && featuredVideoId) {
      this.fetchMediaMetadata(featuredVideoId, 'video');
    }

    if (type !== 'video' && featuredImageId && this.state.imageData?.id !== featuredImageId) {
      this.fetchMediaMetadata(featuredImageId, 'image');
    }
  }

  componentDidUpdate(prevProps) {
    const { featuredImageId } = this.props;
    const { type, featuredVideoId } = this.props.attributes;

    if (type === 'video' && !this.state.videoData?.url && featuredVideoId) {
      this.fetchMediaMetadata(featuredVideoId, 'video');
    }

    if (
      type !== 'video' &&
      (featuredImageId !== prevProps.featuredImageId ||
        this.state.imageData?.id !== featuredImageId)
    ) {
      this.fetchMediaMetadata(featuredImageId, 'image');
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

  render() {
    const { attributes = {}, setAttributes } = this.props;

    let { type } = attributes;
    if (!type) {
      type = 'image';
    }

    const classes = classnames('page-hero', {
      'page-heroSize--full': attributes.size === 'large',
      'page-heroBackground--transparent': !attributes.background,
      'page-heroAlignment--left': !attributes.alignment,
      [`page-heroSize--${attributes.size}`]: attributes.size,
      [`page-heroBackground--${attributes.background || 'dark'}`]: attributes.background || true,
      [`page-heroAlignment--${attributes.alignment}`]: attributes.alignment || false,
      'page-hero--video': type === 'video',
    });

    const contentClasses = classnames('hero-content', {
      'has-donation-block': this.props.hasInnerBlock.length > 0,
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
            <MediaMetadataVisibilityControls
              type={attributes.type}
              hideCaption={attributes.hideImageCaption}
              hideCopyright={attributes.hideImageCopyright}
              setAttributes={setAttributes}
            />
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
          {this.state.videoData?.url && (
            <div className="page-heroVideoContainer">
              <video className="page-heroVideo">
                <source src={this.state.videoData.url} />
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
          <MediaMetadata
            caption={caption}
            copyright={copyright}
            showCaption={!attributes.hideImageCaption}
            showCopyright={!attributes.hideImageCopyright}
          />
        </section>
      </Fragment>
    );
  }
}

const applyWithSelect = () =>
  withSelect((select, blockData) => {
    const { getPostType } = select('core');
    const { getEditedPostAttribute } = select('core/editor');
    const featuredImageId = getEditedPostAttribute('featured_media');
    const hasInnerBlock = select('core/block-editor').getBlocks(blockData.clientId);

    return {
      postType: getPostType(getEditedPostAttribute('type')),
      featuredImageId,
      hasInnerBlock,
    };
  });

export default compose(
  applyWithSelect(),
  withFilters('editor.PostFeaturedImage'),
)(DisplayComponent);
