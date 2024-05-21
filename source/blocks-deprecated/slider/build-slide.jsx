import classnames from 'classnames';
import { brToP, trimBr } from '../utils';

const { __ } = wp.i18n;

export default class SlideBuilder {
  build(slide, hasContent, sliderId) {
    this.slide = slide;
    this.hasContent = hasContent;
    this.sliderId = sliderId;

    return this.render();
  }

  shouldShowContent() {
    return !this.slide.hideContent && this.hasContent;
  }

  shouldShowButton() {
    return !!(this.slide.callToActionText && this.slide.callToActionLink);
  }

  shouldShowToggle() {
    return !!brToP(this.slide.content) || this.shouldShowButton();
  }

  hasInnerContent() {
    return this.shouldShowButton() || this.shouldShowToggle();
  }

  getUrl(size) {
    const obj = this.slide.sizes[size] || this.slide.sizes.full || { source_url: '' };
    return encodeURI(obj.source_url);
  }

  hasImageSizes() {
    const { sizes } = this.slide;

    return this.sliderId && sizes && Object.keys(sizes).length > 0;
  }

  getBackground() {
    const { imageUrl } = this.slide;

    if (!this.hasImageSizes() || !imageUrl) {
      return {};
    }

    return {
      backgroundImage: `url("${imageUrl}")`,
    };
  }

  getCssBlock() {
    if (!this.hasImageSizes()) {
      return '';
    }

    const selector = `#slider-${this.sliderId} #slide-${this.slide.id}`;

    // concating template strings to avoid unsightly whitespace.
    return (
      `${selector}{background-image: url("${this.getUrl('hero-sm')}")}` +
      `@media screen and (min-width:770px){${selector}{background-image: url("${this.getUrl(
        'hero-md',
      )}")}}` +
      `@media screen and (min-width:1444px){${selector}{background-image: url("${this.getUrl(
        'hero-lg',
      )}")}}`
    );
  }

  getSlideClasses() {
    const { alignment, background } = this.slide;

    return classnames('slide', {
      [`is-${alignment}-aligned`]: !!alignment,
      [`has-${background}-background`]: !!background,
    });
  }

  getHtmlContent() {
    return {
      __html: brToP(this.slide.content),
    };
  }

  render() {
    const { id, heading, subheading, callToActionLink, callToActionText, timelineContent } =
      this.slide;

    const content = this.getHtmlContent();

    return (
      <div id={`slide-${id}`} className={this.getSlideClasses()}>
        <style>{this.getCssBlock()}</style>
        {timelineContent && (
          <div className="slide-timelineContent">
            <div className="slide-timelineContent-inner">{timelineContent}</div>
          </div>
        )}
        {this.shouldShowContent() && (
          <div
            className="slide-contentWrapper"
            // translators: [admin]
            data-tooltip={__('Tap here to return to gallery', 'amnesty')}
          >
            <div className="slide-contentContainer">
              {heading && <h1 className="slide-title">{trimBr(heading)}</h1>}
              {subheading && <h2 className="slide-subtitle">{trimBr(subheading)}</h2>}
              {this.hasInnerContent() && (
                <div className="slide-content">
                  {/* eslint-disable-next-line no-underscore-dangle */}
                  {content.__html && <div dangerouslySetInnerHTML={content}></div>}
                  {this.shouldShowButton() && (
                    <a className="btn" href={callToActionLink}>
                      {trimBr(callToActionText)}
                    </a>
                  )}
                  {this.shouldShowToggle() && (
                    <button className="slider-toggleContent">
                      {/* translators: [admin] */ __('Toggle Content', 'amnesty')}
                    </button>
                  )}
                </div>
              )}
            </div>
          </div>
        )}
      </div>
    );
  }

  buildDeprecated(slide, hasContent, sliderId) {
    this.slide = slide;
    this.hasContent = hasContent;
    this.sliderId = sliderId;

    return this.renderDeprecated();
  }

  buildVersionTwoDeprecated(slide, hasContent, sliderId) {
    this.slide = slide;
    this.hasContent = hasContent;
    this.sliderId = sliderId;

    return this.renderVersionTwoDeprecated();
  }

  buildTimelineItem(slide, hasContent, sliderId) {
    this.slide = slide;
    this.hasContent = hasContent;
    this.sliderId = sliderId;

    return this.renderTimelineItem();
  }

  renderTimelineItem() {
    const { timelineContent } = this.slide;

    return <div>{timelineContent}</div>;
  }

  renderVersionTwoDeprecated() {
    const { id, heading, subheading, callToActionLink, callToActionText } = this.slide;

    const content = this.getHtmlContent();

    return (
      <div id={`slide-${id}`} className={this.getSlideClasses()}>
        <style>{this.getCssBlock()}</style>
        {this.shouldShowContent() && (
          <div
            className="slide-contentWrapper"
            // translators: [admin]
            data-tooltip={__('Tap here to return to gallery', 'amnesty')}
          >
            <div className="slide-contentContainer">
              {heading && <h1 className="slide-title">{trimBr(heading)}</h1>}
              {subheading && <h2 className="slide-subtitle">{trimBr(subheading)}</h2>}
              {this.hasInnerContent() && (
                <div className="slide-content">
                  {/* eslint-disable-next-line no-underscore-dangle */}
                  {content.__html && <div dangerouslySetInnerHTML={content}></div>}
                  {this.shouldShowButton() && (
                    <a className="btn" href={callToActionLink}>
                      {trimBr(callToActionText)}
                    </a>
                  )}
                  {this.shouldShowToggle() && (
                    <button className="slider-toggleContent">
                      {/* translators: [admin] */ __('Toggle Content', 'amnesty')}
                    </button>
                  )}
                </div>
              )}
            </div>
          </div>
        )}
      </div>
    );
  }

  renderDeprecated() {
    const { id, heading, subheading, callToActionLink, callToActionText } = this.slide;

    const content = this.getHtmlContent();

    return (
      <div id={`slide-${id}`} className={this.getSlideClasses()} style={this.getBackground()}>
        <style>{this.getCssBlock()}</style>
        {this.shouldShowContent() && (
          <div
            className="slide-contentWrapper"
            // translators: [admin]
            data-tooltip={__('Tap here to return to gallery', 'amnesty')}
          >
            <div className="slide-contentContainer">
              {heading && <h1 className="slide-title">{trimBr(heading)}</h1>}
              {subheading && <h2 className="slide-subtitle">{trimBr(subheading)}</h2>}
              {this.hasInnerContent() && (
                <div className="slide-content">
                  {/* eslint-disable-next-line no-underscore-dangle */}
                  {content.__html && <div dangerouslySetInnerHTML={content}></div>}
                  {this.shouldShowButton() && (
                    <a className="btn" href={callToActionLink}>
                      {trimBr(callToActionText)}
                    </a>
                  )}
                  {this.shouldShowToggle() && (
                    <button className="slider-toggleContent">
                      {/* translators: [admin] */ __('Toggle Content', 'amnesty')}
                    </button>
                  )}
                </div>
              )}
            </div>
          </div>
        )}
      </div>
    );
  }
}
