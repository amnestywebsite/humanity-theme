import createSelector from '../components/createSelector';

import { createHigherOrderComponent } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

const TermSelectorComponent = createSelector({
  // translators: [admin]
  label: __('Select terms', 'amnesty'),
  route: '/amnesty/v1/categories',
});

const TermSelector = createHigherOrderComponent((OriginalComponent) => (props) => (
  <label>
    <div className="term-selector">
      {/* translators: [admin] */ __('Terms:', 'amnesty')}
      <br />
      <OriginalComponent {...props} />
    </div>
  </label>
), 'withWrappingMarkup');

export default TermSelector(TermSelectorComponent);
