import classnames from 'classnames';

import List from './components/List';

import { each, filter, head, isEmpty, map } from 'lodash';
import apiFetch from '@wordpress/api-fetch';
import { BlockAlignmentToolbar, BlockControls, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl, ToggleControl } from '@wordpress/components';
import {  useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { addQueryArgs } from '@wordpress/url';
import { useSelect } from '@wordpress/data';

const unflatten = (list, parent = { id: 0 }, tree = []) => {
  const children = filter(list, (item) => item.parent === parent.id);

  if (!isEmpty(children)) {
    if (parent.id === 0) {
      // eslint-disable-next-line no-param-reassign
      tree = children;
    } else {
      // eslint-disable-next-line no-param-reassign
      parent.children = children;
    }

    each(children, (item) => unflatten(list, item));
  }

  return tree;
};

const fetchTerms = ({ attributes, state }) => {
  const { depth, regionsOnly } = attributes;
  const { cache, current, setTerms } = state;

  if (cache[current.rest_base]) {
    setTerms(cache[current.rest_base]);
    return;
  }

  let path = addQueryArgs(`/wp/v2/${current.rest_base}`, {
    hide_empty: 'false',
    per_page: '1',
  });

  if (depth < 1) {
    path = addQueryArgs(path, {
      parent: '0',
    });
  }

  const allTerms = [];

  apiFetch({ path })
    .then((data, status, response) => response.getResponseHeader('X-WP-TotalPages'))
    .then((termCount) => {
      const pages = Math.ceil(termCount / 100);
      const requests = [];

      for (let i = 1; i <= pages; i += 1) {
        const pagePath = addQueryArgs(path, {
          page: i,
          per_page: 100,
        });
        requests.push(apiFetch({ path: pagePath, parse: true }));
      }

      return Promise.all(requests);
    })
    .then((termPages) => {
      termPages.forEach((items) => {
        // items is [ {termx}, {termy} ]
        allTerms.push(...items);
      });
      return allTerms;
    })
    .then((terms) => {
      let filteredTerms = terms;

      if (regionsOnly) {
        filteredTerms = filteredTerms.filter(
          (term) => ['region', 'subregion'].indexOf(term.type) !== -1,
        );
      }

      if (depth > 0) {
        filteredTerms = unflatten(filteredTerms);
      }

      return filteredTerms;
    })
    .then((terms) => {
      setCache({ ...cache, [current.rest_base]: terms });
      setTerms(terms);
    });
};

let taxList = '';

const isLoading = useSelect((select) => {
  return select('core').isResolving('core', 'getTaxonomies');
});

if (!isLoading) {
  taxList = wp.data.select('core')?.getTaxonomies();
}


const edit = ({ attributes, setAttributes }) => {
  const [taxonomies, setTaxonomies] = useState([]);
  const [options, setOptions] = useState([]);
  const [current, setCurrent] = useState({});
  const [terms, setTerms] = useState([]);
  const [cache, setCache] = useState({});

  const mounted = useRef();

  useEffect(() => {
    if (!mounted?.current) {
      mounted.current = true;

      // Set the taxonomies using taxList
      const taxes = filter(taxList, (t) => t.amnesty);
      const newTax = head(filter(taxes, (t) => t.slug === attributes.taxonomy));
      const choices = map(taxes, (tax) => ({ label: tax.name, value: tax.slug }));
      setTaxonomies(taxes);
      setCurrent(newTax);
      setOptions(choices);

      // apiFetch({ path: '/wp/v2/taxonomies/' })
      //   .then((response) => {
      //     console.log(response);
      //     const taxes = filter(response, (t) => t.amnesty);
      //     const newTax = head(filter(taxes, (t) => t.slug === attributes.taxonomy));
      //     const choices = map(taxes, (tax) => ({ label: tax.name, value: tax.slug }));
      //     setTaxonomies(taxes);
      //     setCurrent(newTax);
      //     setOptions(choices);
      //   });
    }
  }, []);

  useEffect(() => {
    if (!attributes.taxonomy) {
      return;
    }

    const newCurrent = head(filter(taxonomies, (t) => t.slug === attributes.taxonomy));

    if (newCurrent.slug === current.slug) {
      return;
    }

    setCurrent(newCurrent);

    if (!newCurrent.hierarchical) {
      setAttributes({ depth: 0 });
    }
  }, [attributes.depth, attributes.regionsOnly, attributes.taxonomy]);

  // useEffect(() => {
  //   fetchTerms({ state: { cache, current, setCache, setTerms }, attributes });
  // }, [attributes.depth, attributes.taxonomy]);

  const hierarchical = current && current.hierarchical;
  const classes = classnames(classnames, {
    [`has-${attributes.background}-background-color`]: !!attributes.background,
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={/* translators: [admin] */ __('Display Options', 'amnesty')}>
          <SelectControl
            // translators: [admin]
            label={__('Background Colour', 'amnesty')}
            options={[
              // translators: [admin]
              { label: __('White', 'amnesty'), value: '' },
              // translators: [admin]
              { label: __('Grey', 'amnesty'), value: 'very-light-gray' },
            ]}
            value={attributes.background}
            onChange={(background) => setAttributes({ background })}
          />
          <SelectControl
            // translators: [admin]
            label={__('Choose taxonomy to display', 'amnesty')}
            options={options}
            value={attributes.taxonomy}
            onChange={(taxonomy) => setAttributes({ taxonomy })}
          />
          {hierarchical && (
            <RangeControl
              // translators: [admin]
              label={__('Max depth', 'amnesty')}
              min={0}
              max={3}
              value={attributes.depth}
              onChange={(depth) => setAttributes({ depth })}
            />
          )}
          <ToggleControl
            // translators: [admin]
            label={__('Show only Regions/Subregions', 'amnesty')}
            checked={attributes.regionsOnly}
            onChange={(regionsOnly) => setAttributes({ regionsOnly })}
          />
        </PanelBody>
      </InspectorControls>
      <BlockControls>
        <BlockAlignmentToolbar
          value={attributes.alignment}
          onChange={(alignment) => setAttributes({ alignment })}
        />
      </BlockControls>
      <aside className={classes}>
        <RichText
          tagName="h2"
          format="string"
          className={attributes.alignment ? `is-${attributes.alignment}-aligned` : null}
          value={attributes.title}
          onChange={(title) => setAttributes({ title })}
          // translators: [admin]
          placeholder={__('Explore by Region', 'amnesty')}
          withoutInteractiveFormatting={true}
        />
        <List terms={terms} depth={0} maxDepth={attributes.depth} />
      </aside>
    </>
  );
};

export default edit;
