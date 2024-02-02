import classnames from 'classnames';
import PostMediaSelector from '../../components/PostMediaSelector.jsx';
import { randId } from '../utils';

const { map, omit } = lodash;
const { InspectorControls, RichText, URLInputButton } = wp.blockEditor;
const { Button, PanelBody, SelectControl, TextControl, ToggleControl, TextareaControl } =
  wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;

class DisplayComponent extends Component {
  static emptySlide = {
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

  static alignmentOptions = [
    /* translators: [admin] text alignment. for RTL languages, localise as 'Right' */
    { label: __('Left', 'amnesty'), value: '' },
    /* translators: [admin] text alignment. */
    { label: __('Centre', 'amnesty'), value: 'center' },
    /* translators: [admin] text alignment. for RTL languages, localise as 'Left' */
    { label: __('Right', 'amnesty'), value: 'right' },
  ];

  static timelineStyleOptions = [
    // translators: [admin]
    { label: __('Dark', 'amnesty'), value: 'dark' },
    // translators: [admin]
    { label: __('Light', 'amnesty'), value: 'light' },
  ];

  static backgroundOptions = [
    // translators: [admin]
    { label: __('Opaque', 'amnesty'), value: '' },
    // translators: [admin]
    { label: __('Translucent', 'amnesty'), value: 'opaque' },
    // translators: [admin]
    { label: __('Transparent', 'amnesty'), value: 'transparent' },
  ];

  state = {
    selectedSlide: 0,
    sizes: {},
  };

  static shouldShowSliderTitle = (attributes) => {
    if (attributes.sliderTitle) {
      return true;
    }
    return false;
  };

  componentDidMount() {
    const { attributes, setAttributes } = this.props;

    if (!attributes.sliderId) {
      setAttributes({
        sliderId: randId(),
      });
    }

    attributes.slides.forEach((slide, index) => {
      if (!slide.id) {
        this.createUpdateSlideAttribute(index)('id')(randId());
      }

      if (slide.imageId && !this.state.sizes[slide.imageId]) {
        this.requestSizes(index);
      }
    });
  }

  requestSizes(index) {
    const { attributes } = this.props;
    const { slides } = attributes;
    const { imageId } = slides[index];

    const update = (sizes) => this.setState({ sizes: { ...this.state.sizes, [imageId]: sizes } });

    wp.apiRequest({ path: `/wp/v2/media/${imageId}` }).then((resp) =>
      update(resp.media_details.sizes),
    );
  }

  /**
   * Higher order component that takes the attribute key,
   * this then returns a function which takes a value,
   * when called it updates the attribute with the key.
   * @param key
   * @returns {function(*): *}
   */
  createUpdateAttribute = (key) => (value) => this.props.setAttributes({ [key]: value });

  createUpdateSlideAttribute = (index) => (key) => (value) =>
    this.props.setAttributes({
      slides: [
        ...this.props.attributes.slides.slice(0, Math.max(0, index)),
        {
          ...this.props.attributes.slides[index],
          [key]: value,
        },
        ...this.props.attributes.slides.slice(index + 1, this.props.attributes.slides.length),
      ],
    });

  createUpdateImage =
    (index) =>
    ({ id: imageId = false, source_url: imageUrl = false, media_details: { sizes } = {} } = {}) => {
      this.setState({ sizes: { ...this.state.sizes, [imageId]: sizes } });
      const removeSizes = (obj) => omit(obj, 'sizes');
      this.props.setAttributes({
        slides: map(
          [
            ...this.props.attributes.slides.slice(0, Math.max(0, index)),
            {
              ...this.props.attributes.slides[index],
              imageId,
              imageUrl,
            },
            ...this.props.attributes.slides.slice(index + 1, this.props.attributes.slides.length),
          ],
          removeSizes,
        ),
      });
    };

  deleteSlide = (index) => {
    if (index === this.props.attributes.slides.length - 1) {
      this.setState({
        selectedSlide: index - 1,
      });
    }

    this.props.setAttributes({
      slides: [
        ...this.props.attributes.slides.slice(0, Math.max(0, index)),
        ...this.props.attributes.slides.slice(index + 1, this.props.attributes.slides.length),
      ],
    });
  };

  addSlide = () => {
    this.setState({
      selectedSlide: this.props.attributes.slides.length,
    });

    this.props.setAttributes({
      slides: [
        ...this.props.attributes.slides,
        {
          ...DisplayComponent.emptySlide,
          id: randId(),
        },
      ],
    });
  };

  createDeleteSlide = (index) => () => this.deleteSlide(index);

  selectSlide = (index) =>
    this.setState({
      selectedSlide: index,
    });

  createSelectSlide = (index) => () => this.selectSlide(index);

  initiateDelete = () => {
    if (
      // eslint-disable-next-line no-restricted-globals, no-alert
      confirm(
        /* translators: [admin] */ __(
          'Do you wish to delete this slide? This action is irreversible',
          'amnesty',
        ),
      )
    ) {
      this.deleteSlide(this.state.selectedSlide);
    }
  };

  nextSlide = () =>
    this.setState({
      selectedSlide:
        this.state.selectedSlide === this.props.attributes.slides.length - 1
          ? 0
          : this.state.selectedSlide + 1,
    });

  prevSlide = () =>
    this.setState({
      selectedSlide:
        this.state.selectedSlide === 0
          ? this.props.attributes.slides.length - 1
          : this.state.selectedSlide - 1,
    });

  render() {
    const { attributes } = this.props;
    const { selectedSlide } = this.state;

    const currentSlide = attributes.slides[selectedSlide];
    const updateSlide = this.createUpdateSlideAttribute(selectedSlide);

    const controls = (
      <InspectorControls>
        <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
          <ToggleControl
            // translators: [admin]
            label={__('Show Arrows', 'amnesty')}
            checked={attributes.hasArrows}
            onChange={this.createUpdateAttribute('hasArrows')}
          />

          <ToggleControl
            // translators: [admin]
            label={__('Has Content', 'amnesty')}
            checked={attributes.hasContent}
            onChange={this.createUpdateAttribute('hasContent')}
            help={
              <span>
                {
                  /* translators: [admin] */ __(
                    'By disabling this you will hide the content in *ALL* slides. To disable this on only one slide, select the desired slide and toggle the "Hide Content" field in the "Slide Options" panel.',
                    'amnesty',
                  )
                }
              </span>
            } // eslint-disable-line max-len
          />

          <ToggleControl
            // translators: [admin]
            label={__('Show Tabs', 'amnesty')}
            checked={attributes.showTabs}
            onChange={this.createUpdateAttribute('showTabs')}
            help={
              <span>
                {
                  /* translators: [admin] */ __(
                    'Hide the tabs on the front end, these will still show in the panel to allow you to navigate through each slide.',
                    'amnesty',
                  )
                }
              </span>
            } // eslint-disable-line max-len
          />
        </PanelBody>

        <PanelBody title={/* translators: [admin] */ __('Timeline Options', 'amnesty')}>
          <TextControl
            // translators: [admin]
            label={__('Slider Title', 'amnesty')}
            onChange={this.createUpdateAttribute('sliderTitle')}
            value={attributes.sliderTitle}
          />
          <SelectControl
            // translators: [admin]
            label={__('Timeline Style', 'amnesty')}
            value={attributes.timelineCaptionStyle}
            options={DisplayComponent.timelineStyleOptions}
            onChange={this.createUpdateAttribute('timelineCaptionStyle')}
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
            <label
              style={{
                display: 'block',
                marginBottom: '5px',
              }}
            >
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
              options={DisplayComponent.alignmentOptions}
              onChange={updateSlide('alignment')}
            />
            <SelectControl
              // translators: [admin]
              label={__('Background Style', 'amnesty')}
              value={currentSlide.background}
              options={DisplayComponent.backgroundOptions}
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
              } // eslint-disable-line max-len
            />

            <hr />
            <Button isDestructive isLink onClick={this.initiateDelete}>
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
      <Fragment>
        {controls}
        <div>
          <div className={`slider timeline-${attributes.timelineCaptionStyle}`}>
            {DisplayComponent.shouldShowSliderTitle(attributes) && (
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
                <Fragment>
                  <button onClick={this.nextSlide} className="slides-arrow slides-arrow--next">
                    {/* translators: [admin] */ __('Next', 'amnesty')}
                  </button>
                  <button onClick={this.prevSlide} className="slides-arrow slides-arrow--previous">
                    {/* translators: [admin] */ __('Previous', 'amnesty')}
                  </button>
                </Fragment>
              )}
              <div className="slides">
                {attributes.slides.length === 0 && (
                  <div className="slide">
                    <div className="slide-contentContainer">
                      <h1 className="slide-title">
                        {/* translators: [admin] */ __('Add a slide below.', 'amnesty')}
                      </h1>
                      <button className="btn btn--dark" onClick={this.addSlide}>
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
                    style={{
                      backgroundImage: `url(${currentSlide.imageUrl || ''})`,
                    }}
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

              <button className="slider-navButton" onClick={this.addSlide}>
                {/* translators: [admin] */ __('Add Slide', 'amnesty')}
              </button>
            </nav>
          </div>
        </div>
      </Fragment>
    );
  }
}

export default DisplayComponent;
