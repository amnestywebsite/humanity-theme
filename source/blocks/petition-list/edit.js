import { useEffect } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';

const edit = (props) => {
  const { attributes } = props;
  const { style } = attributes;

  // Add filter to modify quantity based on the style
  useEffect(() => {
    // Define the filter and capture the unsubscribe function
    const unsubscribe = addFilter(
      'amnesty-post-list-quantity',
      'amnesty/petition-list',
      (quantity, { style }) => {
        if (style === 'petition') {
          return 100;
        }
        return quantity;
      }
    );

    // Unsubscribe when the component unmounts
    return () => {
      unsubscribe();
    }
  }, [style]);

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Petition Settings', 'amnesty')}>
          {style}
        </PanelBody>
      </InspectorControls>
    </>
  );
};

export default edit;
