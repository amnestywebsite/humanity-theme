/* eslint-disable react/display-name */

const { InspectorControls } = wp.blockEditor;
const { PanelBody } = wp.components;
const { Component, Fragment } = wp.element;
const { addFilter } = wp.hooks;
const { __ } = wp.i18n;

class PetitionEdit extends Component {
  render() {
    addFilter('amnesty-post-list-quantity', 'amnesty/petition-list', (quantity, { style }) => {
      if (style === 'petition') {
        return 100;
      }

      return quantity;
    });

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
