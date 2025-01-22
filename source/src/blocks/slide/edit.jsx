import classnames from 'classnames';
import {
  InspectorControls,
  RichText,
  URLInputButton,
  useBlockProps,
} from '@wordpress/block-editor';
import {
  PanelBody,
  SelectControl,
  TextareaControl,
  TextControl,
  ToggleControl,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import PostMediaSelector from '../../components/PostMediaSelector.jsx';
import { fetchMediaUrl, randId } from '../../utils';

const backgroundOptions = [
  { label: __('Opaque', 'amnesty'), value: '' },
  { label: __('Translucent', 'amnesty'), value: 'opaque' },
  { label: __('Transparent', 'amnesty'), value: 'transparent' },
];

export default function Edit({ attributes, className, clientId, context, setAttributes }) {
  const [mediaUrl, setMediaUrl] = useState('');
  const mounted = useRef();

  const { getSelectedSlide, getSlider } = useSelect((select) => select('amnesty/blocks'));
  const selectedSlide = getSelectedSlide(getSlider(clientId));
  // eslint-disable-next-line no-console
  console.log({ selectedSlide, clientId }); // @TODO: this doesn't work

  useEffect(() => {
    if (!mounted.current) {
      mounted.current = true;
      if (!attributes.id) {
        setAttributes({ id: randId() });
      }
    }
  }, [attributes.id, setAttributes]);

  useEffect(() => {
    fetchMediaUrl(attributes.imageId, setMediaUrl);
  }, [attributes.imageId]);

  const controls = () => (
    <InspectorControls>
      <PanelBody title={__('Options', 'amnesty')}>
        <TextControl
          label={__('Slide Title', 'amnesty')}
          onChange={(title) => setAttributes({ title })}
          value={attributes.title}
        />
        <TextareaControl
          label={__('Slide Timeline Text', 'amnesty')}
          onChange={(timelineContent) => setAttributes({ timelineContent })}
          value={attributes.timelineContent}
        />
        <label style={{ display: 'block', marginBottom: '5px' }}>
          {__('Slide Background', 'amnesty')}
        </label>
        <PostMediaSelector
          mediaId={attributes.imageId}
          onUpdate={(imageId) => setAttributes({ imageId })}
        />
        <hr />
        <SelectControl
          label={__('Background Style', 'amnesty')}
          value={attributes.background}
          options={backgroundOptions}
          onChange={(background) => setAttributes({ background })}
        />
        <ToggleControl
          label={__('Hide Content', 'amnesty')}
          checked={attributes.hideContent}
          onChange={(hideContent) => setAttributes({ hideContent })}
          help={
            <span>
              {__(
                'By enabling this you will hide the content on *THIS* slide. To disable content on all slides go to the "Options" and toggle the "Has Content" field.',
                'amnesty',
              )}
            </span>
          }
        />
      </PanelBody>
    </InspectorControls>
  );

  const classes = classnames(className, 'slide', {
    [`has-${attributes.background}-background`]: !!attributes.background,
  });

  const blockProps = useBlockProps({
    className: classes,
  });

  const style = {};
  if (mediaUrl) {
    style.backgroundImage = `url("${mediaUrl}")`;
  }

  return (
    <>
      {controls()}
      <div {...blockProps} style={style}>
        {attributes.timelineContent && (
          <div className="slide-timelineContent">
            <div className="slide-timelineContent-inner">
              <RichText
                tagname="span"
                placeholder={__('TimeLine Content', 'amnesty')}
                value={attributes.timelineContent}
                onChange={(timelineContent) => setAttributes({ timelineContent })}
                allowedFormats={[]}
                format="string"
                aria-label={__('Timeline content field', 'amnesty')}
              />
            </div>
          </div>
        )}
        {!attributes.hideContent && context?.['amnesty-core/slider/hasContent'] && (
          <div className="slide-contentContainer">
            <h1 className="slide-title">
              <RichText
                tagname="span"
                placeholder={__('Heading', 'amnesty')}
                value={attributes.heading}
                onChange={(heading) => setAttributes({ heading })}
                allowedFormats={[]}
                format="string"
              />
            </h1>
            <h2 className="slide-subtitle">
              <RichText
                tagname="span"
                placeholder={__('Sub-Heading', 'amnesty')}
                value={attributes.subheading}
                onChange={(subheading) => setAttributes({ subheading })}
                allowedFormats={[]}
                format="string"
              />
            </h2>
            <div className="slide-content">
              <RichText
                tagname="p"
                placeholder={__('Content', 'amnesty')}
                value={attributes.content}
                onChange={(content) => setAttributes({ content })}
                allowedFormats={[]}
              />
            </div>
            <div className="slide-callToAction">
              <div className="btn">
                <RichText
                  tagname="span"
                  placeholder={__('Button Text', 'amnesty')}
                  value={attributes.callToActionText}
                  onChange={(callToActionText) => setAttributes({ callToActionText })}
                  allowedFormats={[]}
                  format="string"
                />
              </div>
              <URLInputButton
                url={attributes.callToActionLink}
                onChange={(callToActionLink) => setAttributes({ callToActionLink })}
              />
            </div>
          </div>
        )}
      </div>
    </>
  );
}
