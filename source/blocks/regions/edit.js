import classnames from 'classnames';

import List from './components/List';

import { each, filter, head, isEmpty, map } from 'lodash';
import { BlockAlignmentToolbar, BlockControls, InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl, ToggleControl } from '@wordpress/components';
import { useEntityRecords } from '@wordpress/core-data';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

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

  let filtered = cloned.filter(
    (term) => ['region', 'subregion'].indexOf(term.type) !== -1,
  );

  return unflatten(filtered);
};

const edit = ({ attributes, className, setAttributes }) => {
  let { records: taxonomies, isResolving: taxonomiesLoading } = useEntityRecords('root', 'taxonomy');
  let { records: terms, isResolving: termsLoading } = useEntityRecords('taxonomy', attributes.taxonomy, { per_page: -1 });

  let filteredTerms = filterTermsForRegionsOnly(termsLoading, terms, attributes.regionsOnly);
  let current, options;

  if (!taxonomiesLoading) {
    taxonomies = filter(taxonomies, (t) => t.amnesty);

    current = head(filter(taxonomies, (t) => t.slug === attributes.taxonomy));
    options = map(taxonomies, (tax) => ({ label: tax.name, value: tax.slug }));
  }

  if (current && !current?.hierarchical && attributes.depth !== 0) {
    setAttributes({ depth: 0 });
  }

  useEffect(() => {
    filteredTerms = filterTermsForRegionsOnly(termsLoading, terms, attributes.regionsOnly);
  }, [attributes.regionsOnly]);

  const classes = classnames(className, {
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
          {current?.hierarchical && (
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
      <aside {...useBlockProps({ className: classes })}>
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
        <List terms={filteredTerms} depth={0} maxDepth={attributes.depth} />
      </aside>
    </>
  );
};

export default edit;
