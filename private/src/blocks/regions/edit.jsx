import classnames from 'classnames';

import { each, filter, head, isEmpty, map } from 'lodash';
import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl, ToggleControl } from '@wordpress/components';
import { useEntityRecords } from '@wordpress/core-data';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import List from './components/List.jsx';

const clone = (thing) => JSON.parse(JSON.stringify(thing));

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

const filterTermsForRegionsOnly = (termsLoading, terms, regionsOnly) => {
  if (termsLoading || !Array.isArray(terms)) {
    return null;
  }

  const cloned = clone(terms);

  if (!regionsOnly) {
    return unflatten(cloned);
  }

  const filtered = cloned.filter((term) => ['region', 'subregion'].indexOf(term.type) !== -1);

  return unflatten(filtered);
};

function getCurrentTaxonomy(taxonomies, taxonomy) {
  const filteredTaxonomies = filter(taxonomies, (t) => t.amnesty);
  return head(filter(filteredTaxonomies, (t) => t.slug === taxonomy));
}

function getTaxonomyOptions(taxonomies) {
  const filteredTaxonomies = filter(taxonomies, (t) => t.amnesty);
  return map(filteredTaxonomies, (tax) => ({
    label: tax.name,
    value: tax.slug,
  }));
}

export default function Edit({ attributes, className, setAttributes }) {
  const { records: taxonomies, isResolving: taxonomiesLoading } = useEntityRecords(
    'root',
    'taxonomy',
  );
  const { records: terms, isResolving: termsLoading } = useEntityRecords(
    'taxonomy',
    attributes.taxonomy,
    { per_page: -1 },
  );

  const [current, setCurrent] = useState();
  const [options, setOptions] = useState();
  const [theTerms, setTheTerms] = useState();

  // transform taxonomy entities
  useEffect(() => {
    if (!taxonomiesLoading) {
      setCurrent(getCurrentTaxonomy(taxonomies, attributes.taxonomy));
      setOptions(getTaxonomyOptions(taxonomies));
    }
  }, [taxonomies, taxonomiesLoading, attributes.taxonomy]);

  // minimise depth if the currently-selected taxonomy is not hierarchical
  useEffect(() => {
    if (!taxonomiesLoading && current && !current?.hierarchical && attributes.depth !== 0) {
      setAttributes({ depth: 0 });
    }
  }, [attributes.depth, current, setAttributes, taxonomiesLoading]);

  // disable regions-only if the currently-selected taxonomy is not hierarchical
  useEffect(() => {
    if (!taxonomiesLoading && current && !current?.hierarchical && attributes.regionsOnly) {
      setAttributes({ regionsOnly: false });
    }
  }, [attributes.regionsOnly, current, setAttributes, taxonomiesLoading]);

  // manage available terms
  useEffect(() => {
    if (!taxonomiesLoading && !termsLoading && Array.isArray(terms)) {
      setTheTerms(filterTermsForRegionsOnly(termsLoading, terms, attributes.regionsOnly));
    }
  }, [attributes.regionsOnly, taxonomiesLoading, termsLoading, terms]);

  const classes = classnames(className, {
    [`has-${attributes.background}-background-color`]: !!attributes.background,
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={/* translators: [admin] */ __('Display Options', 'amnesty')}>
          <SelectControl
            /* translators: [admin] */
            label={__('Background Colour', 'amnesty')}
            options={[
              /* translators: [admin] */
              { label: __('White', 'amnesty'), value: '' },
              /* translators: [admin] */
              { label: __('Grey', 'amnesty'), value: 'very-light-gray' },
            ]}
            value={attributes.background}
            onChange={(background) => setAttributes({ background })}
          />
          <SelectControl
            /* translators: [admin] */
            label={__('Choose taxonomy to display', 'amnesty')}
            options={options}
            value={attributes.taxonomy}
            onChange={(taxonomy) => setAttributes({ taxonomy })}
          />
          {current?.hierarchical && (
            <>
              <RangeControl
                /* translators: [admin] */
                label={__('Max depth', 'amnesty')}
                min={0}
                max={3}
                value={attributes.depth}
                onChange={(depth) => setAttributes({ depth })}
              />
              <ToggleControl
                /* translators: [admin] */
                label={__('Show only Regions/Subregions', 'amnesty')}
                checked={attributes.regionsOnly}
                onChange={(regionsOnly) => setAttributes({ regionsOnly })}
              />
            </>
          )}
        </PanelBody>
      </InspectorControls>
      <aside {...useBlockProps({ className: classes })}>
        <RichText
          tagName="h2"
          format="string"
          className={attributes.alignment ? `is-${attributes.alignment}-aligned` : null}
          value={attributes.title}
          onChange={(title) => setAttributes({ title })}
          /* translators: [admin] */
          placeholder={__('Explore by Region', 'amnesty')}
          withoutInteractiveFormatting={true}
        />
        <List terms={theTerms} depth={0} maxDepth={attributes.depth} />
      </aside>
    </>
  );
}
