/* eslint-disable react/display-name */

const { Fragment } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { PanelBody } = wp.components;
const { Component } = wp.element;

class PostListEdit extends Component {
  render() {
    const { attributes } = this.props;
    const { style } = attributes;
    return (
      <Fragment>
        <InspectorControls>
          <PanelBody title="Post List">{style}</PanelBody>
        </InspectorControls>
      </Fragment>
    );
  }
}

export default PostListEdit;
