import classnames from 'classnames';

const { isUndefined } = lodash;
const { InnerBlocks, InspectorControls, RichText } = wp.blockEditor;
const { PanelBody, SelectControl } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { applyFilters } = wp.hooks;

const ALLOWED_BLOCKS = [
  'core/buttons',
  'amnesty-core/block-button',
  'amnesty-core/block-download',
  'amnesty-core/iframe-button',
];

// type is undef for old versions of block
const getTemplate = (type) => {
  if (isUndefined(type)) {
    return [['amnesty-core/block-button']];
  }

  if (type === 'download') {
    return [['amnesty-core/block-download']];
  }

  return [['core/buttons']];
};

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

  state = {
    allowedInnerBlocks: applyFilters('add-modal-to-allowed-blocks', ALLOWED_BLOCKS),
  };

  createUpdateAttribute = (key) => (value) => this.props.setAttributes({ [key]: value });

  componentDidMount = () => {
    const { attributes } = this.props;

    if (!attributes.actionType) {
      const actionTypeUpdate = this.createUpdateAttribute('actionType');
      actionTypeUpdate('');
    }
  };

  render() {
    const { attributes } = this.props;

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
            <SelectControl
              // translators: [admin]
              label={__('Background Style', 'amnesty')}
              options={[
                {
                  // translators: [admin]
                  label: __('Light', 'amnesty'),
                  value: '',
                },
                {
                  // translators: [admin]
                  label: __('Grey', 'amnesty'),
                  value: 'shade',
                },
              ]}
              value={attributes.background}
              onChange={this.createUpdateAttribute('background')}
            />
          </PanelBody>
        </InspectorControls>
        <div
          className={classnames('callToAction', {
            [`callToAction--${attributes.background}`]: attributes.background,
          })}
        >
          <RichText
            tagName="h2"
            className="callToAction-preHeading"
            // translators: [admin]
            placeholder={__('(Pre-heading)', 'amnesty')}
            allowedFormats={[]}
            value={attributes.preheading}
            onChange={this.createUpdateAttribute('preheading')}
          />
          <RichText
            tagName="h1"
            className="callToAction-heading"
            // translators: [admin]
            placeholder={__('(Heading)', 'amnesty')}
            allowedFormats={[]}
            value={attributes.title}
            onChange={this.createUpdateAttribute('title')}
          />
          <RichText
            tagName="p"
            className="callToAction-content"
            // translators: [admin]
            placeholder={__('(Content)', 'amnesty')}
            value={attributes.content}
            onChange={this.createUpdateAttribute('content')}
          />
          <div className="callToAction-buttonContainer">
            <InnerBlocks
              templateInsertUpdatesSelection={false}
              template={getTemplate(attributes.actionType)}
              templateLock={false}
              allowedBlocks={this.state.allowedInnerBlocks}
            />
          </div>
        </div>
      </Fragment>
    );
  }
}

export default DisplayComponent;
