const { filter, has, head, map } = lodash;
const { apiFetch } = wp;
const { BlockAlignmentToolbar, BlockControls, InspectorControls, RichText } = wp.blockEditor;
const { PanelBody, SelectControl } = wp.components;
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { addQueryArgs } = wp.url;

const groupTerms = (terms) => {
  const grouped = {};

  terms
    .filter(({ hidden, type }) => type === 'default' && !hidden)
    .forEach((term) => {
      const char = term.name.charAt(0).toUpperCase();
      if (!has(grouped, char)) {
        grouped[char] = [];
      }
      grouped[char].push(term);
    });

  return Object.keys(grouped)
    .sort()
    .reduce((object, key) => {
      // eslint-disable-next-line no-param-reassign
      object[key] = grouped[key];
      return object;
    }, {});
};

export default class DisplayComponent extends Component {
  state = {
    taxonomies: [],
    options: [],
    current: {},
    terms: [],
    cache: {},
    activeLetter: '',
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
    const prevTax = prevProps.attributes.taxonomy;
    const nextTax = this.props.attributes.taxonomy;
    const { current, taxonomies } = this.state;

    if (!nextTax) {
      return;
    }

    if (prevTax !== nextTax) {
      this.setState({ current: head(filter(taxonomies, (t) => t.slug === nextTax)) });
    }

    if (prevState.current !== current) {
      this.fetchTerms();
    }
  }

  fetchTerms = () => {
    const { cache, current } = this.state;

    const path = addQueryArgs(`/wp/v2/${current.rest_base}/`, {
      hide_empty: 'false',
      per_page: '250',
    });

    if (cache[path]) {
      this.setState({ terms: cache[path] });
      return;
    }

    apiFetch({ path }).then((data) => {
      const terms = groupTerms(data);

      this.setState({
        terms,
        activeLetter: Object.keys(terms)[0],
        cache: {
          ...cache,
          [path]: terms,
        },
      });
    });
  };

  setActiveLetter(letter) {
    const { terms } = this.state;

    if (terms[letter]) {
      this.setState({ activeLetter: letter });
    }
  }

  renderInspectorControls() {
    const { attributes, setAttributes } = this.props;
    const { options } = this.state;

    return (
      <InspectorControls>
        <PanelBody title={/* translators: [admin] */ __('Display Options', 'amnesty')}>
          <SelectControl
            // translators: [admin]
            label={__('Choose taxonomy to display', 'amnesty')}
            options={options}
            value={attributes.taxonomy}
            onChange={(taxonomy) => setAttributes({ taxonomy })}
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
    const { activeLetter, terms } = this.state;
    const letterItems = Array.from(terms[activeLetter] || []);

    return (
      <Fragment>
        {this.renderInspectorControls()}
        {this.renderBlockControls()}
        <aside className={className}>
          <RichText
            tagName="h2"
            format="string"
            className={attributes.alignment ? `is-${attributes.alignment}-aligned` : null}
            value={attributes.title}
            onChange={(title) => setAttributes({ title })}
            // translators: [admin]
            placeholder={__('A-Z of Countries and Regions', 'amnesty')}
            withoutInteractiveFormatting={true}
          />
          <div className="navigation">
            {Object.keys(this.state.terms).map((letter) => (
              <button
                key={letter}
                className={letter === activeLetter ? 'is-active' : null}
                onClick={() => this.setActiveLetter(letter)}
                disabled={(terms[letter] || []).length === 0}
              >
                {letter}
              </button>
            ))}
          </div>
          <div className="listContainer">
            <div className="activeLetter">{activeLetter}</div>
            <ul className="listItems">
              {letterItems.map((item) => (
                <li className="listItem" key={item.id}>
                  <a href={item.link}>{item.name}</a>
                </li>
              ))}
            </ul>
          </div>
        </aside>
      </Fragment>
    );
  }
}
