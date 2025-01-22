import { createHigherOrderComponent } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

import createSelector from './createSelector.jsx';

const TermSelectorComponent = createSelector({
  /* translators: [admin] */
  label: __('Select terms', 'amnesty'),
  route: '/amnesty/v1/categories',
});

const TermSelector = createHigherOrderComponent((OriginalComponent) => {
  const WrappedTermSelectorComponent = (props) => (
    <label>
      <div className="term-selector">
        {/* translators: [admin] */ __('Terms:', 'amnesty')}
        <br />
        <OriginalComponent {...props} />
      </div>
    </label>
  );

  return WrappedTermSelectorComponent;
}, 'withWrappingMarkup');

export default TermSelector(TermSelectorComponent);
