import classNames from 'classnames';
import layouts from './layouts';

const { times } = lodash;
const { InspectorControls, InnerBlocks } = wp.blockEditor;
const { PanelBody, SelectControl } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;

class DisplayComponent extends Component {
  createUpdateAttribute = (key) => (value) => this.props.setAttributes({ [key]: value });

  render() {
    const { attributes } = this.props;
    const options = Object.keys(layouts).map((key) => ({
      value: key,
      label: layouts[key].name,
    }));

    const layoutKey = attributes.layout || '1/2|1/2';

    const currentTemplate = times(layouts[layoutKey].columns, () => [
      'amnesty-core/block-row-column',
    ]);

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
            <SelectControl
              // translators: [admin]
              label={__('Layout Style', 'amnesty')}
              options={options}
              value={attributes.layout}
              onChange={this.createUpdateAttribute('layout')}
            />
          </PanelBody>
        </InspectorControls>
        <div
          className={classNames({
            row: true,
            [`layout-${layoutKey}`]: true,
          })}
        >
          <InnerBlocks
            template={currentTemplate}
            templateLock="all"
            allowedBlocks={['amnesty-core/block-row-column']}
          />
        </div>
      </Fragment>
    );
  }
}

export default DisplayComponent;
