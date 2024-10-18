import classnames from 'classnames';
import PostMediaSelector from '../../components/PostMediaSelector';
import { randId } from '../../utils';

const { map, omit } = lodash;
const { InspectorControls, RichText, URLInputButton } = wp.blockEditor;
const { Button, PanelBody, SelectControl, TextControl, ToggleControl, TextareaControl } =
  wp.components;
const { useEffect, useRef, useState } = wp.element;
const { __ } = wp.i18n;

const emptySlide = {
  alignment: '',
  background: '',
  callToActionLink: '',
  callToActionText: '',
  content: '',
  heading: '',
  hideContent: false,
  id: '',
  imageId: '',
  imageUrl: '',
  subheading: '',
  timelineContent: '',
  title: '',
};

const alignmentOptions = [
  /* translators: [admin] text alignment. for RTL languages, localise as 'Right' */
  { label: __('Left', 'amnesty'), value: '' },
  /* translators: [admin] text alignment. */
  { label: __('Centre', 'amnesty'), value: 'center' },
  /* translators: [admin] text alignment. for RTL languages, localise as 'Left' */
  { label: __('Right', 'amnesty'), value: 'right' },
];

const timelineStyleOptions = [
  // translators: [admin]
  { label: __('Dark', 'amnesty'), value: 'dark' },
  // translators: [admin]
  { label: __('Light', 'amnesty'), value: 'light' },
];

const backgroundOptions = [
  // translators: [admin]
  { label: __('Opaque', 'amnesty'), value: '' },
  // translators: [admin]
  { label: __('Translucent', 'amnesty'), value: 'opaque' },
  // translators: [admin]
  { label: __('Transparent', 'amnesty'), value: 'transparent' },
];

const edit = ({ attributes, setAttributes }) => {
  const [selectedSlide, setSelectedSlide] = useState(0);
  const [sizes, setSizes] = useState({});
  const mounted = useRef();

  useEffect(() => {
    if (mounted?.current) {
      return;
    }

    mounted.current = true;

    if (!attributes.sliderId) {
      setAttributes({ sliderId: randId() });
    }

    attributes.slides.forEach((slide, index) => {
      if (!slide.id) {
        createUpdateSlideAttribute(index)('id')(randId());
      }

      if (slide.imageId && !sizes[slide.imageId]) {
        requestSizes(index);
      }
    });
  }, []);

  // @TODO: rewrite
  const createUpdateSlideAttribute = (index) => (key) => (value) =>
    setAttributes({
      slides: [
        ...attributes.slides.slice(0, Math.max(0, index)),
        {
          ...attributes.slides[index],
          [key]: value,
        },
        ...attributes.slides.slice(index + 1, attributes.slides.length),
      ],
    });

  // @TODO: rewrite
  const requestSizes = (index) => {
    const { attributes } = this.props;
    const { slides } = attributes;
    const { imageId } = slides[index];

    const update = (sizes) => this.setState({ sizes: { ...this.state.sizes, [imageId]: sizes } });

    wp.apiRequest({ path: `/wp/v2/media/${imageId}` }).then((resp) =>
      update(resp.media_details.sizes),
    );
  };

  // @TODO: rewrite
  const createUpdateImage = (index) =>
  ({ id: imageId = false, source_url: imageUrl = false, media_details: { sizes } = {} } = {}) => {
    setSizes({ ...sizes, [imageId]: sizes });

    const removeSizes = (obj) => omit(obj, 'sizes');
    setAttributes({
      slides: map(
        [
          ...attributes.slides.slice(0, Math.max(0, index)),
          {
            ...attributes.slides[index],
            imageId,
            imageUrl,
          },
          ...attributes.slides.slice(index + 1, attributes.slides.length),
        ],
        removeSizes,
      ),
    });
  };

  // @TODO: rewrite
  const deleteSlide = (index) => {
    if (index === attributes.slides.length - 1) {
      setSelectedSlide(index - 1);
    }

    setAttributes({
      slides: [
        ...attributes.slides.slice(0, Math.max(0, index)),
        ...attributes.slides.slice(index + 1, attributes.slides.length),
      ],
    });
  };

  // @TODO: rewrite
  const addSlide = () => {
    setSelectedSlide(attributes.slides.length);

    setAttributes({
      slides: [
        ...attributes.slides,
        {
          ...emptySlide,
          id: randId(),
        },
      ],
    });
  };

  // @TODO: rewrite
  const createDeleteSlide = (index) => () => deleteSlide(index);

  const selectSlide = (index) => setSelectedSlide(index);
  const createSelectSlide = (index) => () => selectSlide(index);

  const initiateDelete = () => {
    // eslint-disable-next-line no-restricted-globals, no-alert
    const response = confirm(
      /* translators: [admin] */
      __(
        'Do you wish to delete this slide? This action is irreversible',
        'amnesty',
      ),
    );

    if (response) {
      deleteSlide(selectedSlide);
    }
  };

  const nextSlide = () => setSelectedSlide(
    selectedSlide === attributes.slides.length ? 0 : selectedSlide + 1,
  );

  const prevSlide = () => setSelectedSlide(
    selectedSlide === 0 ? attributes.slides.length - 1 : selectedSlide - 1,
  );

  const shouldShowSliderTitle = () => ! ! attributes.sliderTitle;

  const currentSlide = attributes.slides[selectedSlide];
  const updateSlide = createUpdateSlideAttribute(selectedSlide);

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
                  'By disabling this you will hide the content in *ALL* slides. To disable this on only one slide, select the desired slide and toggle the "Hide Content" field in the "Slide Options" panel.',
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
                  'Hide the tabs on the front end, these will still show in the panel to allow you to navigate through each slide.',
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
          onChange={(sliderTitle) => setAttributes({ sliderTitle })}
          value={attributes.sliderTitle}
        />
        <SelectControl
          // translators: [admin]
          label={__('Timeline Style', 'amnesty')}
          value={attributes.timelineCaptionStyle}
          options={timelineStyleOptions}
          onChange={(timelineCaptionStyle) => setAttributes({ timelineCaptionStyle })}
        />
      </PanelBody>
      {attributes.slides.length > 0 && (
        <PanelBody title={/* translators: [admin] */ __('Slide Options', 'amnesty')}>
          <TextControl
            // translators: [admin]
            label={__('Slide Title', 'amnesty')}
            onChange={updateSlide('title')}
            value={currentSlide.title}
          />
          <TextareaControl
            // translators: [admin]
            label={__('Slide Timeline Text', 'amnesty')}
            onChange={updateSlide('timelineContent')}
            value={currentSlide.timelineContent}
          />
          <label style={{ display: 'block', marginBottom: '5px' }}>
            {/* translators: [admin] */ __('Slide Background', 'amnesty')}
          </label>
          <PostMediaSelector
            mediaId={currentSlide.imageId}
            onUpdate={this.createUpdateImage(selectedSlide)}
          />
          <hr />
          <SelectControl
            // translators: [admin]
            label={__('Content Alignment', 'amnesty')}
            value={currentSlide.alignment}
            options={alignmentOptions}
            onChange={updateSlide('alignment')}
          />
          <SelectControl
            // translators: [admin]
            label={__('Background Style', 'amnesty')}
            value={currentSlide.background}
            options={backgroundOptions}
            onChange={updateSlide('background')}
          />
          <ToggleControl
            // translators: [admin]
            label={__('Hide Content', 'amnesty')}
            checked={currentSlide.hideContent}
            onChange={updateSlide('hideContent')}
            help={
              <span>
                {
                  /* translators: [admin] */ __(
                    'By enabling this you will hide the content on *THIS* slide. To disable content on all slides go to the "Options" and toggle the "Has Content" field.',
                    'amnesty',
                  )
                }
              </span>
            }
          />
          <hr />
          <Button isDestructive isLink onClick={initiateDelete}>
            {/* translators: [admin] */ __('Remove Slide', 'amnesty')}
          </Button>
          <p>
            <em>
              <small>{/* translators: [admin] */ __('This is irreversible.', 'amnesty')}</small>
            </em>
          </p>
        </PanelBody>
      )}
    </InspectorControls>
  );

  return (
    <>
      {controls}
      <div>
        <div className={`slider timeline-${attributes.timelineCaptionStyle}`}>
          {shouldShowSliderTitle() && (
            <div className="slider-title">
              <RichText
                tagname="span"
                // translators: [admin]
                placeholder={__('(Slider Title)', 'amnesty')}
                onChange={this.createUpdateAttribute('sliderTitle')}
                value={attributes.sliderTitle}
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
              {attributes.slides.length === 0 && (
                <div className="slide">
                  <div className="slide-contentContainer">
                    <h1 className="slide-title">
                      {/* translators: [admin] */ __('Add a slide below.', 'amnesty')}
                    </h1>
                    <button className="btn btn--dark" onClick={addSlide}>
                      {/* translators: [admin] */ __('Add Slide', 'amnesty')}
                    </button>
                  </div>
                </div>
              )}
              {currentSlide && (
                <div
                  className={classnames({
                    slide: true,
                    [`is-${currentSlide.alignment}-aligned`]: !!currentSlide.alignment,
                    [`has-${currentSlide.background}-background`]: !!currentSlide.background,
                  })}
                  style={{ backgroundImage: `url(${currentSlide.imageUrl || ''})` }}
                >
                  {currentSlide.timelineContent && (
                    <div className="slide-timelineContent">
                      <div className="slide-timelineContent-inner">
                        <RichText
                          tagname="span"
                          // translators: [admin]
                          placeholder={__('(TimeLine Content)', 'amnesty')}
                          value={currentSlide.timelineContent}
                          onChange={updateSlide('timelineContent')}
                          allowedFormats={[]}
                          keepPlaceholderOnFocus={true}
                          format="string"
                        />
                      </div>
                    </div>
                  )}
                  {!currentSlide.hideContent && attributes.hasContent && (
                    <div className="slide-contentContainer">
                      <h1 className="slide-title">
                        <RichText
                          tagname="span"
                          // translators: [admin]
                          placeholder={__('(Heading)', 'amnesty')}
                          value={currentSlide.heading}
                          onChange={updateSlide('heading')}
                          allowedFormats={[]}
                          keepPlaceholderOnFocus={true}
                          format="string"
                        />
                      </h1>
                      <h2 className="slide-subtitle">
                        <RichText
                          tagname="span"
                          // translators: [admin]
                          placeholder={__('(Sub-Heading)', 'amnesty')}
                          value={currentSlide.subheading}
                          onChange={updateSlide('subheading')}
                          allowedFormats={[]}
                          keepPlaceholderOnFocus={true}
                          format="string"
                        />
                      </h2>
                      <div className="slide-content">
                        <RichText
                          tagname="p"
                          // translators: [admin]
                          placeholder={__('(Content)', 'amnesty')}
                          value={currentSlide.content}
                          onChange={updateSlide('content')}
                          allowedFormats={[]}
                          keepPlaceholderOnFocus={true}
                        />
                      </div>
                      <div className="slide-callToAction">
                        <div className="btn">
                          <RichText
                            tagname="span"
                            // translators: [admin]
                            placeholder={__('(Button Text)', 'amnesty')}
                            value={currentSlide.callToActionText}
                            onChange={updateSlide('callToActionText')}
                            allowedFormats={[]}
                            keepPlaceholderOnFocus={true}
                            format="string"
                          />
                        </div>
                        <URLInputButton
                          url={currentSlide.callToActionLink}
                          onChange={updateSlide('callToActionLink')}
                        />
                      </div>
                    </div>
                  )}
                </div>
              )}
            </div>
          </div>
          <nav className="slider-nav">
            {attributes.slides.length > 0 &&
              attributes.slides.map((slide, index) => {
                const slideTitle = slide.title && slide.title !== '';

                if (selectedSlide === index) {
                  return (
                    <div key={slide.title} className="slider-navButton is-active">
                      <span>
                        {slideTitle
                          ? slide.title
                          : /* translators: [admin] */ __('(No Title)', 'amnesty')}
                      </span>
                    </div>
                  );
                }

                return (
                  <button
                    key={slide.title}
                    className="slider-navButton"
                    onClick={this.createSelectSlide(index)}
                  >
                    {slideTitle
                      ? slide.title
                      : /* translators: [admin] */ __('(No Title)', 'amnesty')}
                  </button>
                );
              })}

            <button className="slider-navButton" onClick={addSlide}>
              {/* translators: [admin] */ __('Add Slide', 'amnesty')}
            </button>
          </nav>
        </div>
      </div>
    </>
  );
};

export default edit;
