const DisplayCreditData = ({ type, media, videoCaption, videoDescription, hideImageCaption, hideImageCopyright  }) => {
  return (
    <div className="image-metadata">
        {type === 'video' && (
          <>
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
          </>
        )}
        {type !== 'video' && (
          <>
          {!hideImageCaption && (
            <span className="image-metadataItem image-caption">
              {media?.caption.raw}
            </span>
          )}
          {!hideImageCopyright && (
            <span className="image-metadataItem image-copyright">
              {media?.description.raw}
            </span>
          )}
          </>
        )}
    </div>
  );
};

export default DisplayCreditData;
