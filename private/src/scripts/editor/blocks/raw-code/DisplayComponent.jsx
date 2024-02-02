const { PlainText, BlockControls, transformStyles } = wp.blockEditor;
const { Component } = wp.element;
const { __ } = wp.i18n;
const { withSelect } = wp.data;
const { ToolbarButton, Disabled, SandBox, ToolbarGroup } = wp.components;

class DisplayComponent extends Component {
  state = {
    isPreview: false,
    styles: [],
  };

  componentDidMount() {
    const { styles } = this.props;

    // Default styles used to unset some of the styles
    // that might be inherited from the editor style.
    const defaultStyles = `
			html,body,:root {
				margin: 0 !important;
				padding: 0 !important;
				overflow: visible !important;
				min-height: auto !important;
			}
		`;

    this.setState({
      styles: [defaultStyles, ...transformStyles(styles)],
    });
  }

  switchToPreview = () => {
    this.setState({ isPreview: true });
  };

  switchToHTML = () => {
    this.setState({ isPreview: false });
  };

  render() {
    const { attributes, setAttributes } = this.props;
    const { isPreview, styles } = this.state;

    return (
      <div className="wp-block-html">
        <BlockControls>
          <ToolbarGroup>
            <ToolbarButton
              className="components-tab-button"
              isPressed={!isPreview}
              onClick={this.switchToHTML}
            >
              <span>HTML</span>
            </ToolbarButton>
            <ToolbarButton
              className="components-tab-button"
              isPressed={isPreview}
              onClick={this.switchToPreview}
            >
              <span>{/* translators: [admin] */ __('Preview', 'amnesty')}</span>
            </ToolbarButton>
          </ToolbarGroup>
        </BlockControls>
        <Disabled.Consumer>
          {() =>
            isPreview ? (
              <>
                <SandBox html={attributes.content} styles={styles} />
                {/*
									An overlay is added when the block is not selected in order to register click events.
									Some browsers do not bubble up the clicks from the sandboxed iframe, which makes it
									difficult to reselect the block.
								*/}
                {!this.props.isSelected && (
                  <div className="block-library-html__preview-overlay"></div>
                )}
              </>
            ) : (
              <PlainText
                style={{ fontFamily: 'monospace' }}
                value={attributes.content}
                onChange={(content) => setAttributes({ content })}
                // translators: [admin]
                placeholder={__('Write HTML…', 'amnesty')}
                // translators: [admin]
                aria-label={__('HTML', 'amnesty')}
              />
            )
          }
        </Disabled.Consumer>
      </div>
    );
  }
}
export default withSelect((select) => {
  const { getSettings } = select('core/block-editor');
  return {
    styles: getSettings().styles,
  };
})(DisplayComponent);
