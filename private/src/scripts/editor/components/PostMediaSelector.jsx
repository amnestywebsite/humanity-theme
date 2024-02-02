const { MediaUpload } = wp.blockEditor;
const { Button, Spinner } = wp.components;
const { Component } = wp.element;
const { __ } = wp.i18n;

// translators: [admin]
const DEFAULT_SET_MEDIA_LABEL = __('Set Image', 'amnesty');
// translators: [admin]
const DEFAULT_REPLACE_MEDIA_LABEL = __('Replace Image', 'amnesty');
// translators: [admin]
const DEFAULT_REMOVE_MEDIA_LABEL = __('Remove Image', 'amnesty');

class PostMediaSelector extends Component {
  constructor(...args) {
    super(...args);

    this.state = {
      media: false,
      loading: false,
    };
  }

  componentDidMount() {
    if (this.props.mediaId && !this.state.media) {
      this.fetchMediaObject();
    }
  }

  componentDidUpdate(prevProps) {
    if (this.props.mediaId !== prevProps.mediaId) {
      this.fetchMediaObject();
    }
  }

  static getMediaObjectPromise = (mediaId) =>
    wp.apiRequest({
      path: `/wp/v2/media/${mediaId}`,
    });

  fetchMediaObject() {
    const { mediaId } = this.props;

    if (!mediaId) {
      return;
    }

    this.setState({ loading: true });

    wp.apiRequest({
      path: `/wp/v2/media/${mediaId}`,
    }).then((resp) => {
      this.setState({
        media: { ...resp },
        loading: false,
      });
    });
  }

  onUpdate = async (media) => {
    if (!media) {
      this.setState({ media: false });
      this.props.onUpdate();
      return;
    }

    const response = await PostMediaSelector.getMediaObjectPromise(media.id);

    this.setState({ response });
    this.props.onUpdate({ ...response });
  };

  onRemove = () => this.onUpdate();

  render() {
    const {
      mediaId,
      labels: {
        set: setMediaLabel = DEFAULT_SET_MEDIA_LABEL,
        remove: removeMediaLabel = DEFAULT_REMOVE_MEDIA_LABEL,
        replace: replaceMediaLabel = DEFAULT_REPLACE_MEDIA_LABEL,
      } = {},
      type: mediaType = 'image',
    } = this.props;
    const { media, loading } = this.state;

    return (
      <div className="editor-post-featured-image">
        {!!mediaId && (
          <MediaUpload
            title={setMediaLabel}
            onSelect={this.onUpdate}
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
            onSelect={this.onUpdate}
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
              onSelect={this.onUpdate}
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
          <Button onClick={this.onRemove} isLink isDestructive>
            {removeMediaLabel}
          </Button>
        )}
      </div>
    );
  }
}

export default PostMediaSelector;
