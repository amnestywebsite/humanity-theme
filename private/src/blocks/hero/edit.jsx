import classnames from 'classnames';
import {
  InnerBlocks,
  InspectorControls,
  RichText,
  URLInputButton,
  useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { PostFeaturedImage } from '@wordpress/editor';
import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import BlockImageSelector from './components/BlockImageSelector.jsx';
import MediaMetadata from '../../components/MediaMetadata.jsx';
import MediaMetadataVisibilityControls from '../../components/MediaMetadataVisibilityControls.jsx';
import PostFeaturedVideo from '../../components/PostFeaturedVideo.jsx';
import { fetchMediaData } from '../../utils/index';

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

const useHasInnerBlocks = (parentClientId) =>
  useSelect((select) => {
    const { innerBlocks } = select('core/block-editor').getBlock(parentClientId);
    return innerBlocks.length;
  });

export default function Edit({ attributes, className, clientId, setAttributes }) {
  const [mediaData, setMediaData] = useState({});
  const videoRef = useRef();
  const hasInnerBlocks = useHasInnerBlocks(clientId);
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
  }, [featuredImage, attributes.imageID, attributes.type]); // eslint-disable-line react-hooks/exhaustive-deps

  useEffect(() => {
    if (attributes.type !== 'video') {
      return;
    }

    fetchMediaData(attributes.featuredVideoId, setMediaData, mediaData).then(() => {
      videoRef?.current?.load?.();
    });
  }, [attributes.featuredVideoId, attributes.type]); // eslint-disable-line react-hooks/exhaustive-deps

  const blockClasses = classnames(className, {
    [`has-${attributes.background}-background`]: attributes.background,
    'has-innerBlocks': hasInnerBlocks,
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
    <>
      {BlockInspectorControls}
      <section {...useBlockProps({ className: blockClasses })} style={blockInlineStyle}>
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
        <div className="hero-contentWrapper">
          <h1 className="hero-title">
            <RichText
              tagName="span"
              placeholder={/* translators: [admin] */ __('Header Title', 'amnesty')}
              value={attributes.title}
              onChange={(title) => setAttributes({ title })}
              format="string"
            />
          </h1>
          <RichText
            tagName="p"
            className="hero-content"
            placeholder={/* translators: [admin] */ __('Header Content', 'amnesty')}
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
          <div className="hero-innerBlocks">
            <InnerBlocks allowedBlocks={['amnesty-wc/donation']} orientation="horizontal" />
          </div>
        </div>
        <MediaMetadata
          media={mediaData}
          showMediaCaption={showMediaCaption}
          showMediaCopyright={showMediaCopyright}
        />
      </section>
    </>
  );
}
