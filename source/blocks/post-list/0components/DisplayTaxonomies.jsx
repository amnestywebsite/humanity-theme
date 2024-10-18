import LinkList from './display/LinkList.jsx';
import GridItem from './display/GridItem.jsx';
import * as api from './post-selector/api';

import { difference, isString, isEqual } from 'lodash';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

class DisplayTaxonomies extends Component {
  constructor(...args) {
    super(...args);
    this.state = {
      results: [],
      loading: false,
    };
  }

  static normaliseCategory = (category = '[]') => {
    let normal = category;

    if (isString(normal)) {
      normal = JSON.parse(normal);
    }

    if (!Array.isArray(normal)) {
      normal = [normal];
    }

    normal = normal.map((val) => {
      if (isString(val)) {
        return JSON.parse(val);
      }

      return val;
    });

    return normal.filter(Boolean);
  };

  componentDidMount() {
    if (this.props.taxonomy) {
      this.fetchPostsByTaxonomy();
    }
  }

  componentDidUpdate(prevProps) {
    if (!prevProps.taxonomy && !this.props.taxonomy) {
      return;
    }

    if (!prevProps.terms && !this.props.terms) {
      return;
    }

    const prevTaxonomy = prevProps.taxonomy;
    const nextTaxonomy = this.props.taxonomy;

    if (!isEqual(prevTaxonomy, nextTaxonomy)) {
      this.fetchPostsByTaxonomy();
    }

    const prev = prevProps.terms;
    const next = this.props.terms;

    if (!next) {
      this.fetchPostsByTaxonomy();
      return;
    }

    if (!prev) {
      this.fetchPostsByTaxonomy();
      return;
    }

    if (prev.length !== next.length || next === null) {
      this.fetchPostsByTaxonomy();
      return;
    }

    let propsAreEquivalent = true;

    next.forEach((a, i) => {
      const b = prev[i];
      const aKeys = Object.keys(a);
      const bKeys = Object.keys(b);

      if (difference(aKeys, bKeys).length > 0) {
        propsAreEquivalent = false;
        return;
      }

      aKeys.forEach((k) => {
        if (a[k] === b[k]) {
          return;
        }

        propsAreEquivalent = false;
      });
    });

    if (!propsAreEquivalent) {
      this.fetchPostsByTaxonomy();
    }
  }

  fetchPostsByTaxonomy() {
    const { taxonomy, terms } = this.props;

    const defaultArgs = {
      per_page: 10,
      type: 'post',
    };

    const requestArguments = {
      ...defaultArgs,
    };

    api
      .getPostsFromTerms(requestArguments, taxonomy, terms)
      // eslint-disable-next-line default-param-last
      .then((data = [], i, xhr) => {
        const posts = data.map((p) => {
          if (!p.featured_media || p.featured_media < 1) {
            return {
              ...p,
              featured_image: false,
            };
          }

          return {
            ...p,
            // eslint-disable-next-line no-underscore-dangle
            featured_image: p._embedded['wp:featuredmedia'][0].source_url || false,
          };
        });

        return {
          xhr,
          data: posts,
        };
      })
      .then(({ data = [] }) => {
        this.setState({
          results: DisplayTaxonomies.alterResults(data),
          loading: false,
        });
      })
      .catch(() => {
        this.setState({
          results: [],
          loading: false,
        });
      });
  }

  fetchPostsByCategory() {
    const { category } = this.props;

    let value = DisplayTaxonomies.normaliseCategory(category);

    if (!value.length) {
      this.setState({
        results: [],
        category: [],
      });
      return;
    }

    this.setState({
      loading: true,
    });

    // We store category as string of an array of objects
    // to retain the label for the select box.
    value = value.map((v) => v.value).join(',');

    wp.apiRequest({
      path: `/wp/v2/posts/?categories=${value}&_embed`,
    }).then((results) =>
      this.setState({
        results: DisplayTaxonomies.alterResults(results),
        loading: false,
      }),
    );
  }

  static strip = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || '';
  };

  static alterResults = (response) =>
    response.map((resp) => {
      // eslint-disable-next-line no-underscore-dangle
      const tags = resp._embedded['wp:term']
        .reduce((prev, curr) => [...prev, ...curr], [])
        .map((tag) => ({
          title: tag.name,
          link: tag.link,
        }));

      let featuredImage = false;

      if (resp.featured_media || resp.featured_media > 0) {
        // eslint-disable-next-line no-underscore-dangle
        featuredImage = resp._embedded['wp:featuredmedia'][0].source_url || false;
      }

      let excerpt = DisplayTaxonomies.strip(resp.excerpt.rendered);
      excerpt = excerpt.length > 250 ? `${excerpt.slice(0, 250)}...` : '';

      return {
        id: resp.id,
        title: resp.title.rendered,
        link: resp.link,
        tag: tags.shift(),
        excerpt,
        featured_image: featuredImage,
        authorName: resp.authorName,
        date: resp.datePosted,
      };
    });

  render() {
    const { style, prefix, showAuthor, showPostDate } = this.props;
    const { loading, results } = this.state;
    const { taxonomy } = this.props;
    const isList = style === 'list';
    const isGrid = style === 'grid';
    const hasResults = results.length > 0;

    if (loading) {
      return (
        <div>
          <p>{/* translators: [admin] */ __('Loading…', 'amnesty')}</p>
        </div>
      );
    }

    if (!taxonomy) {
      return (
        <div>
          <p className="linklist-container">
            {/* translators: [admin] */ __('Select a taxonomy.', 'amnesty')}
          </p>
        </div>
      );
    }

    if (!hasResults) {
      return (
        <div>
          <p className="linklist-container">
            {/* translators: [admin] */ __('No Items found', 'amnesty')}
          </p>
        </div>
      );
    }

    if (isList) {
      return (
        <div>
          <ul className="linkList linklist-container">
            {results
              .filter((item, i) => i < this.props.amount)
              .map((result) => (
                <LinkList
                  key={`${prefix}-${result.id}`}
                  {...result}
                  showAuthor={showAuthor}
                  showPostDate={showPostDate}
                />
              ))}
          </ul>
        </div>
      );
    }

    if (isGrid) {
      return [1, 2, 3, 5, 6, 7].indexOf(this.props.amount) > -1 ? (
        <div>
          <div className={`grid grid-${this.props.amount}`}>
            {results
              .filter((item, i) => i < this.props.amount)
              .map((result) => (
                <GridItem key={`${prefix}-${result.id}`} {...result} />
              ))}
          </div>
        </div>
      ) : (
        <div>
          <div className={`grid grid-many`}>
            {results
              .filter((item, i) => i < this.props.amount)
              .map((result) => (
                <GridItem key={`${prefix}-${result.id}`} {...result} />
              ))}
          </div>
        </div>
      );
    }

    return <div></div>;
  }
}

export default DisplayTaxonomies;
