import classnames from 'classnames';

import { InspectorControls, MediaUpload, PlainText, URLInputButton } from '@wordpress/block-editor';
import { IconButton, PanelBody, SelectControl, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const setURL = (image) => {
  if (!image.sizes || !Object.prototype.hasOwnProperty.call(image.sizes, 'large')) {
    return image.url;
  }

  return image.sizes.large.url;
};

const setLargeURL = (image) => {
  if (!image.sizes || !Object.prototype.hasOwnProperty.call(image.sizes, 'action-wide@2x')) {
    return image.url;
  }

  return image.sizes['action-wide@2x'].url;
};

export default function Edit({ attributes, className, setAttributes }) {
  const classes = classnames('customCard', className, {
    'customCard--wide': attributes.style === 'wide',
    'is-centred': attributes.centred,
  });

  const buttonClasses = classnames('btn', 'btn--fill', 'btn--large');

  return (
    <>
      <InspectorControls>
        <PanelBody>
          <SelectControl
            /* translators: [admin] */
            label={__('Size', 'amnesty')}
            value={attributes.style}
            onChange={(style) => setAttributes({ style })}
            options={[
              /* translators: [admin] */
              { value: 'standard', label: __('Standard', 'amnesty') },
              /* translators: [admin] */
              { value: 'wide', label: __('Wide', 'amnesty') },
            ]}
          />
          <TextControl
            /* translators: [admin] */
            label={__('Scroll To Link', 'amnesty')}
            value={attributes.scrollLink}
            onChange={(scrollLink) => setAttributes({ scrollLink })}
          />
        </PanelBody>
      </InspectorControls>
      <figure className={classes}>
        <PlainText
          className="customCard-label"
          rows="1"
          /* translators: [admin] */
          placeholder={__('(Label)', 'amnesty')}
          value={attributes.label}
          onChange={(label) => setAttributes({ label })}
        />
        <div className="customCard-figure">
          <div className="linkList-options">
            {attributes.imageID ? (
              <IconButton
                icon="no-alt"
                /* translators: [admin] */
                label={__('Remove Image', 'amnesty')}
                onClick={() => setAttributes({ imageID: 0, imageURL: '', imageAlt: '' })}
              />
            ) : (
              <MediaUpload
                allowedTypes={['image']}
                value={attributes.imageID}
                onSelect={(media) =>
                  setAttributes({
                    imageID: media.id,
                    imageURL: setURL(media),
                    largeImageURL: setLargeURL(media),
                    imageAlt: media.alt,
                  })
                }
                render={({ open }) => <IconButton icon="format-image" onClick={open} />}
              />
            )}
          </div>
          {attributes.style === 'wide' && (
            <img
              className="customCard-image"
              src={attributes.largeImageURL}
              alt={attributes.imageAlt}
            />
          )}
          {attributes.style === 'standard' && (
            <img className="customCard-image" src={attributes.imageURL} alt={attributes.imageAlt} />
          )}
        </div>
        <figcaption className="customCard-content">
          <PlainText
            /* translators: [admin] */
            placeholder={__('Content', 'amnesty')}
            rows="3"
            value={attributes.content}
            onChange={(content) => setAttributes({ content })}
          />
          <PlainText
            className={buttonClasses}
            /* translators: [admin] */
            placeholder={__('Button Text', 'amnesty')}
            rows="1"
            value={attributes.linkText}
            onChange={(linkText) => setAttributes({ linkText })}
          />
          <URLInputButton url={attributes.link} onChange={(link) => setAttributes({ link })} />
        </figcaption>
      </figure>
    </>
  );
}
