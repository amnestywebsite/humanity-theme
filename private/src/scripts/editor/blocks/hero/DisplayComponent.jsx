import classnames from 'classnames';
import BlockImageSelector from './components/BlockImageSelector.jsx';
import MediaMetadata from '../../components/MediaMetadata.jsx';
import MediaMetadataVisibilityControls from '../../components/MediaMetadataVisibilityControls.jsx';
import PostFeaturedVideo from '../../components/PostFeaturedVideo.jsx';
import { fetchMediaData } from '../utils';

const { InnerBlocks, InspectorControls, RichText, URLInputButton } = wp.blockEditor;
const { PanelBody, SelectControl } = wp.components;
const { useSelect } = wp.data;
const { PostFeaturedImage } = wp.editor;
const { Fragment, useEffect, useRef, useState } = wp.element;
const { __ } = wp.i18n;

/**
 * Retrieve the correct title for the media panel
 *
 * @param {String} type the media type
 *
 * @returns {String}
 */
const mediaPanelTitle = (type) => {
  if (type === 'video') {
    /* translators: [admin] */
    return __('Background Image', 'amnesty');
  }

  /* translators: [admin] */
  return __('Featured Image', 'amnesty');
};

const DisplayComponent = ({ attributes, className, setAttributes }) => {
  const [mediaData, setMediaData] = useState({});
  const videoRef = useRef();

  const featuredImage = useSelect((select) => {
    const { getEditedPostAttribute } = select('core/editor');
    return getEditedPostAttribute('featured_media');
  });

  useEffect(() => {
    if (attributes.type !== 'image') {
      return;
    }

    // block attribute takes precedence over the featured image
    const id = attributes?.imageID || featuredImage;
    fetchMediaData(id, setMediaData, mediaData);
  }, [featuredImage, attributes.imageID, attributes.type]);

  useEffect(() => {
    if (attributes.type !== 'video') {
      return;
    }

    fetchMediaData(attributes.featuredVideoId, setMediaData, mediaData).then(() => {
      videoRef?.current?.load?.();
    });
  }, [attributes.featuredVideoId, attributes.type]);

  // Set class names for the content back colours
  const classes = classnames(className, {
    [`has-${attributes.background}-background`]: attributes.background,
    'has-video': !!attributes.featuredVideoId,
  });

  const blockInlineStyle = {};
  if (attributes.type === 'image' && mediaData?.url) {
    blockInlineStyle.backgroundImage = `url("${mediaData.url}")`;
  }

  const showMediaCaption = mediaData?.caption && !attributes.hideImageCaption;
  const showMediaCopyright = mediaData?.copyright && !attributes.hideImageCopyright;

  const BlockInspectorControls = (
    <InspectorControls>
      <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')} initialOpen={true}>
        <SelectControl
          label={/* translators: [admin] */ __('Background Colour', 'amnesty')}
          options={[
            { label: /* translators: [admin] */ __('Black', 'amnesty'), value: 'dark' },
            { label: /* translators: [admin] */ __('White', 'amnesty'), value: 'light' },
          ]}
          value={attributes.background || 'dark'}
          onChange={(background) => setAttributes({ background })}
        />
        <SelectControl
          label={/* translators: [admin] */ __('Background Type', 'amnesty')}
          options={[
            { label: /* translators: [admin] */ __('Image', 'amnesty'), value: 'image' },
            { label: /* translators: [admin] */ __('Video', 'amnesty'), value: 'video' },
          ]}
          value={attributes.type || 'image'}
          onChange={(type) => setAttributes({ type })}
        />
        <MediaMetadataVisibilityControls
          type={attributes.type}
          hideCaption={attributes.hideImageCaption}
          hideCopyright={attributes.hideImageCopyright}
          setAttributes={setAttributes}
        />
      </PanelBody>
      <PanelBody title={mediaPanelTitle(attributes.type)}>
        <PostFeaturedImage />
      </PanelBody>
      {attributes.type === 'video' && (
        <PanelBody title={/* translators: [admin] */ __('Featured Video', 'amnesty')}>
          <PostFeaturedVideo
            featuredVideoId={attributes.featuredVideoId}
            onUpdate={(featuredVideoId) => setAttributes({ featuredVideoId })}
          />
        </PanelBody>
      )}
    </InspectorControls>
  );

  return (
    <Fragment>
      {BlockInspectorControls}
      <section className={classes} style={blockInlineStyle}>
        {attributes.type === 'image' && (
          <div className="linkList-options">
            <BlockImageSelector imageId={attributes.imageID} />
          </div>
        )}
        {attributes.type === 'video' && (
          <div className="hero-videoContainer">
            <video className="hero-video" ref={videoRef}>
              <source src={mediaData.url} />
            </video>
          </div>
        )}
        <div className="container">
          <div className="hero-contentWrapper">
            <h1>
              <RichText
                tagName="span"
                className="hero-title"
                placeholder={/* translators: [admin] */ __('Header Title', 'amnesty')}
                value={attributes.title}
                onChange={(title) => setAttributes({ title })}
                format="string"
                keepPlaceholderOnFocus={true}
              />
            </h1>
            <RichText
              tagName="p"
              className="hero-content"
              placeholder={/* translators: [admin] */ __('Header Content', 'amnesty')}
              value={attributes.content}
              onChange={(content) => setAttributes({ content })}
              format="string"
              keepPlaceholderOnFocus={true}
            />
            <div className="hero-cta">
              <div className="btn btn--large">
                <RichText
                  tagName="span"
                  placeholder={/* translators: [admin] */ __('Call to action', 'amnesty')}
                  value={attributes.ctaText}
                  onChange={(ctaText) => setAttributes({ ctaText })}
                  format="string"
                  keepPlaceholderOnFocus={true}
                />
                <URLInputButton
                  url={attributes.ctaLink}
                  onChange={(ctaLink) => setAttributes({ ctaLink })}
                />
              </div>
            </div>
          </div>
          <InnerBlocks allowedBlocks={['amnesty-wc/donation']} orientation="horizontal" />
        </div>
        <MediaMetadata
          media={mediaData}
          showMediaCaption={showMediaCaption}
          showMediaCopyright={showMediaCopyright}
        />
      </section>
    </Fragment>
  );
};

export default DisplayComponent;
