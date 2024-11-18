import classnames from 'classnames';
import memoize from 'memize';

import { InnerBlocks, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { useEffect, useRef, useSelect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

const ALLOWED_BLOCKS = ['amnesty-core/slide'];
const getLayoutTemplate = memoize((blocks) => times(blocks, () => ALLOWED_BLOCKS));

const edit = ({ attributes, className, clientId, setAttributes }) => {
  const [selectedSlide, setSelectedSlide] = useState(0);
  const mounted = useRef();

  useEffect(() => {
    if (mounted?.current) {
      return;
    }

    mounted.current = true;

    if (!attributes.sliderId) {
      setAttributes({ sliderId: randId() });
    }
  }, []);

  const slides = useSelect(
    (select) => select('core/block-editor').getBlock(clientId).innerBlocks,
  );

  const controls = (
    <InspectorControls>
      <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
        <ToggleControl
          // translators: [admin]
          label={__('Show Arrows', 'amnesty')}
          checked={attributes.hasArrows}
          onChange={(hasArrows) => setAttributes({ hasArrows })}
        />

        <ToggleControl
          // translators: [admin]
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
          // translators: [admin]
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
          // translators: [admin]
          label={__('Slider Title', 'amnesty')}
          onChange={(title) => setAttributes({ title })}
          value={attributes.title}
        />
      </PanelBody>
    </InspectorControls>
  );

  const nextSlide = () => setSelectedSlide(selectedSlide + 1);
  const prevSlide = () => setSelectedSlide(selectedSlide - 1);
  const classes = classnames(className, 'slider', `timeline-${attributes.style}`);

  return (
    <>
      {controls}
      <div className={classes}>
        {!!attributes.title && (
          <div className="slider-title">
            <RichText
              tagname="span"
              // translators: [admin]
              placeholder={__('Slider Title', 'amnesty')}
              onChange={(title) => setAttributes({ title })}
              value={attributes.title}
              allowedFormats={[]}
              keepPlaceholderOnFocus={true}
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
            <InnerBlocks template={getLayoutTemplate(attributes.quantity)} templateLock="all" />
          </div>
        </div>
        <nav className="slider-nav">
          {slides.map((slide, index) => {
            if (selectedSlide === index) {
              return (
                <div key={slide.title} className="slider-navButton is-active">
                  {/* translators: [admin] */}
                  <span>{slide.title || __('No Title', 'amnesty')}</span>
                </div>
              );
            }

            return (
              <button key={slide.title} className="slider-navButton" onClick={nextSlide}>
                {/* translators: [admin] */}
                {slide.title || __('No Title', 'amnesty')}
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
};

export default edit;
