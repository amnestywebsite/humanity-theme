import { every, pick } from 'lodash';
import { Button } from '@wordpress/components';
import { dispatch } from '@wordpress/data';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

const { MediaPlaceholder, MediaUploadCheck, RichText, useBlockProps } = wp.blockEditor;

const MESSAGES = {
  /* translators: [admin] */
  uploading: __('Uploading…', 'amnesty'),
  /* translators: [admin] */
  uploadError: __('Uploading failed', 'amnesty'),
  /* translators: [admin] */
  uploadSuccess: __('Uploading complete', 'amnesty'),
};

export default function Edit({ attributes, setAttributes }) {
  const [preupload, setPreupload] = useState(0);
  const [postupload, setPostupload] = useState(0);
  const [locked, setLocked] = useState(false);
  const [type, setType] = useState(false);

  const onBeforeUpload = (value) => {
    if (!locked) {
      setLocked(true);
      dispatch('core/editor').lockPostSaving('fileUploadLock');
      dispatch('core/notices').createWarningNotice(MESSAGES.uploading, {
        id: MESSAGES.uploading,
      });
    }

    setPreupload(value.length);
  };

  const onUploadError = (message) => {
    let errorMessage;

    dispatch('core/notices').removeNotice(MESSAGES.uploading);

    if (Array.isArray(message)) {
      [, , errorMessage] = message;
    } else {
      errorMessage = message;
    }

    dispatch('core/notices').createErrorNotice(errorMessage, { id: MESSAGES.uploadError });
    dispatch('core/editor').unlockPostSaving('fileUploadLock');
  };

  const onFilesSelect = (media) => {
    dispatch('core/notices').removeNotice(MESSAGES.uploadError);

    setAttributes({ files: media.map((m) => pick(m, ['id', 'title'])) });

    setPostupload(media.length);
    setType(every(media, 'type'));
    // state.spinner: true?

    if (preupload === postupload && type === true) {
      if (locked) {
        setLocked(false);
        dispatch('core/editor').unlockPostSaving('fileUploadLock');
      }

      setPreupload(0);
      setType(false);

      dispatch('core/notices').removeNotice(MESSAGES.uploading);
      dispatch('core/notices').createSuccessNotice(MESSAGES.uploadSuccess, {
        id: MESSAGES.uploadSuccess,
      });

      setTimeout(() => dispatch('core/notices').removeNotice(MESSAGES.uploadSuccess), 3000);
    }
  };

  const blockProps = useBlockProps({
    className: attributes.files.length > 1 ? 'has-multiple' : '',
  });

  return (
    <>
      {!attributes.files.length && (
        <div {...blockProps} style={{ maxHeight: '135px', width: '100%' }}>
          <MediaUploadCheck>
            <MediaPlaceholder
              icon="media-default"
              multiple={true}
              isAppender={true}
              accept="*"
              labels={{
                /* translators: [admin] */
                title: __('Select File(s)', 'amnesty'),
                /* translators: [admin] */
                instructions: __('Upload media files or pick from your media library.', 'amnesty'),
              }}
              onFilesPreUpload={onBeforeUpload}
              onError={onUploadError}
              onSelect={onFilesSelect}
            />
          </MediaUploadCheck>
        </div>
      )}
      {attributes.files.length > 0 && (
        <div {...blockProps}>
          {attributes.files.length > 1 && (
            <select>
              <option key="label">
                {/* translators: [admin] */ __('Select a file to download', 'amnesty')}
              </option>
              {attributes.files.map((file) => (
                <option key={file.id}>{file.title}</option>
              ))}
            </select>
          )}
          <span className="btn btn--download">
            <RichText
              format="string"
              tagName="span"
              allowedFormats={[]}
              /* translators: [admin] */
              placeholder={__('Download', 'amnesty')}
              value={attributes.buttonText}
              onChange={(buttonText) => setAttributes({ buttonText })}
            />
          </span>
          <Button isDestructive variant="link" onClick={() => setAttributes({ files: [] })}>
            {/* translators: [admin] */ __('Clear File(s)', 'amnesty')}
          </Button>
        </div>
      )}
    </>
  );
}
