import { fetchMediaUrl } from '../utils';

import { MediaUpload } from '@wordpress/block-editor';
import { Button, Spinner } from '@wordpress/components';
import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

// translators: [admin]
const DEFAULT_SET_MEDIA_LABEL = __('Set Image', 'amnesty');
// translators: [admin]
const DEFAULT_REPLACE_MEDIA_LABEL = __('Replace Image', 'amnesty');
// translators: [admin]
const DEFAULT_REMOVE_MEDIA_LABEL = __('Remove Image', 'amnesty');

const edit = (props) => {
  const { onUpdate } = props;
  const [media, setMedia] = useState(false);
  const [loading, setLoading] = useState(false);
  const mounted = useRef();

  useEffect(() => {
    if (mounted?.current) {
      return;
    }

    mounted.current = true;

    if (mediaId) {
      fetchMediaUrl(mediaId, setMedia);
    }
  }, []);

  useEffect(() => {
    setLoading(true);
    fetchMediaUrl(props.mediaId, setMedia).then(() => setLoading(false));
  }, [props.mediaId]);

  const doUpdate = async (media) => {
    if (!media) {
      setMedia(false);
      onUpdate();
      return;
    }

    fetchMediaUrl(media.id, setMedia).then((r) => onUpdate(r));
  };

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
      {!!mediaId && (
        <MediaUpload
          title={setMediaLabel}
          onSelect={doUpdate}
          allowedTypes={[mediaType]}
          modalClass="editor-post-featured-image__media-modal"
          render={({ open }) => (
            <Button className="editor-post-featured-image__preview" onClick={open} />
          )}
        />
      )}
      {!!mediaId && media && !media.isLoading && (
        <MediaUpload
          title={setMediaLabel}
          onSelect={doUpdate}
          allowedTypes={[mediaType]}
          modalClass="editor-post-featured-image__media-modal"
          render={({ open }) => (
            <div>
              {!loading && mediaType === 'video' && (
                <video>
                  <source src={media.source_url || media.url} />
                </video>
              )}
              {!loading && mediaType === 'image' && <img src={media.source_url || media.url} />}

              {loading && <Spinner />}
              <Button onClick={open} isSecondary isLarge>
                {replaceMediaLabel}
              </Button>
            </div>
          )}
        />
      )}
      {!mediaId && (
        <div>
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
        </div>
      )}
      {!!mediaId && (
        <Button onClick={doRemove} isLink isDestructive>
          {removeMediaLabel}
        </Button>
      )}
    </div>
  );
};

export default edit;
