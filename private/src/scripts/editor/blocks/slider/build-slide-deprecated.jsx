import classnames from 'classnames';
import { brToP, trimBr } from '../utils';

const { __ } = wp.i18n;

export default function buildSlideDeprecated(slide, hasContent, sliderId) {
  const shouldShowContent = () => !slide.hideContent && hasContent;
  const shouldShowButton = () => slide.callToActionText && slide.callToActionLink;
  const shouldShowToggle = () =>
    slide.content || (slide.callToActionText && slide.callToActionLink);

  const getUrl = (size) => {
    const obj = slide.sizes[size] || slide.sizes.full || { source_url: '' };
    return encodeURI(obj.source_url);
  };

  const style =
    sliderId && slide.sizes && Object.keys(slide.sizes).length > 0
      ? {}
      : {
          backgroundImage: `url(${slide.imageUrl || ''})`,
        };

  let cssBlock = '';
  if (sliderId && slide.sizes && Object.keys(slide.sizes).length > 0) {
    const selector = `#slider-${sliderId} #slide-${slide.id}`;

    cssBlock = `
      ${selector}{background-image: url("${getUrl('hero-sm')}")}
      @media screen and (min-width:770px){${selector}{background-image: url("${getUrl(
        'hero-md',
      )}")}}
      @media screen and (min-width:1444px){${selector}{background-image: url("${getUrl(
        'hero-lg',
      )}")}}
    `;
  }

  const slideClasses = classnames('slide', {
    [`is-${slide.alignment}-aligned`]: !!slide.alignment,
    [`has-${slide.background}-background`]: !!slide.background,
  });

  const content = { __html: brToP(slide.content) };

  return (
    <div id={`slide-${slide.id}`} className={slideClasses} style={style}>
      <style>{cssBlock}</style>
      {shouldShowContent() && (
        <div
          className="slide-contentWrapper"
          // translators: [admin]
          data-tooltip={__('Tap here to return to gallery', 'amnesty')}
        >
          <div className="slide-contentContainer">
            {slide.heading && <h1 className="slide-title">{trimBr(slide.heading)}</h1>}
            {slide.subheading && <h2 className="slide-subtitle">{trimBr(slide.subheading)}</h2>}
            <div className="slide-content">
              {slide.content && <div dangerouslySetInnerHTML={content}></div>}
              {shouldShowButton() && (
                <a className="btn" href={slide.callToActionLink}>
                  {trimBr(slide.callToActionText)}
                </a>
              )}
              {shouldShowToggle() && (
                // translators: [admin]
                <button className="slider-toggleContent">{__('Toggle Content', 'amnesty')}</button>
              )}
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
