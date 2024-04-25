class DisplayVideoData extends React.Component {
  render() {
    const { attributes, videoCaption, videoDescription } = this.props;
    const { hideImageCaption, hideImageCopyright } = attributes;

    return (
      <div className="image-metadata">
        {!hideImageCaption && (
          <span className="image-metadataItem image-caption">
            {videoCaption}
          </span>
        )}
        {!hideImageCopyright && (
          <span className="image-metadataItem image-copyright">
            {videoDescription}
          </span>
        )}
      </div>
    );
  }
}

export default DisplayVideoData;
