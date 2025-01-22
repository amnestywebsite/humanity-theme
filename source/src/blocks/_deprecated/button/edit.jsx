import classnames from 'classnames';

import { InspectorControls, RichText, URLInputButton } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * This is the component that renders the edit screen in the panel.
 */
class DisplayComponent extends Component {
  /**
   * Higher order component that takes the attribute key,
   * this then returns a function which takes a value,
   * when called it updates the attribute with the key.
   * @param key
   * @returns {function(*): *}
   */
  createUpdateAttribute = (key) => (value) => this.props.setAttributes({ [key]: value });

  render() {
    const { attributes } = this.props;
    const { ctaLink, ctaText, style } = attributes;

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
            <SelectControl
              /* translators: [admin] */
              label={__('Button Style', 'amnesty')}
              options={[
                {
                  /* translators: [admin] */
                  label: __('Primary (Yellow)', 'amnesty'),
                  value: '',
                },
                {
                  /* translators: [admin] */
                  label: __('Dark', 'amnesty'),
                  value: 'dark',
                },
                {
                  /* translators: [admin] */
                  label: __('Light', 'amnesty'),
                  value: 'white',
                },
              ]}
              value={style}
              onChange={this.createUpdateAttribute('style')}
            />
          </PanelBody>
        </InspectorControls>

        <div className={classnames('btn', { [`btn--${attributes.style}`]: style })}>
          <RichText
            tagName="span"
            /* translators: [admin] */
            placeholder={__('(Button Text)', 'amnesty')}
            allowedFormats={[]}
            value={ctaText}
            onChange={this.createUpdateAttribute('ctaText')}
          />
        </div>
        <URLInputButton url={ctaLink} onChange={this.createUpdateAttribute('ctaLink')} />
      </Fragment>
    );
  }
}

export default DisplayComponent;
