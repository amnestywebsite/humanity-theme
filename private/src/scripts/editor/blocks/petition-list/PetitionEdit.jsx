/* eslint-disable react/display-name */

const { InspectorControls } = wp.blockEditor;
const { PanelBody } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;

class PetitionEdit extends Component {
  render() {
    const { attributes } = this.props;
    const { style } = attributes;
    return (
      <Fragment>
        <InspectorControls>
          <PanelBody title={__('Petition Settings', 'amnesty')}>{style}</PanelBody>
        </InspectorControls>
      </Fragment>
    );
  }
}

export default PetitionEdit;
