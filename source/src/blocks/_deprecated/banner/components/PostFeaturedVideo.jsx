import { MediaUpload } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/* translators: [admin] */
const DEFAULT_SET_FEATURE_VIDEO_LABEL = __('Set featured video', 'amnesty');
/* translators: [admin] */
const DEFAULT_REMOVE_FEATURE_VIDEO_LABEL = __('Remove featured video', 'amnesty');

class PostFeaturedVideo extends Component {
  constructor(...args) {
    super(...args);

    this.state = {
      media: false,
    };
  }

  componentDidMount() {
    if (this.props.featuredVideoId && !this.state.media) {
      this.fetchMediaObject();
    }
  }

  fetchMediaObject() {
    const { featuredVideoId } = this.props;
    wp.apiRequest({
      path: `/wp/v2/media/${featuredVideoId}`,
    }).then((resp) => {
      this.setState({
        media: { ...resp },
      });
    });
  }

  onUpdateVideo = (media) => {
    if (!media) {
      this.setState({ media: false });

      this.props.onUpdate('');
      return;
    }

    this.setState({ media });
    this.props.onUpdate(media.id);
  };

  onRemoveVideo = () => this.onUpdateVideo(false);

  render() {
    const { featuredVideoId } = this.props;
    const { media } = this.state;

    return (
      <div className="editor-post-featured-image">
        {!!featuredVideoId && (
          <MediaUpload
            title={DEFAULT_SET_FEATURE_VIDEO_LABEL}
            onSelect={this.onUpdateVideo}
            allowedTypes={['video']}
            modalClass="editor-post-featured-image__media-modal"
            render={({ open }) => (
              <Button className="editor-post-featured-image__preview" onClick={open} />
            )}
          />
        )}
        {!!featuredVideoId && media && !media.isLoading && (
          <MediaUpload
            title={DEFAULT_SET_FEATURE_VIDEO_LABEL}
            onSelect={this.onUpdateVideo}
            allowedTypes={['video']}
            modalClass="editor-post-featured-image__media-modal"
            render={({ open }) => (
              <div>
                <video>
                  <source src={media.source_url || media.url} />
                </video>
                <Button onClick={open} isSecondary isLarge>
                  {/* translators: [admin] */ __('Replace Video', 'amnesty')}
                </Button>
              </div>
            )}
          />
        )}
        {!featuredVideoId && (
          <div>
            <MediaUpload
              title={DEFAULT_SET_FEATURE_VIDEO_LABEL}
              onSelect={this.onUpdateVideo}
              allowedTypes={['video']}
              modalClass="editor-post-featured-image__media-modal"
              render={({ open }) => (
                <Button className="editor-post-featured-image__toggle" onClick={open}>
                  {DEFAULT_SET_FEATURE_VIDEO_LABEL}
                </Button>
              )}
            />
          </div>
        )}
        {!!featuredVideoId && (
          <Button onClick={this.onRemoveVideo} isLink isDestructive>
            {DEFAULT_REMOVE_FEATURE_VIDEO_LABEL}
          </Button>
        )}
      </div>
    );
  }
}

export default PostFeaturedVideo;
