import { map } from 'lodash';
import { apiFetch } from '@wordpress/api-fetch';
import { SelectControl } from '@wordpress/components';
import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Render a select control to choose which term archive to feature the post on
 *
 * @param {Function} createMetaUpdate creates an update function
 * @param {Object} props the plugin props
 *
 * @returns {wp.element.Component|null}
 */
const TermFeature = ({ createMetaUpdate, props }) => {
  const mounted = useRef();

  const [loaded, setLoaded] = useState(false);
  const [termOptions, setTermOptions] = useState([]);

  useEffect(() => {
    if (!mounted.current) {
      mounted.current = true;

      apiFetch({ path: '/wp/v2/category?per_page=100' }).then((rawTerms) => {
        const defaultOption = {
          // translators: [admin]
          label: __('Select Term', 'amnesty'),
          value: '',
        };
        const selectItem = [defaultOption];
        const taxTerms = map(rawTerms, (tax) => ({ label: tax.name, value: tax.slug }));
        const terms = selectItem.concat(taxTerms);

        setTermOptions(terms);
        setLoaded(true);
      });
    }
  });

  if (!loaded) {
    return null;
  }

  return (
    <SelectControl
      // translators: [admin]
      label={__('Feature on content type:', 'amnesty')}
      value={props.meta.term_slider}
      options={termOptions}
      onChange={(value) => createMetaUpdate('term_slider', value, props.meta, props.oldMeta)}
    />
  );
};

export default TermFeature;
