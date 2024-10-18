import createSelector from '../components/createSelector';

import { createHigherOrderComponent } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

const TaxonomySelectorComponent = createSelector({
  // translators: [admin]
  label: __('Select a category', 'amnesty'),
  route: '/amnesty/v1/categories',
  filterCallback: (results) => {
    const options = [];

    Object.keys(results).map((key) => {
      let label = results[key].name;

      if (results[key].parent) {
        label = `- ${results.name}`;
      }

      options.push({ label, value: results[key].rest_base });

      return results[key];
    });

    return options;
  },
});

const TaxonomySelector = createHigherOrderComponent((OriginalComponent) => (props) => (
  <label>
    {/* translators: [admin] */ __('Taxonomy:', 'amnesty')}
    <br />
    <OriginalComponent {...props} />
    <br />
  </label>
), 'withWrappingMarkup');

export default TaxonomySelector(TaxonomySelectorComponent);
