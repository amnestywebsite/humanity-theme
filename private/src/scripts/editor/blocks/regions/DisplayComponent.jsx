import classnames from 'classnames';

const { each, filter, head, isEmpty, map } = lodash;
const { apiFetch } = wp;
const { BlockAlignmentToolbar, BlockControls, InspectorControls, RichText } = wp.blockEditor;
const { PanelBody, RangeControl, SelectControl, ToggleControl } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { addQueryArgs } = wp.url;

const List = ({ terms = [], depth = 0, maxDepth }) => (
  <ul className={depth === 0 ? 'listItems' : 'children'}>
    {Array.from(terms).map((term) => (
      <li key={term.id} className={term.children ? 'has-children' : null}>
        <span>{term.name}</span>
        {depth < maxDepth && <List terms={term.children} depth={depth + 1} maxDepth={maxDepth} />}
      </li>
    ))}
  </ul>
);

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

export default class DisplayComponent extends Component {
  state = {
    taxonomies: [],
    options: [],
    current: {},
    terms: [],
    cache: {},
  };

  componentDidMount() {
    const { taxonomy } = this.props.attributes;

    apiFetch({ path: '/wp/v2/taxonomies/' }).then((rawTaxonomies) => {
      const taxonomies = filter(rawTaxonomies, (t) => t.amnesty);
      const current = head(filter(taxonomies, (t) => t.slug === taxonomy));
      const options = map(taxonomies, (tax) => ({ label: tax.name, value: tax.slug }));
      this.setState({ current, options, taxonomies });
    });
  }

  componentDidUpdate(prevProps, prevState) {
    const prevDepth = prevProps.attributes.depth;
    const prevTax = prevProps.attributes.taxonomy;
    const nextTax = this.props.attributes.taxonomy;
    const prevRegionsOnly = prevProps.attributes.regionsOnly;
    const { regionsOnly, depth } = this.props.attributes;
    const { current, taxonomies } = this.state;

    if (!nextTax) {
      return;
    }

    if (prevTax !== nextTax) {
      const currentTax = head(filter(taxonomies, (t) => t.slug === nextTax));
      this.setState({ current: currentTax });
      if (currentTax.hierarchical === false) {
        this.props.setAttributes({ depth: 0 });
      }
    }

    if (prevState.current !== current || prevRegionsOnly !== regionsOnly || prevDepth !== depth) {
      this.fetchTerms(prevRegionsOnly !== regionsOnly);
    }
  }

  fetchTerms = (bypassCache = false) => {
    const { regionsOnly, depth } = this.props.attributes;
    const { cache, current } = this.state;

    if (cache[current.rest_base] && !bypassCache) {
      this.setState({ terms: cache[current.rest_base] });
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

    wp.apiRequest({ path })
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
      .then((terms) =>
        this.setState({
          terms,
          cache: {
            ...cache,
            [current.rest_base]: terms,
          },
        }),
      );
  };

  renderInspectorControls() {
    const { attributes, setAttributes } = this.props;
    const { options, current } = this.state;
    const hierarchical = current && current.hierarchical;

    return (
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
    );
  }

  renderBlockControls() {
    const { attributes, setAttributes } = this.props;

    return (
      <BlockControls>
        <BlockAlignmentToolbar
          value={attributes.alignment}
          onChange={(alignment) => setAttributes({ alignment })}
        />
      </BlockControls>
    );
  }

  render() {
    const { attributes, className, setAttributes } = this.props;
    const { terms } = this.state;
    const classes = classnames(className, {
      [`has-${attributes.background}-background-color`]: !!attributes.background,
    });

    return (
      <Fragment>
        {this.renderInspectorControls()}
        {this.renderBlockControls()}
        <aside className={classes}>
          <RichText
            tagName="h2"
            format="string"
            className={attributes.alignment ? `is-${attributes.alignment}-aligned` : null}
            value={attributes.title}
            onChange={(title) => setAttributes({ title })}
            /* translators: [front] Deafult text can be changed in CMS for https://www.amnesty.org/en/countries/ https://wordpresstheme.amnesty.org/blocks/b026-regions-list-block/ */
            placeholder={__('Explore by Region', 'amnesty')}
            withoutInteractiveFormatting={true}
          />
          <List terms={terms} depth={0} maxDepth={attributes.depth} />
        </aside>
      </Fragment>
    );
  }
}
