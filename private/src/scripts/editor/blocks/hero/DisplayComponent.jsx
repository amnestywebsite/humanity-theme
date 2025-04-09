import classnames from 'classnames';
import BlockImageSelector from './components/BlockImageSelector.jsx';
import MediaMetadata from '../../components/MediaMetadata.jsx';
import MediaMetadataVisibilityControls from '../../components/MediaMetadataVisibilityControls.jsx';
import PostFeaturedVideo from '../../components/PostFeaturedVideo.jsx';
import { fetchMediaData } from '../utils';

const { InnerBlocks, InspectorControls, RichText, URLInputButton } = wp.blockEditor;
const { PanelBody, SelectControl } = wp.components;
const { useEntityRecord } = wp.coreData;
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

const useHasDonationBlock = (parentClientId) =>
  useSelect((select) => {
    const { innerBlocks } = select('core/block-editor').getBlock(parentClientId);
    return innerBlocks.filter((block) => block.name === 'amnesty-wc/donation').length;
  });

const DisplayComponent = (props) => {
  const {
    attributes,
    className,
    clientId,
    context: { postId, postType },
    setAttributes,
  } = props;

  const [mediaData, setMediaData] = useState({});
  const videoRef = useRef();
  const hasDonationBlock = useHasDonationBlock(clientId);
  const object = useEntityRecord('postType', postType, postId);

  useEffect(() => {
    if (attributes.type !== 'image') {
      return;
    }

    // block attribute takes precedence over the featured image
    const id = attributes?.imageID || object.editedRecord.featured_media;
    fetchMediaData(id, setMediaData, mediaData);
  }, [object.editedRecord.featured_media, attributes.imageID, attributes.type]);

  useEffect(() => {
    if (attributes.type !== 'video') {
      return;
    }

    fetchMediaData(attributes.featuredVideoId, setMediaData, mediaData).then(() => {
      videoRef?.current?.load?.();
    });
  }, [attributes.featuredVideoId, attributes.type]);

  // Set class names for the content back colours
  const blockClasses = classnames(className, {
    [`has-${attributes.background}-background`]: attributes.background,
    [`is-aligned-${attributes.align}`]: attributes.align,
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
      <section className={blockClasses} style={blockInlineStyle}>
        {attributes.type === 'image' && (
          <div className="linkList-options">
            <BlockImageSelector imageId={attributes.imageID} setAttributes={setAttributes} />
          </div>
        )}
        {attributes.type === 'video' && (
          <div className="hero-videoContainer">
            <video className="hero-video" ref={videoRef}>
              <source src={mediaData.url} />
            </video>
          </div>
        )}
        <div className={`container ${hasDonationBlock ? 'has-donation-block' : ''}`}>
          <div className="hero-contentWrapper">
            <h1 className="hero-title">
              <RichText
                tagName="span"
                placeholder={/* translators: [admin] */ __('Hero Title', 'amnesty')}
                value={attributes.title}
                onChange={(title) => setAttributes({ title })}
                format="string"
              />
            </h1>
            <RichText
              tagName="p"
              className="hero-content"
              placeholder={/* translators: [admin] */ __('Hero Content', 'amnesty')}
              value={attributes.content}
              onChange={(content) => setAttributes({ content })}
              format="string"
            />
            <div className="hero-cta">
              <div className="btn btn--large">
                <RichText
                  tagName="span"
                  placeholder={/* translators: [admin] */ __('Call to action', 'amnesty')}
                  value={attributes.ctaText}
                  onChange={(ctaText) => setAttributes({ ctaText })}
                  format="string"
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
