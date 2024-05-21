const { PlainText, BlockControls, transformStyles } = wp.blockEditor;
const { ToolbarButton, Disabled, SandBox, ToolbarGroup } = wp.components;
const { useSelect, withSelect } = wp.data;
const { useEffect, useRef, useState } = wp.element;
const { __ } = wp.i18n;

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

const edit = ({ attributes, isSelected, setAttributes }) => {
  const editorStyles = useSelect((select) => {
    const { getSettings } = select('core/block-editor');
    return getSettings().styles;
  });

  const [isPreviewing, setIsPreviewing] = useState(false);
  const [styles, setStyles] = useState([]);

  const mounted = useRef();
  useEffect(() => {
    if (!mounted?.current) {
      mounted.current = true;
      setStyles([...defaultStyles, ...transformStyles(editorStyles)]);
    }
  }, []);

  return (
    <div className="wp-block-html">
      <BlockControls>
        <ToolbarGroup>
          <ToolbarButton
            className="components-tab-button"
            isPressed={!isPreviewing}
            onClick={() => setIsPreviewing(false)}
          >
            <span>{/* translators: [admin] */ __('HTML', 'amnesty')}</span>
          </ToolbarButton>
          <ToolbarButton
            className="components-tab-button"
            isPressed={isPreviewing}
            onClick={() => setIsPreviewing(true)}
          >
            <span>{/* translators: [admin] */ __('Preview', 'amnesty')}</span>
          </ToolbarButton>
        </ToolbarGroup>
      </BlockControls>
      <Disabled.Consumer>
        {() =>
          isPreviewing ? (
            <>
              <SandBox html={attributes.content} styles={styles} />
              {/*
                An overlay is added when the block is not selected in order to register click events.
                Some browsers do not bubble up the clicks from the sandboxed iframe, which makes it
                difficult to reselect the block.
              */}
              {!isSelected && (
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
};
