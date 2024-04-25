class DisplayImageData extends React.Component {
  render() {
    const { imageData, attributes } = this.props;
    const { hideImageCaption, hideImageCopyright } = attributes;

    if (!imageData) {
      return null;
    }

    return (
      <div className="image-metadata">
        {!hideImageCaption && (
          <span className="image-metadataItem image-caption">
            {imageData.caption}
          </span>
        )}
        {!hideImageCopyright && (
          <span className="image-metadataItem image-copyright">
            {imageData.description}
          </span>
        )}
      </div>
    );
  }
}

export default DisplayImageData;
