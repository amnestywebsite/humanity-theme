/**
 * Render an item of metadata
 *
 * @param {Object} param0 props passed to the component
 *
 * @returns {WP.Element}
 */
const MetadataItem = ({ text, className }) => {
  if (!text) {
    return null;
  }

  return <span className={`image-metaItem ${className}`}>{text}</span>;
};

/**
 * Render media metadata
 *
 * @param {Object} props props passed to the component
 *
 * @returns {WP.Element}
 */
const MediaMetadata = ({ media, showMediaCaption, showMediaCopyright }) => {
  if (!showMediaCaption && !showMediaCopyright) {
    return null;
  }

  const { caption, copyright } = media;

  return (
    <div className="image-metadata">
      {showMediaCaption && (
        <MetadataItem text={caption} className="image-metadataItem image-caption" />
      )}
      {showMediaCopyright && (
        <MetadataItem text={copyright} className="image-metadataItem image-copyright" />
      )}
    </div>
  );
};

export default MediaMetadata;
