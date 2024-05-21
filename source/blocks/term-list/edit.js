const { filter, has, head, map } = lodash;
const { apiFetch } = wp;
const { BlockAlignmentToolbar, BlockControls, InspectorControls, RichText } = wp.blockEditor;
const { PanelBody, SelectControl } = wp.components;
const { useEntityRecord, useEntityRecords } = wp.data;
const { Component, useEffect, useRef, useState } = wp.element;
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

const edit = ({ attributes, setAttributes }) => {
  const [taxonomies, setTaxonomies] = useState([]);
  const [options, setOptions] = useState([]);
  const [current, setCurrent] = useState({});
  const [terms, setTerms] = useState([]);
  const [activeLetter, setActiveLetter] = useState('');

  const fetchTerms = () => {
    const path = addQueryArgs(`/wp/v2/${current.rest_base}/`, {
      hide_empty: 'false',
      per_page: '250',
    });

    if (cache[path]) {
      setTerms(cache[path]);
      return;
    }

    apiFetch({ path }).then((data) => {
      const groupedTerms = groupTerms(data);
      setTerms(groupedTerms);
      setActiveLetter(head(Object.keys(groupedTerms)));
      setCache({ ...cache, [path]: groupedTerms });
    });
  };

  const mounted = useRef();

  useEffect(() => {
    if (!mounted?.current) {
      mounted.current = true;

      const { taxonomy } = attributes;
      apiFetch({ path: '/wp/v2/taxonomies' })
        .then((response) => {
          const taxes = filter(response, (t) => t.amnesty);
          const newTax = head(filter(taxes, (t) => t.slug === taxonomy));
          const choices = map(taxes, (tax) => ({ label: tax.name, value: tax.slug }));
          setTaxonomies(taxes);
          setCurrent(newTax);
          setOptions(choices);
        });
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
    fetchTerms();
  }, [attributes.taxonomy]);


  const letterItems = Array.from(terms[activeLetter] || []);

  return (
    <>
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
      <BlockControls>
        <BlockAlignmentToolbar
          value={attributes.alignment}
          onChange={(alignment) => setAttributes({ alignment })}
        />
      </BlockControls>
      <aside className={className}>
        <RichText
          tagName="h2"
          format="string"
          className={attributes.alignment ? `is-${attributes.alignment}-aligned` : null}
          value={attributes.title}
          onChange={(title) => setAttributes({ title })}
          // translators: [admin]
          placeholder={__('A-Z of Countries and Regions', 'amnesty')}
          keepPlaceholderOnFocus={true}
          withoutInteractiveFormatting={true}
        />
        <div className="navigation">
          {Object.keys(terms).map((letter) => (
            <button
              key={letter}
              className={letter === activeLetter ? 'is-active' : null}
              onClick={() => setActiveLetter(terms?.[letter] || activeLetter)}
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
    </>
  );
};
