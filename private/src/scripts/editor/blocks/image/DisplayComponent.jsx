import classnames from 'classnames';

const { React } = window;
const { InspectorControls, MediaUpload, PlainText, RichText, URLInputButton } = wp.blockEditor;
const { Button, IconButton, PanelBody, SelectControl, ToggleControl } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;

const getClosestSize = (media) => {
  const sizeList = media.sizes || media.media_details.sizes;
  const sizes = {};

  Object.keys(sizeList).forEach((size) => {
    sizes[size] = sizeList[size].height;
  });

  let closest = 'full';

  Object.keys(sizes).forEach((size) => {
    if (Math.abs(600 - sizes[size]) < Math.abs(600 - sizes[closest])) {
      closest = size;
    }
  });

  return closest;
};

export default class DisplayComponent extends Component {
  state = {
    image: {},
    video: {},
  };

  componentDidMount() {
    const { attributes } = this.props;
    const { type, imageID = 0, videoID = 0 } = attributes;

    let mediaID = imageID;
    let key = 'image';

    if (type === 'video') {
      mediaID = videoID;
      key = 'video';
    }

    wp.apiRequest({ path: `/wp/v2/media/${mediaID}` }).then((response) =>
      this.setState({ [key]: response }),
    );
  }

  updateImage(media, style = 'loose') {
    let size = 'full';
    if (style === 'fixed') {
      size = getClosestSize(media);
    }

    const sizeList = media.sizes || media.media_details.sizes;
    const url = sizeList[size].url || sizeList[size].source_url;

    this.setState({ image: media });
    this.props.setAttributes({ imageID: media.id, imageURL: url });
  }

  removeImage = () => {
    this.setState({ image: {} });
    this.props.setAttributes({ imageID: 0, imageURL: '' });
  };

  updateVideo = (media) => {
    this.removeVideo();

    this.setState({ video: media });
    this.props.setAttributes({ videoID: media.id, videoURL: media.source_url || media.url });
  };

  removeVideo = () => {
    this.setState({ video: {} });
    this.props.setAttributes({ videoID: 0, videoURL: '' });
  };

  updateStyle = (style) => {
    this.props.setAttributes({ style });

    if (this.state.image) {
      this.updateImage(this.state.image, style);
    }
  };

  updateButtonAttribute(index, attribute, value) {
    const { attributes, setAttributes } = this.props;
    const { buttons } = attributes;

    return setAttributes({
      buttons: [
        // old buttons up to current index
        ...buttons.slice(0, Math.max(0, index)),
        // current button
        {
          ...buttons[index],
          [attribute]: value,
        },
        // old buttons after current index
        ...buttons.slice(index + 1, buttons.length),
      ],
    });
  }

  createButton(index = -1) {
    const { attributes } = this.props;
    const { buttons } = attributes;

    if (!buttons[index]) {
      buttons[index] = {
        text: '',
        url: '',
      };
    }

    return (
      <div style={{ position: 'relative' }} key={index}>
        <div className="imageBlock-buttonWrapper">
          <PlainText
            className="btn btn--white"
            rows="1"
            // translators: [admin]
            placeholder={__('(Link Text)', 'amnesty')}
            value={buttons[index].text}
            onChange={(text) => this.updateButtonAttribute(index, 'text', text)}
          />
        </div>
        <div className="linkList-options">
          {buttons.length > 1 && (
            <IconButton
              icon="no-alt"
              // translators: [admin]
              label={__('Remove Button', 'amnesty')}
              onClick={() => this.removeButton(index)}
            />
          )}
          <URLInputButton
            url={buttons[index].url}
            onChange={(url) => this.updateButtonAttribute(index, 'url', url)}
          />
        </div>
      </div>
    );
  }

  removeButton(index) {
    const { attributes, setAttributes } = this.props;
    const { buttons } = attributes;

    const newButtons = [
      ...buttons.slice(0, Math.max(0, index)),
      ...buttons.slice(index + 1, buttons.length),
    ];

    setAttributes({ buttons: newButtons });
  }

  imagePanelControls() {
    const { attributes, setAttributes } = this.props;
    const { type = '', parallax = false, align = 'default', style = 'loose' } = attributes;

    if (parallax || type === 'video') {
      return '';
    }

    return (
      <PanelBody>
        <SelectControl
          // translators: [admin]
          label={__('Image Style', 'amnesty')}
          value={style}
          onChange={(newStyle) => this.updateStyle(newStyle)}
          options={[
            // translators: [admin]
            { value: 'fixed', label: __('Fixed Height (600px)', 'amnesty') },
            // translators: [admin]
            { value: 'loose', label: __('Actual Height', 'amnesty') },
          ]}
        />
        <SelectControl
          // translators: [admin]
          label={__('Alignment', 'amnesty')}
          // translators: [admin]
          help={__('Only has an effect on images smaller than their container', 'amnesty')}
          value={align}
          onChange={(newAlign) => setAttributes({ align: newAlign })}
          options={[
            /* translators: [admin] text alignment/. */
            { value: 'default', label: __('Default', 'amnesty') },
            /* translators: [admin] text alignment. for RTL languages, localise as 'Right' */
            { value: 'left', label: __('Left', 'amnesty') },
            /* translators: [admin] text alignment. */
            { value: 'centre', label: __('Centre', 'amnesty') },
            /* translators: [admin] text alignment. for RTL languages, localise as 'Left' */
            { value: 'right', label: __('Right', 'amnesty') },
          ]}
        />
      </PanelBody>
    );
  }

  imageInlineControls() {
    const { type = '', imageID = 0 } = this.props.attributes;

    if (type === 'video') {
      return '';
    }

    return (
      <div className="linkList-options imageBlock-action">
        {imageID ? (
          <IconButton
            icon="no-alt"
            // translators: [admin]
            label={__('Remove Image', 'amnesty')}
            onClick={this.removeImage}
          />
        ) : (
          <MediaUpload
            allowedTypes={['image']}
            value={imageID}
            onSelect={(media) => {
              /**
               * MediaUpload component doesn't return full sizes array, so we need to
               * grab it from the API again. Inefficient, but it's a Gutenberg core issue
               * that was supposedly resolved in https://github.com/WordPress/gutenberg/pull/7605,
               * but either wasn't, or was subject to a regression failure.
               */
              wp.apiRequest({ path: `/wp/v2/media/${media.id}` }).then((response) => {
                this.updateImage({
                  id: response.id,
                  sizes: response.media_details.sizes,
                });
              });
            }}
            render={({ open }) => <IconButton icon="format-image" onClick={open} />}
          />
        )}
      </div>
    );
  }

  overlayInputFields() {
    const { attributes, setAttributes } = this.props;
    const { hasOverlay = false, title = '', content = '', buttons = [] } = attributes;

    if (!hasOverlay) {
      return '';
    }

    return (
      <div className="imageBlock-overlay">
        <RichText
          className="imageBlock-title"
          rows="1"
          // translators: [admin]
          placeholder={__('(Title)', 'amnesty')}
          value={title}
          onChange={(newTitle) => setAttributes({ title: newTitle })}
        />
        <RichText
          className="imageBlock-content"
          // translators: [admin]
          placeholder={__('(Content)', 'amnesty')}
          value={content}
          format="string"
          keepPlaceholderOnFocus={true}
          onChange={(newContent) => setAttributes({ content: newContent })}
        />
        <div className="imageBlock-buttonsContainer">
          {buttons.map((button, index) => this.createButton(index, button))}
          {buttons.length < 1 && this.createButton(0)}
          <IconButton
            icon="plus"
            // translators: [admin]
            label={__('Add Button', 'amnesty')}
            onClick={() => setAttributes({ buttons: [...buttons, { text: '', url: '' }] })}
          />
        </div>
      </div>
    );
  }

  videoPanelControls() {
    const { attributes } = this.props;
    const { videoURL = '', type = '' } = attributes;

    if (type !== 'video') {
      return '';
    }

    if (!videoURL) {
      return (
        <PanelBody>
          <MediaUpload
            title={__('Choose Video', 'amnesty')}
            onSelect={(media) => this.updateVideo(media)}
            allowedTypes={['video']}
            modalClass="editor-post-featured-image__media-modal"
            render={({ open }) => (
              <Button className="editor-post-featured-image__toggle" onClick={open}>
                {/* translators: [admin] */ __('Choose Video', 'amnesty')}
              </Button>
            )}
          />
        </PanelBody>
      );
    }

    return (
      <PanelBody>
        <MediaUpload
          // translators: [admin]
          title={__('Replace Video', 'amnesty')}
          onSelect={(media) => this.updateVideo(media)}
          allowedTypes={['video']}
          modalClass="editor-post-featured-image__media-modal"
          render={({ open }) => (
            <div>
              <video>
                <source src={videoURL} />
              </video>
              <Button onClick={open} isSecondary isLarge>
                {/* translators: [admin] */ __('Replace Video', 'amnesty')}
              </Button>
            </div>
          )}
        />
        <Button onClick={this.removeVideo} isLink isDestructive>
          {/* translators: [admin] */ __('Remove Video', 'amnesty')}
        </Button>
      </PanelBody>
    );
  }

  backgroundMediaFields() {
    const { type = '', imageURL = '', videoURL = '' } = this.props.attributes;

    if (!type && imageURL) {
      return <img className="imageBlock-image" src={imageURL} />;
    }

    if (videoURL && (this.state.video.source_url || this.state.video.url)) {
      return (
        <video className="imageBlock-video">
          <source src={videoURL} />
        </video>
      );
    }

    return '';
  }

  render() {
    const { attributes, setAttributes } = this.props;
    const {
      type = '',
      style = 'loose',
      parallax = false,
      align = 'default',
      hasOverlay = false,
      caption,
    } = attributes;

    const classes = classnames('imageBlock', {
      'imageBlock--fixed': style === 'fixed',
      'has-video': type === 'video',
      'has-parallax': parallax,
      [`align${align}`]: align !== 'default',
    });

    const capClasses = classnames('imageBlock-caption', {
      [`align${align}`]: align !== 'default',
    });

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody>
            <SelectControl
              // translators: [admin]
              label={__('Background Type', 'amnesty')}
              options={[
                {
                  // translators: [admin]
                  label: __('Image', 'amnesty'),
                  value: '',
                },
                {
                  // translators: [admin]
                  label: __('Video', 'amnesty'),
                  value: 'video',
                },
              ]}
              value={type}
              onChange={(newType) => setAttributes({ type: newType })}
            />
          </PanelBody>

          {this.imagePanelControls()}

          <PanelBody>
            <ToggleControl
              // translators: [admin]
              label={__('Display Overlay', 'amnesty')}
              checked={hasOverlay}
              onChange={(newHasOverlay) => setAttributes({ hasOverlay: newHasOverlay })}
            />
            <ToggleControl
              // translators: [admin]
              label={__('Enable Parallax', 'amnesty')}
              checked={parallax}
              onChange={(newParallax) =>
                setAttributes({
                  parallax: newParallax,
                  style: newParallax ? 'loose' : style,
                })
              }
            />
          </PanelBody>

          {this.videoPanelControls()}
        </InspectorControls>

        <div className={classes}>
          {this.backgroundMediaFields()}
          {this.overlayInputFields()}
        </div>

        <RichText
          className={capClasses}
          rows="1"
          // translators: [admin]
          placeholder={__('(Insert Caption, if required)', 'amnesty')}
          value={caption}
          onChange={(newCaption) => setAttributes({ caption: newCaption })}
        />

        {this.imageInlineControls()}
        <div className="clear"></div>
      </Fragment>
    );
  }
}
