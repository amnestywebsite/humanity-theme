import classNames from 'classnames';
import layouts from './layouts';
import { useBlockProps, InnerBlocks, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { times } from 'lodash';

const Edit = (props) => {
  const { attributes, setAttributes } = props;

  // Create the update function for attribute change
  const createUpdateAttribute = (key) => (value) => setAttributes({ [key]: value });

  // Generate layout options for the SelectControl
  const options = Object.keys(layouts).map((key) => ({
    value: key,
    label: layouts[key].name,
  }));

  // Default layout key, ensuring it is valid
  const layoutKey = attributes.layout && layouts[attributes.layout] ? attributes.layout : '1/2|1/2';

  // Generate current template based on layout columns
  const currentTemplate = layouts[layoutKey] ? times(layouts[layoutKey].columns, () => [
    'amnesty-core/block-row-column',
  ]) : [];

  // Block props for styling
  const blockProps = useBlockProps({
    className: classNames({
      row: true,
      [`layout-${layoutKey}`]: true,
    }),
  });

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={/* translators: [admin] */ __('Options', 'amnesty')}>
          <SelectControl
            // translators: [admin]
            label={__('Layout Style', 'amnesty')}
            options={options}
            value={attributes.layout || layoutKey}  // Default to layoutKey if undefined
            onChange={createUpdateAttribute('layout')}
          />
        </PanelBody>
      </InspectorControls>
      <div {...blockProps}>
        <InnerBlocks
          template={currentTemplate}
          templateLock="all"
          allowedBlocks={['amnesty-core/block-row-column']}
        />
      </div>
    </Fragment>
  );
};

export default Edit;
