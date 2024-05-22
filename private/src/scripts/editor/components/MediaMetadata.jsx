const MediaMetadata = ({ media, showMediaCaption, showMediaCopyright }) => {
  if (!showMediaCaption && !showMediaCopyright) {
    return null;
  }

  return (
    <div className="image-metadata">
      {showMediaCaption && (
        <span className="image-metadataItem image-caption">{media.caption}</span>
      )}
      {showMediaCopyright && (
        <span className="image-metadataItem image-copyright">{media.copyright}</span>
      )}
    </div>
  );
};

export default MediaMetadata;
