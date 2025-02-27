import { MediaUpload } from '@wordpress/block-editor';
import { Button, Spinner } from '@wordpress/components';
import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import { fetchMediaUrl } from '../utils';

/* translators: [admin] */
const DEFAULT_SET_MEDIA_LABEL = __('Set Image', 'amnesty');
/* translators: [admin] */
const DEFAULT_REPLACE_MEDIA_LABEL = __('Replace Image', 'amnesty');
/* translators: [admin] */
const DEFAULT_REMOVE_MEDIA_LABEL = __('Remove Image', 'amnesty');

export default function PostMediaSelector(props) {
  const { onUpdate } = props;
  const [media, setMedia] = useState(null);
  const [loading, setLoading] = useState(false);
  const mounted = useRef(false);

  // UseEffect to fetch media on mount or when mediaId changes
  useEffect(() => {
    if (!mounted.current) {
      mounted.current = true;
      if (props.mediaId) {
        setLoading(true);
        fetchMediaUrl(props.mediaId, setMedia).then(() => setLoading(false));
      }
    }
  }, [props.mediaId]);

  // Update media
  const doUpdate = (newMedia) => {
    if (!newMedia) {
      setMedia(null);
      onUpdate();
      return;
    }

    setLoading(true);
    fetchMediaUrl(newMedia.id, setMedia).then(() => {
      setLoading(false);
      onUpdate(newMedia.id);
    });
  };

  // Remove media
  const doRemove = () => doUpdate(false);

  const {
    mediaId,
    labels: {
      set: setMediaLabel = DEFAULT_SET_MEDIA_LABEL,
      remove: removeMediaLabel = DEFAULT_REMOVE_MEDIA_LABEL,
      replace: replaceMediaLabel = DEFAULT_REPLACE_MEDIA_LABEL,
    } = {},
    type: mediaType = 'image',
  } = props;

  return (
    <div className="editor-post-featured-image">
      <div>
        {!mediaId && (
          <MediaUpload
            title={setMediaLabel}
            onSelect={doUpdate}
            allowedTypes={[mediaType]}
            modalClass="editor-post-featured-image__media-modal"
            render={({ open }) => (
              <Button className="editor-post-featured-image__toggle" onClick={open}>
                {setMediaLabel}
              </Button>
            )}
          />
        )}

        {!!mediaId && (
          <MediaUpload
            title={mediaId ? replaceMediaLabel : setMediaLabel}
            onSelect={doUpdate}
            allowedTypes={[mediaType]}
            modalClass="editor-post-featured-image__media-modal"
            render={({ open }) => (
              <div>
                {!loading && media && (
                  <>
                    {mediaType === 'video' && (
                      <video>
                        <source src={media.source_url || media.url} />
                      </video>
                    )}
                    {mediaType === 'image' && <img src={media.source_url || media.url} />}
                    <Button onClick={open}>{replaceMediaLabel}</Button>
                  </>
                )}
                {loading && <Spinner />}
              </div>
            )}
          />
        )}

        {!!mediaId && <Button onClick={doRemove}>{removeMediaLabel}</Button>}
      </div>
    </div>
  );
}
