/**
 * Third-party
 */
import classnames from 'classnames';
import memoize from 'memize';

/**
 * WordPress
 */
const { times } = lodash;
const { InnerBlocks, InspectorControls } = wp.blockEditor;
const { PanelBody, RangeControl, SelectControl, ToggleControl } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;

/**
 * Module-specific
 */
// blocks allowed to be contained within the repeatable block
const ALLOWED_BLOCKS = ['amnesty-core/links-with-icons'];
// Returns the layouts configuration for a given number of repeats.
const getLayoutTemplate = memoize((blocks) => times(blocks, () => ALLOWED_BLOCKS));
export default class DisplayComponent extends Component {
  render = () => {
    const { attributes, setAttributes } = this.props;

    const buttonIconOptions = [
      {
        // translators: [admin]
        label: __('None', 'amnesty'),
        value: 'none',
      },
      {
        // translators: [admin]
        label: __('Arrow', 'amnesty'),
        value: 'arrow',
      },
      {
        // translators: [admin]
        label: __('Ampersand', 'amnesty'),
        value: 'ampersand',
      },
    ];

    const {
      backgroundColor,
      className,
      hideLines,
      orientation = 'horizontal',
      quantity,
      dividerIcon = 'none',
      style,
    } = attributes;

    const classes = classnames(
      'linksWithIcons-group',
      `is-${orientation}`,
      `has-${quantity}-items`,
      {
        [className]: !!className,
        'has-background': !!backgroundColor,
        'has-no-lines': !!hideLines,
        [`has-${backgroundColor}-background-color`]: !!backgroundColor,
        [`icon-${dividerIcon}`]: !!dividerIcon,
      },
    );

    if (style === 'square' && orientation !== 'horizontal') {
      setAttributes({ orientation: 'horizontal' });
    }

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody>
            <RangeControl
              // translators: [admin]
              label={__('Quantity', 'amnesty')}
              value={quantity}
              onChange={(newQuantity) => setAttributes({ quantity: newQuantity })}
              min={1}
              max={24}
            />
            {style !== 'square' && (
              <SelectControl
                // translators: [admin]
                label={__('Orientation', 'amnesty')}
                value={orientation}
                onChange={(newOrientation) => setAttributes({ orientation: newOrientation })}
                options={[
                  // translators: [admin]
                  { value: 'horizontal', label: __('Horizontal', 'amnesty') },
                  // translators: [admin]
                  { value: 'vertical', label: __('Vertical', 'amnesty') },
                ]}
              />
            )}
            <SelectControl
              // translators: [admin]
              label={__('Background Colour', 'amnesty')}
              value={backgroundColor}
              onChange={(newBgColor) => setAttributes({ backgroundColor: newBgColor })}
              options={[
                // translators: [admin]
                { value: '', label: __('None', 'amnesty') },
                // translators: [admin]
                { value: 'very-light-gray', label: __('Grey', 'amnesty') },
              ]}
            />
            {style !== 'square' && (
              <ToggleControl
                // translators: [admin]
                label={__('Hide Lines', 'amnesty')}
                checked={hideLines}
                onChange={(newHideLines) => setAttributes({ hideLines: newHideLines })}
              />
            )}
            <SelectControl
              className="radio-control-icons"
              // translators: [admin]
              label={__('Divider Style', 'amnesty')}
              value={attributes.dividerIcon}
              onChange={(value) => {
                setAttributes({ dividerIcon: value });
              }}
              options={buttonIconOptions}
            />
          </PanelBody>
        </InspectorControls>
        <div className={classes}>
          <InnerBlocks
            template={getLayoutTemplate(quantity)}
            templateLock="all"
            allowedBlocks={ALLOWED_BLOCKS}
          />
        </div>
      </Fragment>
    );
  };
}
