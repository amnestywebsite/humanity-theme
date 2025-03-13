import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, ToggleControl } from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

import createSelector from './createSelector.jsx';
import { createRange } from '../../../../utils/index';

const range = createRange(1, 100);

const CategorySelectorComponent = createSelector({
  /* translators: [admin] */
  label: __('Select a category', 'amnesty'),
  route: '/amnesty/v1/categories',
  filterCallback: (results) =>
    results.map((result) => ({
      label: result.parent ? `- ${result.name}` : result.name,
      value: result.term_id,
    })),
});

const CategorySelector = createHigherOrderComponent((OriginalComponent) => {
  const WrappedCategorySelectorComponent = (props) => (
    <InspectorControls>
      <PanelBody>
        <label>
          {/* translators: [admin] */ __('Category:', 'amnesty')}
          <br />
          <OriginalComponent {...props} />
          <br />
        </label>
        <RangeControl
          /* translators: [admin] */
          label={__('Number of posts to show:', 'amnesty')}
          min={1}
          max={8}
          value={props.attributes.amount || 3}
          onChange={(amount) => props.setAttributes({ amount: range(amount) })}
        />
        <ToggleControl
          /* translators: [admin] */
          label={__('Use related categories where supported', 'amnesty')}
          checked={props.attributes.categoryRelated}
          onChange={(categoryRelated) => props.setAttributes({ categoryRelated })}
        />
      </PanelBody>
    </InspectorControls>
  );

  return WrappedCategorySelectorComponent;
}, 'withInspectorControls');

export default CategorySelector(CategorySelectorComponent);
