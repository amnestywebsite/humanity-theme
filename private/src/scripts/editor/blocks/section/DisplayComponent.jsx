import classNames from 'classnames';

const { apiFetch } = wp;
const { InspectorControls, InnerBlocks, MediaUpload, MediaUploadCheck } = wp.blockEditor;
const { Button, PanelBody, RangeControl, SelectControl, TextControl, ToggleControl } =
  wp.components;
const { compose } = wp.compose;
const { withDispatch } = wp.data;
const { Component, Fragment } = wp.element;
const { __, sprintf } = wp.i18n;

const findImage = async (basename, year, month) => {
  let found = false;

  const results = await apiFetch({
    path: sprintf('wp/v2/media?search=%s', encodeURIComponent(basename)),
  });

  if (results.length === 0) {
    return found;
  }

  if (results.length === 0) {
    return results[0];
  }

  found = results.filter((result) => {
    const guid = result.guid.rendered;
    const fileYear = guid.replace(/^.*\/(\d{4})\/\d{2}\/.*\.[a-z]{3,4}$/, '$1');
    const fileMonth = guid.replace(/^.*\/\d{4}\/(\d{2})\/.*\.[a-z]{3,4}$/, '$1');

    if (fileYear !== year) {
      return false;
    }

    if (fileMonth !== month) {
      return false;
    }

    return true;
  });

  return found[0];
};

class DisplayComponent extends Component {
  state = {
    imageData: null,
  };

  createUpdateAttribute = (key) => (value) => this.props.setAttributes({ [key]: value });

  fetchImageData = (imageId) => {
    wp.apiRequest({
      path: `/wp/v2/media/${imageId}?_fields=description,caption&context=edit`,
    }).then((resp) => {
      this.setState({
        imageData: {
          caption: resp.caption.raw,
          description: resp.description.raw,
        },
      });
    });
  };

  async componentDidMount() {
    const { attributes, setAttributes } = this.props;
    const { backgroundImageId = 0, backgroundImage } = attributes;

    // have the id already - nothing to do
    if (backgroundImageId) {
      this.fetchImageData(backgroundImageId);
      return;
    }

    // no background image - nothing to do
    if (!backgroundImage) {
      return;
    }

    const parts = backgroundImage.split(/[\\/]/);
    const basename = parts.pop().replace(/\.[a-z]{3,4}/, '');
    const month = parts.pop();
    const year = parts.pop();

    const image = await findImage(basename, year, month);

    if (!image) {
      return;
    }

    setAttributes({ backgroundImageId: image.id });
    this.fetchImageData(backgroundImageId);
  }

  componentDidUpdate(prevProps) {
    const { backgroundImageId } = this.props.attributes;

    if (backgroundImageId === prevProps.attributes.backgroundImageId) {
      return;
    }

    this.fetchImageData(backgroundImageId);
  }

  handleSave = (val) => {
    const newVal = val.replace(/\s/g, '').toLowerCase();

    this.props.setAttributes({
      sectionId: newVal,
      sectionName: val,
    });
  };

  selectImage = (value) => {
    const { setAttributes } = this.props;
    setAttributes({
      backgroundImageId: value.id,
      backgroundImage: value.sizes.full.url,
      backgroundImageHeight: value.sizes.full.height,
    });
  };

  removeImage = () => {
    const { setAttributes } = this.props;
    setAttributes({
      backgroundImageId: 0,
      backgroundImage: '',
      backgroundImageHeight: 0,
      minHeight: 0,
    });
  };

  controls() {
    const { attributes, setAttributes } = this.props;

    return (
      <InspectorControls>
        <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
          <TextControl
            // translators: [admin]
            label={__('Section Name', 'amnesty')}
            value={attributes.sectionName}
            onChange={this.handleSave}
          />
          <SelectControl
            // translators: [admin]
            label={__('Text Colour', 'amnesty')}
            options={[
              {
                // translators: [admin]
                label: __('Black', 'amnesty'),
                value: 'black',
              },
              {
                // translators: [admin]
                label: __('White', 'amnesty'),
                value: 'white',
              },
            ]}
            value={attributes.textColour}
            onChange={(value) => setAttributes({ textColour: value })}
          />
        </PanelBody>
        <PanelBody title={/* translators: [admin] */ __('Background Options', 'amnesty')}>
          <div className="components-base-control">
            <MediaUploadCheck>
              <MediaUpload
                onSelect={this.selectImage}
                allowedTypes={['image']}
                value={attributes.backgroundImage}
                render={({ open }) => (
                  <Fragment>
                    {attributes.backgroundImage !== '' ? (
                      <Fragment>
                        <Button isPrimary onClick={open}>
                          {/* translators: [admin] */ __('Edit image', 'amnesty')}
                        </Button>
                        <Button
                          isDestructive
                          isDefault
                          onClick={this.removeImage}
                          style={{
                            marginLeft: '6px',
                          }}
                        >
                          {/* translators: [admin] */ __('Remove image', 'amnesty')}
                        </Button>
                      </Fragment>
                    ) : (
                      <Button isPrimary onClick={open}>
                        {/* translators: [admin] */ __('Select background image', 'amnesty')}
                      </Button>
                    )}
                  </Fragment>
                )}
              />
            </MediaUploadCheck>
          </div>
          <RangeControl
            // translators: [admin]
            label={__('Min image height as viewport percentage', 'amnesty')}
            onChange={(value) => setAttributes({ minHeight: !value ? 0 : value })}
            value={attributes.minHeight}
            intialPostition={attributes.minHeight}
            min={0}
            max={100}
            allowReset
          />
          <ToggleControl
            // translators: [admin]
            label={__('Toggle Background Overlay', 'amnesty')}
            checked={attributes.enableBackgroundGradient}
            onChange={(enableBackgroundGradient) => setAttributes({ enableBackgroundGradient })}
          />
          <ToggleControl
            // translators: [admin]
            label={__('Hide Image Caption', 'amnesty')}
            checked={attributes.hideImageCaption}
            onChange={() => setAttributes({ hideImageCaption: !attributes.hideImageCaption })}
          />
          <ToggleControl
            // translators: [admin]
            label={__('Hide Image Credit', 'amnesty')}
            checked={attributes.hideImageCopyright}
            onChange={() => setAttributes({ hideImageCopyright: !attributes.hideImageCopyright })}
          />
          <SelectControl
            // translators: [admin]
            label={__('Background Image Origin', 'amnesty')}
            options={[
              {
                // translators: [admin]
                label: __('Top', 'amnesty'),
                value: 'top',
              },
              {
                // translators: [admin]
                label: __('Right', 'amnesty'),
                value: 'right',
              },
              {
                // translators: [admin]
                label: __('Bottom', 'amnesty'),
                value: 'bottom',
              },
              {
                // translators: [admin]
                label: __('Left', 'amnesty'),
                value: 'left',
              },
              {
                // translators: [admin]
                label: __('Centre', 'amnesty'),
                value: 'center',
              },
              {
                // translators: [admin]
                label: __('Initial', 'amnesty'),
                value: '',
              },
            ]}
            value={attributes.backgroundImageOrigin}
            onChange={(value) => setAttributes({ backgroundImageOrigin: value })}
          />
          <SelectControl
            // translators: [admin]
            label={__('Background Colour', 'amnesty')}
            options={[
              {
                // translators: [admin]
                label: __('White', 'amnesty'),
                value: '',
              },
              {
                // translators: [admin]
                label: __('Grey', 'amnesty'),
                value: 'grey',
              },
            ]}
            value={attributes.background}
            onChange={this.createUpdateAttribute('background')}
          />
        </PanelBody>
      </InspectorControls>
    );
  }

  render() {
    const { attributes } = this.props;

    const styles = (h) => {
      if (!attributes.backgroundImage) {
        return {};
      }

      if (h > 0) {
        return {
          'background-image': `url(${attributes.backgroundImage})`,
          minHeight: `${attributes.minHeight}vw`,
          maxHeight: `${attributes.backgroundImageHeight}px`,
        };
      }
      return {
        'background-image': `url(${attributes.backgroundImage})`,
        height: 'auto',
      };
    };

    const shouldShowImageCaption =
      this.state.imageData?.caption &&
      !attributes.hideImageCaption &&
      this.state.imageData?.caption !== this.state.imageData?.description;

    const shouldShowImageCredit =
      this.state.imageData?.description && !attributes.hideImageCopyright;

    return (
      <Fragment>
        {this.controls()}
        <section
          className={classNames({
            section: true,
            'section--tinted': attributes.background === 'grey',
            [`section--${attributes.padding}`]: !!attributes.padding,
            'section--textWhite': attributes.textColour === 'white',
            'section--has-bg-image': attributes.backgroundImage,
            'section--has-bg-overlay': !!attributes.enableBackgroundGradient,
            [`section--bgOrigin-${attributes.backgroundImageOrigin}`]:
              !!attributes.backgroundImageOrigin,
          })}
          style={styles(attributes.minHeight)}
        >
          <div id={attributes.sectionId} className="container">
            <InnerBlocks templateLock={false} />
          </div>
          {this.state.imageData && (
            <div className="image-metadata">
              {shouldShowImageCaption && (
                <span className="image-metadataItem image-caption">
                  {this.state.imageData.caption}
                </span>
              )}
              {shouldShowImageCredit && (
                <span className="image-metadataItem image-copyright">
                  {this.state.imageData.description}
                </span>
              )}
            </div>
          )}
        </section>
      </Fragment>
    );
  }
}

export default compose(
  withDispatch((dispatch) => dispatch('core/block-editor').setTemplateValidity(true)),
)(DisplayComponent);
