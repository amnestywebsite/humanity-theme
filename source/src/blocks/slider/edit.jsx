import classnames from 'classnames';
import memoize from 'memize';
import { times } from 'lodash';
import { createBlock } from '@wordpress/blocks';
import { InnerBlocks, InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { useDispatch, useSelect } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import { randId } from '../../utils';

const ALLOWED_BLOCKS = ['amnesty-core/slide'];
const getLayoutTemplate = memoize((blocks) => times(blocks, () => ALLOWED_BLOCKS));

const Controls = ({ attributes, setAttributes }) => (
  <InspectorControls>
    <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
      <ToggleControl
        /* translators: [admin] */
        label={__('Show Arrows', 'amnesty')}
        checked={attributes.hasArrows}
        onChange={(hasArrows) => setAttributes({ hasArrows })}
      />

      <ToggleControl
        /* translators: [admin] */
        label={__('Has Content', 'amnesty')}
        checked={attributes.hasContent}
        onChange={(hasContent) => setAttributes({ hasContent })}
        help={
          <span>
            {
              /* translators: [admin] */ __(
                'By disabling this you will hide the content in *ALL* slides. To disable this on only one slide, select the desired slide and toggle the "Hide Content" field in the "Options" panel.',
                'amnesty',
              )
            }
          </span>
        }
      />
      <ToggleControl
        /* translators: [admin] */
        label={__('Show Tabs', 'amnesty')}
        checked={attributes.showTabs}
        onChange={(showTabs) => setAttributes({ showTabs })}
        help={
          <span>
            {
              /* translators: [admin] */ __(
                'Hide the tabs on the front end. They will still show in the panel to allow you to navigate through each slide.',
                'amnesty',
              )
            }
          </span>
        }
      />
    </PanelBody>
    <PanelBody title={/* translators: [admin] */ __('Timeline Options', 'amnesty')}>
      <TextControl
        /* translators: [admin] */
        label={__('Slider Title', 'amnesty')}
        onChange={(title) => setAttributes({ title })}
        value={attributes.title}
      />
    </PanelBody>
  </InspectorControls>
);

export default function Edit({ attributes, className, clientId, setAttributes }) {
  const slides = useSelect(
    (select) => select('core/block-editor').getBlock(clientId).innerBlocks,
    [clientId], // Make sure to update when the block's innerBlocks change
  );

  const { setSlides, setSelectedSlide } = useDispatch('amnesty/blocks');
  const [selectedSlideIndex, setSelectedSlideIndex] = useState(0);

  setSlides(
    clientId,
    slides.map((s) => s.clientId),
  );
  setSelectedSlide(clientId, slides[selectedSlideIndex].clientId);

  useEffect(() => {
    if (!attributes.sliderId) {
      setAttributes({ sliderId: randId() });
    }
  }, [attributes.sliderId, setAttributes]);

  const nextSlide = () => setSelectedSlideIndex(selectedSlideIndex + 1);
  const prevSlide = () => setSelectedSlideIndex(selectedSlideIndex - 1);

  const addSlide = () => {
    // Use setAttributes to modify the innerBlocks
    const updatedSlides = [...slides, createBlock('amnesty-core/slide')];
    setAttributes({ innerBlocks: updatedSlides });
    setAttributes({ quantity: updatedSlides.length });

    nextSlide();
  };

  const classes = classnames(className, 'slider', {
    [`timeline-${attributes.style}`]: !!attributes.style,
  });

  return (
    <>
      <Controls attributes={attributes} setAttributes={setAttributes} />
      <div {...useBlockProps({ className: classes })}>
        {!!attributes.title && (
          <div className="slider-title">
            <RichText
              tagname="span"
              /* translators: [admin] */
              placeholder={__('Slider Title', 'amnesty')}
              onChange={(title) => setAttributes({ title })}
              value={attributes.title}
              allowedFormats={[]}
              format="string"
            />
          </div>
        )}
        <div className="slides-container">
          {attributes.hasArrows && (
            <>
              <button className="slides-arrow slides-arrow--next" onClick={nextSlide}>
                {/* translators: [admin] */ __('Next', 'amnesty')}
              </button>
              <button className="slides-arrow slides-arrow--previous" onClick={prevSlide}>
                {/* translators: [admin] */ __('Previous', 'amnesty')}
              </button>
            </>
          )}
          <div className="slides">
            <InnerBlocks
              template={getLayoutTemplate(attributes.quantity || 5)} // Fallback to 5 if quantity is undefined
              templateLock="all"
            />
          </div>
        </div>
        <nav className="slider-nav">
          {slides.map((slide, index) => {
            // Use clientId or slide.title for unique keys
            const key = slide.attributes.title || slide.clientId;

            return (
              <button
                key={key}
                className={classnames('slider-navButton', {
                  'is-active': selectedSlideIndex === index,
                })}
                onClick={() => setSelectedSlideIndex(index)} // Corrected to set selected slide
              >
                {/* translators: [admin] */}
                {slide.attributes.title || __('No Title', 'amnesty')}
              </button>
            );
          })}
          <button className="slider-navButton" onClick={addSlide}>
            {/* translators: [admin] */ __('Add Slide', 'amnesty')}
          </button>
        </nav>
      </div>
    </>
  );
}
