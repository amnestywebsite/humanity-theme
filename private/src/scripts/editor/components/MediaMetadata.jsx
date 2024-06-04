const MediaMetadata = ({ caption, copyright, showCaption, showCopyright }) => {
  if (!showCaption && !showCopyright) {
    return null;
  }

  return (
    <div className="image-metadata">
      {showCaption && <span className="image-metadataItem image-caption">{caption}</span>}
      {showCopyright && <span className="image-metadataItem image-copyright">{copyright}</span>}
    </div>
  );
};

export default MediaMetadata;
