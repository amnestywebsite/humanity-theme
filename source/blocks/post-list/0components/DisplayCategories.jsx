import LinkList from './display/LinkList.jsx';
import GridItem from './display/GridItem.jsx';
import PetitionItem from './display/PetitionItem.jsx';

import { difference, isString } from 'lodash';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

class DisplayCategories extends Component {
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
    this.fetchPostsByCategory();
  }

  componentDidUpdate(prevProps) {
    if (!prevProps.category && !this.props.category) {
      return;
    }

    const prev = DisplayCategories.normaliseCategory(prevProps.category);
    const next = DisplayCategories.normaliseCategory(this.props.category);

    if (prev.length !== next.length) {
      this.fetchPostsByCategory();
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
      this.fetchPostsByCategory();
    }
  }

  fetchPostsByCategory() {
    const { category, overrideTypes } = this.props;

    let value = DisplayCategories.normaliseCategory(category);

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

    let restPath = `/wp/v2/posts?category=${value}&per_page=${this.props.amount}&_embed`;

    if (overrideTypes) {
      const overrideBase = `${overrideTypes[Object.keys(overrideTypes)[0]].rest_base}`;
      restPath = `/wp/v2/${overrideBase}?category=${value}&per_page=${this.props.amount}&_embed`;
    }

    // /posts?filter[category_name]=MyCategory
    wp.apiRequest({
      path: restPath,
    }).then((results) => {
      this.setState({
        results: DisplayCategories.alterResults(results),
        loading: false,
      });
    });
  }

  static strip = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || '';
  };

  static alterResults = (response) =>
    response.map((resp) => {
      // eslint-disable-next-line no-underscore-dangle
      let tags = [];
      // eslint-disable-next-line no-underscore-dangle
      if (resp._embedded['wp:term']) {
        // eslint-disable-next-line no-underscore-dangle
        tags = resp._embedded['wp:term']
          .reduce((prev, curr) => [...prev, ...curr], [])
          .map((tag) => ({
            title: tag.name,
            link: tag.link,
          }));
      }

      let featuredImage = false;

      if (resp.featured_media || resp.featured_media > 0) {
        const { get } = lodash;
        featuredImage =
          get(
            resp,
            '_embedded["wp:featuredmedia"][0].media_details.sizes["post-half@2x"].source_url',
          ) || get(resp, '_embedded["wp:featuredmedia"][0].source_url', false);
      }

      let excerpt = DisplayCategories.strip(resp.excerpt.rendered);
      excerpt = excerpt.length > 250 ? `${excerpt.slice(0, 250)}...` : '';

      return {
        id: resp.id,
        title: resp.title.rendered,
        link: resp.link,
        tag: tags.shift(),
        excerpt,
        featured_image: featuredImage,
        date: resp.datePosted,
        authorName: resp.authorName,
      };
    });

  render() {
    const { style, prefix, showAuthor, showPostDate } = this.props;
    const { loading, results } = this.state;
    const category = DisplayCategories.normaliseCategory(this.props.category);

    const isList = style === 'list';
    const isGrid = style === 'grid';
    const isPetition = style === 'petition';
    const hasCategory = category.length > 0;
    const hasResults = results.length > 0;

    if (loading) {
      return (
        <div>
          <p>{/* translators: [admin] */ __('Loading…', 'amnesty')}</p>
        </div>
      );
    }

    if (!hasCategory) {
      return (
        <div>
          <p className="linklist-container">
            {/* translators: [admin] */ __('Select a category.', 'amnesty')}
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

    if (isPetition) {
      return [1, 2, 3, 5, 6, 7].indexOf(this.props.amount) > -1 ? (
        <div>
          <div className={`grid grid-${this.props.amount} petition-grid`}>
            {results
              .filter((item, i) => i < this.props.amount)
              .map((result) => (
                <PetitionItem key={`${this.props.prefix}-${result.id}`} {...result} />
              ))}
          </div>
        </div>
      ) : (
        <div>
          <div className={`grid grid-many petition-grid`}>
            {results
              .filter((item, i) => i < this.props.amount)
              .map((result) => (
                <PetitionItem key={`${this.props.prefix}-${result.id}`} {...result} />
              ))}
          </div>
        </div>
      );
    }

    return <div></div>;
  }
}

export default DisplayCategories;
