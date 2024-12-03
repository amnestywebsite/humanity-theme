import classNames from 'classnames';

import { fetchImageData, findImage } from './utils';
import BackgroundImageOriginControl from '../../components/BackgroundImageOriginControl';
import BackgroundImageSelectorControl from '../../components/BackgroundImageSelectorControl';
import MediaMetadata from '../../components/MediaMetadata';
import MediaMetadataVisibilityControls from '../../components/MediaMetadataVisibilityControls';

import { InspectorControls, InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl, TextControl, ToggleControl } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

const Controls = ({ attributes, setAttributes }) => {
  const handleSave = (sectionName) => {
    const sectionId = sectionName.replace(/\s/g, '').toLowerCase();
    setAttributes({ sectionId, sectionName });
  };

  const selectImage = (value) => {
    setAttributes({
      backgroundImageId: value.id,
      backgroundImage: value.sizes.full.url,
      backgroundImageHeight: value.sizes.full.height,
    });
  };

  const removeImage = () => {
    setAttributes({
      backgroundImageId: 0,
      backgroundImage: '',
      backgroundImageHeight: 0,
      minHeight: 0,
    });
  };

  return (
    <InspectorControls>
      <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
        <TextControl
          // translators: [admin]
          label={__('Section Name', 'amnesty')}
          value={attributes.sectionName}
          onChange={handleSave}
        />
        <SelectControl
          // translators: [admin]
          label={__('Text Colour', 'amnesty')}
          options={[
            // translators: [admin]
            { label: __('Black', 'amnesty'), value: 'black' },
            // translators: [admin]
            { label: __('White', 'amnesty'), value: 'white' },
          ]}
          value={attributes.textColour}
          onChange={(textColour) => setAttributes({ textColour })}
        />
      </PanelBody>
      <PanelBody title={/* translators: [admin] */ __('Background Options', 'amnesty')}>
        <BackgroundImageSelectorControl
          imageId={attributes.backgroundImageId}
          onRemove={removeImage}
          onSelect={selectImage}
        />
        <RangeControl
          // translators: [admin]
          label={__('Min image height as viewport percentage', 'amnesty')}
          onChange={(value) => setAttributes({ minHeight: !value ? 0 : value })}
          value={attributes.minHeight}
          intialPostition={attributes.minHeight}
          min={0}
          max={100}
          allowReset
        />
        <ToggleControl
          // translators: [admin]
          label={__('Toggle Background Overlay', 'amnesty')}
          checked={attributes.enableBackgroundGradient}
          onChange={(enableBackgroundGradient) => setAttributes({ enableBackgroundGradient })}
        />
        <MediaMetadataVisibilityControls
          hideCaption={attributes.hideImageCaption}
          hideCopyright={attributes.hideImageCopyright}
          setAttributes={setAttributes}
        />
        <BackgroundImageOriginControl
          origin={attributes.backgroundImageOrigin}
          setOrigin={(backgroundImageOrigin) => setAttributes({ backgroundImageOrigin })}
        />
        <SelectControl
          // translators: [admin]
          label={__('Background Colour', 'amnesty')}
          options={[
            // translators: [admin]
            { label: __('White', 'amnesty'), value: '' },
            // translators: [admin]
            { label: __('Grey', 'amnesty'), value: 'grey' },
          ]}
          value={attributes.background}
          onChange={(background) => setAttributes({ background })}
        />
      </PanelBody>
    </InspectorControls>
  )
};

const getClassName = (attributes) => classNames(
  'section',
  {
    'section--tinted': attributes.background === 'grey',
    [`section--${attributes.padding}`]: !!attributes.padding,
    'section--textWhite': attributes.textColour === 'white',
    'section--has-bg-image': attributes.backgroundImage,
    'section--has-bg-overlay': !!attributes.enableBackgroundGradient,
  },
  `section--bgOrigin-${attributes.backgroundImageOrigin}`,
);

const makeBlockStylesBuilder = (attributes) => (h) => {
  if (!attributes.backgroundImage) {
    return {};
  }

  if (h > 0) {
    return {
      backgroundImage: `url(${attributes.backgroundImage})`,
      minHeight: `${attributes.minHeight}vw`,
      maxHeight: `${attributes.backgroundImageHeight}px`,
    };
  }

  return {
    backgroundImage: `url(${attributes.backgroundImage})`,
    height: 'auto',
  };
};

const edit = ({ attributes, setAttributes }) => {
  const [imageData, setImageData] = useState(null);

  useEffect(() => {
    const { backgroundImageId = 0, backgroundImage } = attributes;
    if (backgroundImageId) {
      fetchImageData(backgroundImageId, setImageData);
      return;
    }

    if (!backgroundImage) {
      return;
    }

    // if no image id, but image uri, get the image id
    const parts = backgroundImage.split(/[\\/]/);
    const basename = parts.pop().replace(/\.[a-z]{3,4}/, '');
    const month = parts.pop();
    const year = parts.pop();

    const image = findImage(basename, year, month);
    if (image) {
      setAttributes({ backgroundImageId: image });
    }
  }, []);

  useEffect(() => {
    fetchImageData(attributes.backgroundImageId, setImageData);
  }, [attributes.backgroundImageId]);

  const shouldShowImageCaption =
    imageData?.caption &&
    !attributes.hideImageCaption &&
    imageData?.caption !== imageData?.copyright;

  const shouldShowImageCredit = imageData?.copyright && !attributes.hideImageCopyright;

  const sectionClasses = getClassName(attributes);
  const buildStyles = makeBlockStylesBuilder(attributes);

  return (
    <div {...useBlockProps()}>
      <Controls attributes={attributes} setAttributes={setAttributes} />
      <section className={sectionClasses} style={buildStyles(attributes.minHeight)}>
        <div id={attributes.sectionId} className="container">
          <InnerBlocks templateLock={false} />
        </div>
        <MediaMetadata
          media={imageData}
          showMediaCaption={shouldShowImageCaption}
          showMediaCopyright={shouldShowImageCredit}
        />
      </section>
    </div>
  );
};

export default edit;
