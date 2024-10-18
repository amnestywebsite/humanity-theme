import classnames from 'classnames';
import blockAttributes from './attributes';
import { trimBr } from '../../utils';
import SlideBuilder from './build-slide.jsx';
import buildSlideDeprecated from './build-slide-deprecated.jsx';

const { Fragment } = wp.element;
const { __ } = wp.i18n;

const Builder = new SlideBuilder();

const v7 = {
  attributes: {
    sliderId: {
      type: 'string',
    },
    slides: {
      type: 'array',
      default: [],
    },
    hasArrows: {
      type: 'boolean',
      default: true,
    },
    showTabs: {
      type: 'boolean',
      default: true,
    },
    hasContent: {
      type: 'boolean',
      default: true,
    },
  },
  save: Object.assign(
    ({ attributes }) => {
      const { slides, hasContent, sliderId } = attributes;

      if (slides.length < 1) {
        return null;
      }

      return (
        <div id={`slider-${sliderId}`} className="slider">
          <div className="slides-container">
            <div className="slides">
              {slides.map((slide) =>
                Builder.buildVersionTwoDeprecated(slide, hasContent, sliderId),
              )}
            </div>
            {attributes.hasArrows && (
              <Fragment>
                <button className="slides-arrow slides-arrow--next" aria-hidden="true">
                  {/* translators: [admin] */ __('Next', 'amnesty')}
                </button>
                <button className="slides-arrow slides-arrow--previous" aria-hidden="true">
                  {/* translators: [admin] */ __('Previous', 'amnesty')}
                </button>
              </Fragment>
            )}
          </div>
          {attributes.showTabs && (
            <div className="slider-navContainer" aria-hidden="true">
              <nav className="slider-nav">
                {slides.map((slide, index) => (
                  <button key={slide.title} className="slider-navButton" data-slide-index={index}>
                    {slide.title}
                  </button>
                ))}
              </nav>
            </div>
          )}
        </div>
      );
    },
    { displayName: 'SliderBlockSave' },
  ),
};

const v6 = {
  attributes: {
    slides: {
      type: 'array',
      default: [],
    },
    hasArrows: {
      type: 'boolean',
      default: true,
    },
    showTabs: {
      type: 'boolean',
      default: true,
    },
    hasContent: {
      type: 'boolean',
      default: true,
    },
  },
  save: Object.assign(
    ({ attributes }) => {
      const { slides } = attributes;
      if (slides.length < 1) {
        return null;
      }

      return (
        <div className="slider">
          <div className="slides-container">
            <div className="slides">
              {slides.map((slide) => (
                <div
                  key={slide.heading}
                  className={classnames('slide', {
                    [`is-${slide.alignment}-aligned`]: !!slide.alignment,
                    [`has-${slide.background}-background`]: !!slide.background,
                  })}
                  style={{ backgroundImage: `url(${slide.imageUrl || ''})` }}
                >
                  {!slide.hideContent && attributes.hasContent && (
                    <div className="slide-contentContainer">
                      {slide.heading && <h1 className="slide-title">{trimBr(slide.heading)}</h1>}
                      {slide.subheading && (
                        <h2 className="slide-subtitle">{trimBr(slide.subheading)}</h2>
                      )}
                      <div className="slide-content">
                        {slide.content && <p>{slide.content}</p>}
                        {slide.callToActionText && slide.callToActionLink && (
                          <a className="btn btn--outline btn--ghost" href={slide.callToActionLink}>
                            {trimBr(slide.callToActionText)}
                          </a>
                        )}
                        <button className="slider-toggleContent">
                          {/* translators: [admin] */ __('Toggle Content', 'amnesty')}
                        </button>
                      </div>
                    </div>
                  )}
                </div>
              ))}
            </div>
            {attributes.hasArrows && (
              <Fragment>
                <button className="slides-arrow slides-arrow--next" aria-hidden="true">
                  {/* translators: [admin] */ __('Next', 'amnesty')}
                </button>
                <button className="slides-arrow slides-arrow--previous" aria-hidden="true">
                  {/* translators: [admin] */ __('Previous', 'amnesty')}
                </button>
              </Fragment>
            )}
          </div>
          {attributes.showTabs && (
            <div className="slider-navContainer" aria-hidden="true">
              <nav className="slider-nav">
                {slides.map((slide, index) => (
                  <button key={slide.title} className="slider-navButton" data-slide-index={index}>
                    {slide.title}
                  </button>
                ))}
              </nav>
            </div>
          )}
        </div>
      );
    },
    { displayName: 'SliderBlockSaveDeprecation1' },
  ),
};

const v5 = {
  attributes: blockAttributes,
  save: Object.assign(
    ({ attributes }) => {
      const { slides, sliderId } = attributes;
      if (slides.length < 1) {
        return null;
      }

      return (
        <div id={`slider-${sliderId}`} className="slider">
          <div className="slides-container">
            <div className="slides">
              {slides.map((slide) => {
                const style =
                  sliderId && slide.sizes && Object.keys(slide.sizes).length > 0
                    ? {}
                    : {
                        backgroundImage: `url(${slide.imageUrl || ''})`,
                      };

                let cssBlock = '';
                if (sliderId && slide.sizes && Object.keys(slide.sizes).length > 0) {
                  cssBlock = `
                #slider-${sliderId} #slide-${slide.id} {
                  background-image: url(${
                    (slide.sizes['hero-sm'] || slide.sizes.full || {}).source_url
                  });
                }

                @media screen and (min-width: 770px) {
                  #slider-${sliderId} #slide-${slide.id} {
                    background-image: url(${
                      (slide.sizes['hero-md'] || slide.sizes.full || {}).source_url
                    });
                  }
                }

                @media screen and (min-width: 1444px) {
                  #slider-${sliderId} #slide-${slide.id} {
                    background-image: url(${
                      (slide.sizes['hero-lg'] || slide.sizes.full || {}).source_url
                    });
                  }
                }
              `;
                }

                return (
                  <div
                    key={slide.id}
                    id={`slide-${slide.id}`}
                    className={classnames('slide', {
                      [`is-${slide.alignment}-aligned`]: !!slide.alignment,
                      [`has-${slide.background}-background`]: !!slide.background,
                    })}
                    style={style}
                  >
                    <style>{cssBlock}</style>
                    {!slide.hideContent && attributes.hasContent && (
                      <div className="slide-contentContainer">
                        {slide.heading && <h1 className="slide-title">{trimBr(slide.heading)}</h1>}
                        {slide.subheading && (
                          <h2 className="slide-subtitle">{trimBr(slide.subheading)}</h2>
                        )}
                        <div className="slide-content">
                          {slide.content && <p>{slide.content}</p>}
                          {slide.callToActionText && slide.callToActionLink && (
                            <a
                              className="btn btn--outline btn--ghost"
                              href={slide.callToActionLink}
                            >
                              {trimBr(slide.callToActionText)}
                            </a>
                          )}
                          <button className="slider-toggleContent">
                            {/* translators: [admin] */ __('Toggle Content', 'amnesty')}
                          </button>
                        </div>
                      </div>
                    )}
                  </div>
                );
              })}
            </div>
            {attributes.hasArrows && (
              <Fragment>
                <button className="slides-arrow slides-arrow--next" aria-hidden="true">
                  {/* translators: [admin] */ __('Next', 'amnesty')}
                </button>
                <button className="slides-arrow slides-arrow--previous" aria-hidden="true">
                  {/* translators: [admin] */ __('Previous', 'amnesty')}
                </button>
              </Fragment>
            )}
          </div>
          {attributes.showTabs && (
            <div className="slider-navContainer" aria-hidden="true">
              <nav className="slider-nav">
                {slides.map((slide, index) => (
                  <button key={slide.title} className="slider-navButton" data-slide-index={index}>
                    {slide.title}
                  </button>
                ))}
              </nav>
            </div>
          )}
        </div>
      );
    },
    { displayName: 'SliderBlockSaveDeprecation2' },
  ),
};

const v4 = {
  attributes: blockAttributes,
  save: Object.assign(
    ({ attributes }) => {
      const { slides, sliderId } = attributes;
      if (slides.length < 1) {
        return null;
      }

      return (
        <div id={`slider-${sliderId}`} className="slider">
          <div className="slides-container">
            <div className="slides">
              {slides.map((slide) => {
                const style =
                  sliderId && slide.sizes && Object.keys(slide.sizes).length > 0
                    ? {}
                    : {
                        backgroundImage: `url(${slide.imageUrl || ''})`,
                      };

                let cssBlock = '';
                if (sliderId && slide.sizes && Object.keys(slide.sizes).length > 0) {
                  cssBlock = `
                #slider-${sliderId} #slide-${slide.id} {
                  background-image: url(${
                    (slide.sizes['hero-sm'] || slide.sizes.full || {}).source_url
                  });
                }

                @media screen and (min-width: 770px) {
                  #slider-${sliderId} #slide-${slide.id} {
                    background-image: url(${
                      (slide.sizes['hero-md'] || slide.sizes.full || {}).source_url
                    });
                  }
                }

                @media screen and (min-width: 1444px) {
                  #slider-${sliderId} #slide-${slide.id} {
                    background-image: url(${
                      (slide.sizes['hero-lg'] || slide.sizes.full || {}).source_url
                    });
                  }
                }
              `;
                }

                return (
                  <div
                    id={`slide-${slide.id}`}
                    key={slide.id}
                    className={classnames('slide', {
                      [`is-${slide.alignment}-aligned`]: !!slide.alignment,
                      [`has-${slide.background}-background`]: !!slide.background,
                    })}
                    style={style}
                  >
                    <style>{cssBlock}</style>
                    {!slide.hideContent && attributes.hasContent && (
                      <div className="slide-contentContainer">
                        {slide.heading && <h1 className="slide-title">{trimBr(slide.heading)}</h1>}
                        {slide.subheading && (
                          <h2 className="slide-subtitle">{trimBr(slide.subheading)}</h2>
                        )}
                        <div className="slide-content">
                          {slide.content && <p>{slide.content}</p>}
                          {slide.callToActionText && slide.callToActionLink && (
                            <a className="btn btn--dark" href={slide.callToActionLink}>
                              {trimBr(slide.callToActionText)}
                            </a>
                          )}
                          <button className="slider-toggleContent">
                            {/* translators: [admin] */ __('Toggle Content', 'amnesty')}
                          </button>
                        </div>
                      </div>
                    )}
                  </div>
                );
              })}
            </div>
            {attributes.hasArrows && (
              <Fragment>
                <button className="slides-arrow slides-arrow--next" aria-hidden="true">
                  {/* translators: [admin] */ __('Next', 'amnesty')}
                </button>
                <button className="slides-arrow slides-arrow--previous" aria-hidden="true">
                  {/* translators: [admin] */ __('Previous', 'amnesty')}
                </button>
              </Fragment>
            )}
          </div>
          {attributes.showTabs && (
            <div className="slider-navContainer" aria-hidden="true">
              <nav className="slider-nav">
                {slides.map((slide, index) => (
                  <button key={slide.title} className="slider-navButton" data-slide-index={index}>
                    {slide.title}
                  </button>
                ))}
              </nav>
            </div>
          )}
        </div>
      );
    },
    { displayName: 'SliderBlockSaveDeprecation3' },
  ),
};

const v3 = {
  attributes: blockAttributes,
  save: Object.assign(
    ({ attributes }) => {
      const { slides, sliderId } = attributes;
      if (slides.length < 1) {
        return null;
      }

      return (
        <div id={`slider-${sliderId}`} className="slider">
          <div className="slides-container">
            <div className="slides">
              {slides.map((slide) => {
                const style =
                  sliderId && slide.sizes && Object.keys(slide.sizes).length > 0
                    ? {}
                    : {
                        backgroundImage: `url(${slide.imageUrl || ''})`,
                      };

                let cssBlock = '';
                if (sliderId && slide.sizes && Object.keys(slide.sizes).length > 0) {
                  cssBlock = `
                #slider-${sliderId} #slide-${slide.id} {
                  background-image: url(${
                    (slide.sizes['hero-sm'] || slide.sizes.full || {}).source_url
                  });
                }

                @media screen and (min-width: 770px) {
                  #slider-${sliderId} #slide-${slide.id} {
                    background-image: url(${
                      (slide.sizes['hero-md'] || slide.sizes.full || {}).source_url
                    });
                  }
                }

                @media screen and (min-width: 1444px) {
                  #slider-${sliderId} #slide-${slide.id} {
                    background-image: url(${
                      (slide.sizes['hero-lg'] || slide.sizes.full || {}).source_url
                    });
                  }
                }
              `;
                }

                return (
                  <div
                    id={`slide-${slide.id}`}
                    key={slide.id}
                    className={classnames('slide', {
                      [`is-${slide.alignment}-aligned`]: !!slide.alignment,
                      [`has-${slide.background}-background`]: !!slide.background,
                    })}
                    style={style}
                  >
                    <style>{cssBlock}</style>
                    {!slide.hideContent && attributes.hasContent && (
                      <div className="slide-contentContainer">
                        {slide.heading && <h1 className="slide-title">{trimBr(slide.heading)}</h1>}
                        {slide.subheading && (
                          <h2 className="slide-subtitle">{trimBr(slide.subheading)}</h2>
                        )}
                        <div className="slide-content">
                          {slide.content && <p>{slide.content}</p>}
                          {slide.callToActionText && slide.callToActionLink && (
                            <a className="btn btn--dark" href={slide.callToActionLink}>
                              {trimBr(slide.callToActionText)}
                            </a>
                          )}
                          {slide.content && (
                            <button className="slider-toggleContent">
                              {/* translators: [admin] */ __('Toggle Content', 'amnesty')}
                            </button>
                          )}
                        </div>
                      </div>
                    )}
                  </div>
                );
              })}
            </div>
            {attributes.hasArrows && (
              <Fragment>
                <button className="slides-arrow slides-arrow--next" aria-hidden="true">
                  {/* translators: [admin] */ __('Next', 'amnesty')}
                </button>
                <button className="slides-arrow slides-arrow--previous" aria-hidden="true">
                  {/* translators: [admin] */ __('Previous', 'amnesty')}
                </button>
              </Fragment>
            )}
          </div>
          {attributes.showTabs && (
            <div className="slider-navContainer" aria-hidden="true">
              <nav className="slider-nav">
                {slides.map((slide, index) => (
                  <button key={slide.title} className="slider-navButton" data-slide-index={index}>
                    {slide.title}
                  </button>
                ))}
              </nav>
            </div>
          )}
        </div>
      );
    },
    { displayName: 'SliderBlockSaveDeprecation4' },
  ),
};

const v2 = {
  attributes: blockAttributes,
  save: Object.assign(
    ({ attributes }) => {
      const { slides, sliderId } = attributes;

      if (slides.length < 1) {
        return null;
      }

      return (
        <div id={`slider-${sliderId}`} className="slider">
          <div className="slides-container">
            <div className="slides">
              {slides.map((slide) => buildSlideDeprecated(slide, attributes.hasContent, sliderId))}
            </div>
            {attributes.hasArrows && (
              <Fragment>
                <button className="slides-arrow slides-arrow--next" aria-hidden="true">
                  {/* translators: [admin] */ __('Next', 'amnesty')}
                </button>
                <button className="slides-arrow slides-arrow--previous" aria-hidden="true">
                  {/* translators: [admin] */ __('Previous', 'amnesty')}
                </button>
              </Fragment>
            )}
          </div>
          {attributes.showTabs && (
            <div className="slider-navContainer" aria-hidden="true">
              <nav className="slider-nav">
                {slides.map((slide, index) => (
                  <button key={slide.title} className="slider-navButton" data-slide-index={index}>
                    {slide.title}
                  </button>
                ))}
              </nav>
            </div>
          )}
        </div>
      );
    },
    { displayName: 'SliderBlockSaveDeprecation5' },
  ),
};

const v1 = {
  attributes: blockAttributes,
  save: Object.assign(
    ({ attributes }) => {
      const { slides, hasContent, sliderId } = attributes;

      if (slides.length < 1) {
        return null;
      }

      return (
        <div id={`slider-${sliderId}`} className="slider">
          <div className="slides-container">
            <div className="slides">
              {slides.map((slide) => Builder.buildDeprecated(slide, hasContent, sliderId))}
            </div>
            {attributes.hasArrows && (
              <Fragment>
                <button className="slides-arrow slides-arrow--next" aria-hidden="true">
                  {/* translators: [admin] */ __('Next', 'amnesty')}
                </button>
                <button className="slides-arrow slides-arrow--previous" aria-hidden="true">
                  {/* translators: [admin] */ __('Previous', 'amnesty')}
                </button>
              </Fragment>
            )}
          </div>
          {attributes.showTabs && (
            <div className="slider-navContainer" aria-hidden="true">
              <nav className="slider-nav">
                {slides.map((slide, index) => (
                  <button key={slide.title} className="slider-navButton" data-slide-index={index}>
                    {slide.title}
                  </button>
                ))}
              </nav>
            </div>
          )}
        </div>
      );
    },
    { displayName: 'SliderBlockSaveDeprecation6' },
  ),
};

const deprecated = [v7, v6, v5, v4, v3, v2, v1];

export default deprecated;
