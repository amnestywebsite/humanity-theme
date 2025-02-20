import classnames from 'classnames';

const { InspectorControls, PlainText, BlockAlignmentToolbar, BlockControls } = wp.blockEditor;
const { PanelBody, SelectControl, ToggleControl } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;

// https://developer.twitter.com/en/docs/twitter-api/v1/developer-utilities/configuration/api-reference/get-help-configuration
const TCO_LENGTH = 23;

const getContent = (content) => {
  let { length } = content;
  let cropAt = 280;

  if (length <= cropAt) {
    return content;
  }

  content.match(/https?:\/\/[^\s]+/g).forEach((link) => {
    length -= link.length;
    cropAt += link.length;

    length += Math.min(link.length, TCO_LENGTH);
    cropAt -= Math.min(link.length, TCO_LENGTH);
  });

  // limit to maximum tweet length
  if (length > 280) {
    content = content.substring(0, cropAt);
  }

  return content;
};

const edit = ({ attributes, className, setAttributes }) => {
  const blockClasses = classnames('tweetAction', this.props.className, {
    'tweetAction--narrow': size === 'narrow',
  });

  const buttonClasses = classnames(['btn', 'btn--fill', 'btn--large']);

  return (
    <>
      <InspectorControls>
        <PanelBody>
          <SelectControl
            // translators: [admin]
            label={__('Size', 'amnesty')}
            value={attributes.size}
            onChange={(size) => setAttributes({ size })}
            options={[
              // translators: [admin]
              { value: '', label: __('Default', 'amnesty') },
              // translators: [admin]
              { value: 'narrow', label: __('Narrow', 'amnesty') },
            ]}
          />
          <ToggleControl
            // translators: [admin]
            label={__('Embed Link', 'amnesty')}
            // translators: [admin]
            help={__('Includes a link to current post/page in tweet', 'amnesty')}
            checked={attributes.embedLink}
            onChange={(embedLink) => setAttributes({ embedLink })}
          />
        </PanelBody>
      </InspectorControls>
      <BlockControls>
        <BlockAlignmentToolbar
          value={attributes.alignment}
          onChange={(alignment) => setAttributes({ alignment })}
        />
      </BlockControls>
      <div className={`tweetBlock align-${attributes.alignment}`}>
        <div className={blockClasses}>
          <div className="tweetAction-header">
            <span className="dashicons dashicons-twitter" aria-label="Twitter Logo"></span>
            <PlainText
              className="tweetAction-title"
              // translators: [admin]
              placeholder={__('(Action Title)', 'amnesty')}
              value={attributes.title}
              onChange={(title) => setAttributes({ title })}
            />
          </div>
          <div className="tweetAction-textBox">
            <PlainText
              className="tweetAction-content"
              rows="8"
              // translators: [admin]
              placeholder={__('(Place Tweet text proforma here)', 'amnesty')}
              value={attributes.content}
              onChange={(content) => setAttributes({ content: getContent(content) })}
            />
            {attributes.embedLink && (
              <p className="embedLink-placeholder">
                {
                  /* translators: [admin] */ __(
                    'A link to the current page/post will now appear below the Tweet content when published/updated.',
                    'amnesty',
                  )
                }
              </p>
            )}
          </div>
          <div>
            <button
              className={buttonClasses}
              aria-label={/* translators: [admin] */ __('Send this Tweet', 'amnesty')}
            >
              {/* translators: [admin] */ __('Send this Tweet', 'amnesty')}
            </button>
          </div>
        </div>
      </div>
    </>
  );
};
