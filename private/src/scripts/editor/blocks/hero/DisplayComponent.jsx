import PostFeaturedVideo from '../../components/PostFeaturedVideo.jsx';
import classnames from 'classnames';


const { InnerBlocks, InspectorControls, RichText, URLInputButton, MediaUpload } = wp.blockEditor;
const { PanelBody, SelectControl, ToggleControl, IconButton } = wp.components;
const { useSelect } = wp.data;
const { PostFeaturedImage } = wp.editor;
const { Fragment, useEffect, useRef, useState } = wp.element;
const { __ } = wp.i18n;

const DisplayComponent = (props) => {
  const [imageData, setImageData] = useState({});
  const [videoData, setVideoData] = useState({});
  const { attributes = {}, setAttributes, className } = props;
  const videoRef = useRef();

  // Set class names for the content back colours
  const classes = classnames(className,{
    'headerBackground--dark': attributes.background === 'dark',
    'headerBackground--light': attributes.background === 'light',
  });

  const { featuredMedia, overrideImage} = useSelect((select) => {
    const { getMedia } = select('core');
    const { getEditedPostAttribute } = select('core/editor');
    const featuredImageId = getEditedPostAttribute('featured_media');

    return {
      featuredMedia: featuredImageId ? getMedia(featuredImageId) : null,
      overrideImage: attributes.imageID ? getMedia(attributes.imageID) : null,
    }
  });

  // On mount set captions to featured media if available
  useEffect(() => {
    if (featuredMedia) {
      setImageData({
        caption: featuredMedia.caption.raw,
        credit: featuredMedia.description.raw,
    })
    }
  },[featuredMedia]);

  // On change of featured image id set caption and credit to state, on change of featured video id if type = video fetch video url
  useEffect(() => {
    const { type, featuredVideoId } = props.attributes;

    if (type === 'video' && featuredVideoId) {
      fetchVideoUrl();
    }

    if (featuredMedia) {
      setImageData({
        caption: featuredMedia.caption.raw,
        credit: featuredMedia.description.raw,
      })
    }
  },[props.attributes.featuredImageId, props.attributes.featuredVideoId]);

  // On change of featured video id load video
  useEffect(() => {
    if (props.attributes.featuredVideoId) {
      videoRef?.current?.load();
    }
  }, [props.attributes.featuredVideoId]);

  // On change of image id set the override image caption and credit to state
  useEffect(() => {
    const { imageID } = props.attributes;

    if(overrideImage) {
      setImageData({
        caption: overrideImage.caption.raw,
        credit: overrideImage.description.raw,
      });
    }

    // If image id is 0 and featured media is available switch back to featured image caption and credit
    if(imageID === 0 && featuredMedia){
      setImageData({
        caption: featuredMedia.caption.raw,
        credit: featuredMedia.description.raw,
      });
    }
  }, [props.attributes.imageID, overrideImage]);

  // When removing the video and changing the type back to image the relevant image caption and credit should be displayed
  useEffect(() => {
    const {type, imageData, featuredVideoId} = props.attributes;

    if (type === 'video' && featuredVideoId) {
      fetchVideoUrl();
    }

    if (type !== 'video' && imageData !== 0 && overrideImage) {
      setImageData({
        caption: overrideImage.caption.raw,
        credit: overrideImage.description.raw,
      });
    }

    if (type !== 'video' && imageData === 0 && featuredMedia) {
      setImageData({
        caption: featuredMedia.caption.raw,
        credit: featuredMedia.description.raw,
      });
    }
  }, [props.attributes.type])

  // Function to fetch video url and set it to state
  const fetchVideoUrl = () => {
    const { featuredVideoId } = props.attributes;

    wp.apiFetch({
      path: `/wp/v2/media/${featuredVideoId}`,
    }).then((media) => {
      setVideoData({
        caption: media.caption.rendered.replace(/<[^>]+>/g, ''),
        credit: media.description.rendered.replace(/<[^>]+>/g, ''),
        url: media.source_url,
      })
    });
  }

  const sectionBackground = {}

  // If there is an override image set the background image to the override image url
  if (attributes.imageID && attributes.imageID !== 0 ) {
    sectionBackground.backgroundImage = `url("${attributes.imageURL}")`;
  // If there is a featured image set the background image to the featured image url
  } else if (featuredMedia?.source_url) {
    sectionBackground.backgroundImage = `url("${featuredMedia.source_url}")`;
  }

  const showImageCaption = imageData?.caption && !attributes.hideImageCaption;
  const showImageCredit = imageData?.credit && !attributes.hideImageCredit;

  const mediaPanelTitle = (type) => {
    if (type === 'video') {
      return /* translators: [admin] */ __('Background Image', 'amnesty');
    }

    return /* translators: [admin] */ __('Featured Image', 'amnesty');
  }

  const BlockInspectorControls = (
    <InspectorControls>
      <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')} initialOpen={true}>
        <SelectControl
          label={/* translators: [admin] */ __('Background Colour', 'amnesty')}
          options={[
            { label:/* translators: [admin] */ __('Black', 'amnesty'), value: 'dark' },
            { label:/* translators: [admin] */ __('White', 'amnesty'), value: 'light' },
          ]}
          value={attributes.background || 'dark'}
          onChange={(background) => setAttributes({ background })}
        />
        <SelectControl
          label={/* translators: [admin] */ __('Background Type', 'amnesty')}
          options={[
            { label:/* translators: [admin] */ __('Image', 'amnesty'), value: 'image' },
            { label:/* translators: [admin] */ __('Video', 'amnesty'), value: 'video' },
          ]}
          value={attributes.type || 'image'}
          onChange={(type) => setAttributes({ type })}
        />
        <>
          <ToggleControl
            label={
              attributes.type === 'video'
                ? /* translators: [admin] */
                  __('Hide Video Caption', 'amnesty')
                : /* translators: [admin] */
                  __('Hide Image Caption', 'amnesty')
              }
            checked={attributes.hideImageCaption}
            onChange={() => setAttributes({ hideImageCaption: !attributes.hideImageCaption })}
          />
          <ToggleControl
            label={
              attributes.type === 'video'
                ? /* translators: [admin] */
                  __('Hide Video Credit', 'amnesty')
                : /* translators: [admin] */
                  __('Hide Image Credit', 'amnesty')
            }
            checked={attributes.hideImageCredit}
            onChange={() => setAttributes({ hideImageCredit: !attributes.hideImageCredit })}
          />
        </>
      </PanelBody>
      <PanelBody title={mediaPanelTitle(attributes.type)}>
        <PostFeaturedImage />
      </PanelBody>
      {attributes.type === 'video' && (
        <>
          <PanelBody title={/* translators: [admin] */ __('Featured Video', 'amnesty')}>
            <PostFeaturedVideo
              featuredVideoId={attributes.featuredVideoId}
              onUpdate={(featuredVideoId) => {
                setAttributes({ featuredVideoId });
                setVideoData({});
              }}
            />
          </PanelBody>
        </>
      )}
    </InspectorControls>
  );

  return (
    <Fragment>
      {BlockInspectorControls}
      <section className={classes} style={sectionBackground}>
      {attributes.type !== 'video' && (
        <div className="linkList-options">
          {attributes.imageID ? (
            <IconButton
              icon="no-alt"
              // translators: [admin]
              label={__('Remove Image', 'amnesty')}
              onClick={() => setAttributes({ imageID: 0, imageURL: '' })}
            />
          ) : (
              <MediaUpload
                allowedTypes={['image']}
                value={attributes.imageID}
                onSelect={(media) => {
                  setAttributes({
                    imageID: media.id,
                    imageURL: media.url,
                  });
                }
              }
                render={({ open }) => <IconButton icon="format-image" onClick={open} />}
              />
              )}
            </div>
        )}
        {videoData.url && attributes.type === 'video' && (
          <div className="headerBackgroundVideo">
            <video className="headerVideo" ref={videoRef}>
              <source src={videoData.url} />
            </video>
          </div>
        )}
        <div className="container">
          <div className="header-content">
            <h1>
              <RichText
                tagName="span"
                className="headerTitle"
                placeholder={/* translators: [admin] */ __('Header Title', 'amnesty')}
                value={attributes.title}
                onChange={(title) => setAttributes({ title })}
                format="string"
                keepPlaceholderOnFocus={true}
              />
            </h1>
              <RichText
                tagName="p"
                className="headerContent"
                placeholder={/* translators: [admin] */ __('Header Content', 'amnesty')}
                value={attributes.content}
                onChange={(content) => setAttributes({ content })}
                format="string"
                keepPlaceholderOnFocus={true}
              />
              <div className="headerCta">
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
          {imageData && attributes.type != 'video' && (
            <div className="image-meta">
              {showImageCaption && (
                <span className="image-metaItem headerImageCaption">
                  {imageData.caption}
                </span>
              )}
              {showImageCredit && (
                <span className="image-metaItem headerImageCredit">
                  {imageData.credit}
                </span>
              )}
            </div>
          )}
          {videoData.url && attributes.type === 'video' && (
            <div className="image-meta">
              {showImageCaption && (
                <span className="image-metaItem headerImageCaption">
                  {videoData.caption}
                </span>
              )}
              {showImageCredit && (
                <span className="image-metaItem headerImageCredit">
                  {videoData.credit}
                </span>
              )}
            </div>
          )}
      </section>
    </Fragment>
  );
};

export default DisplayComponent;
