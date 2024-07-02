import classnames from 'classnames';

const { every, pick } = lodash;
const {
  BlockAlignmentToolbar,
  BlockControls,
  InspectorControls,
  MediaPlaceholder,
  MediaUploadCheck,
  RichText,
} = wp.blockEditor;
const { Button, PanelBody, SelectControl } = wp.components;
const { dispatch } = wp.data;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;

const MESSAGES = {
  // translators: [admin]
  uploading: __('Uploading…', 'amnesty'),
  // translators: [admin]
  uploadError: __('Uploading failed', 'amnesty'),
  // translators: [admin]
  uploadSuccess: __('Uploading complete', 'amnesty'),
};

class DisplayComponent extends Component {
  state = {
    preupload: 0,
    postupload: 0,
    locked: false,
    type: false,
  };

  onBeforeUpload = (value) => {
    if (!this.state.locked) {
      this.setState({ locked: true });

      dispatch('core/editor').lockPostSaving('fileUploadLock');

      dispatch('core/notices').createWarningNotice(MESSAGES.uploading, {
        id: MESSAGES.uploading,
      });
    }

    this.setState({ preupload: value.length });
  };

  static onUploadError = (message) => {
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

  onFilesSelect = (media) => {
    const { setAttributes } = this.props;
    const postupload = media.length;

    dispatch('core/notices').removeNotice(MESSAGES.uploadError);

    setAttributes({ files: media.map((m) => pick(m, ['id', 'title'])) });

    this.setState({
      postupload,
      spinner: true,
      type: every(media, 'type'),
    });

    if (this.state.preupload === postupload && this.state.type === true) {
      if (this.state.locked) {
        this.setState({ locked: false });
        dispatch('core/editor').unlockPostSaving('fileUploadLock');
      }

      this.setState({ preupload: 0, type: false });

      dispatch('core/notices').removeNotice(MESSAGES.uploading);
      dispatch('core/notices').createSuccessNotice(MESSAGES.uploadSuccess, {
        id: MESSAGES.uploadSuccess,
      });

      setTimeout(() => dispatch('core/notices').removeNotice(MESSAGES.uploadSuccess), 3000);
    }
  };

  uploader() {
    return (
      <div style={{ maxHeight: '135px', width: '100%' }}>
        <MediaUploadCheck>
          <MediaPlaceholder
            icon="media-default"
            multiple={true}
            isAppender={true}
            accept="*"
            labels={{
              // translators: [admin]
              title: __('Select File(s)', 'amnesty'),
              // translators: [admin]
              instructions: __('Upload media files or pick from your media library.', 'amnesty'),
            }}
            onFilesPreUpload={this.onBeforeUpload}
            onError={DisplayComponent.onUploadError}
            onSelect={this.onFilesSelect}
          />
        </MediaUploadCheck>
      </div>
    );
  }

  controls() {
    const { attributes, setAttributes } = this.props;
    const { style } = attributes;

    return (
      <InspectorControls>
        <PanelBody>
          <SelectControl
            // translators: [admin]
            label={__('Button Style', 'amnesty')}
            options={[
              {
                // translators: [admin]
                label: __('Dark', 'amnesty'),
                value: 'dark',
              },
              {
                // translators: [admin]
                label: __('Light', 'amnesty'),
                value: 'white',
              },
            ]}
            value={style}
            onChange={(newStyle) => setAttributes({ style: newStyle })}
          />
        </PanelBody>
      </InspectorControls>
    );
  }

  render() {
    const { attributes, setAttributes } = this.props;
    const { alignment, buttonText, files, style } = attributes;

    if (!files.length) {
      return (
        <Fragment>
          {this.controls()}
          <div className="download-block">{this.uploader()}</div>
        </Fragment>
      );
    }

    const blockClasses = classnames('download-block', {
      [alignment]: alignment !== 'none',
      'has-multiple': files.length > 1,
    });

    const btnClasses = classnames('btn', 'btn--download', {
      [`btn--${style}`]: !!style,
    });

    return (
      <Fragment>
        {this.controls()}

        <BlockControls>
          <BlockAlignmentToolbar
            value={attributes.alignment}
            onChange={(newAlign) => setAttributes({ alignment: newAlign })}
          />
        </BlockControls>

        <div className={blockClasses}>
          {files.length > 1 && (
            <select>
              <option key="label">
                {/* translators: [admin] */ __('Select a file to download', 'amnesty')}
              </option>
              {files.map((file) => (
                <option key={file.id}>{file.title}</option>
              ))}
            </select>
          )}
          <span className={btnClasses}>
            <RichText
              format="string"
              tagName="span"
              allowedFormats={[]}
              // translators: [admin]
              placeholder={__('Download', 'amnesty')}
              value={buttonText}
              onChange={(newText) => setAttributes({ buttonText: newText })}
            />
          </span>
          <Button isDestructive isLink onClick={() => setAttributes({ files: [] })}>
            {/* translators: [admin] */ __('Clear File(s)', 'amnesty')}
          </Button>
        </div>
      </Fragment>
    );
  }
}

export default DisplayComponent;
