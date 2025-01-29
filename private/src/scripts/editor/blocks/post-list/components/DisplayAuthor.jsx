import LinkList from './display/LinkList.jsx';
import GridItem from './display/GridItem.jsx';
import * as api from './post-selector/api';

const { difference, isString } = lodash;
const { Component } = wp.element;
const { __ } = wp.i18n;

class DisplayAuthor extends Component {
  constructor(...args) {
    super(...args);
    this.state = {
      results: [],
      loading: false,
    };
  }

  static normaliseAuthor = (author = '[]') => {
    let normal = author;

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
    if (this.props.authors) {
      this.fetchPostsByAuthor();
    }
  }

  componentDidUpdate(prevProps) {
    if (!prevProps.author && !this.props.authors) {
      return;
    }

    const prev = DisplayAuthor.normaliseAuthor(prevProps.authors);
    const next = DisplayAuthor.normaliseAuthor(this.props.authors);

    if (prev.length !== next.length) {
      this.fetchPostsByAuthor();
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
      this.fetchPostsByAuthor();
    }
  }

  fetchPostsByAuthor() {
    const { authors } = this.props;

    const defaultArgs = {
      per_page: 10,
      type: 'post',
    };

    const requestArguments = {
      ...defaultArgs,
    };

    let value = DisplayAuthor.normaliseAuthor(authors);

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

    api
      .getPostsFromAuthors(requestArguments, value)
      .then((data = [], i, xhr) => { // eslint-disable-line
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
          results: DisplayAuthor.alterResults(data),
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

      let excerpt = DisplayAuthor.strip(resp.excerpt.rendered);
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
    // const category = this.normaliseCategory(this.props.category);
    const { authors } = this.props;
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

    if (!authors) {
      return (
        <div>
          <p className="linklist-container">
            {/* translators: [admin] */ __('Select an author.', 'amnesty')}
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

    const hasMany = this.props.amount % 4 === 0 || this.props.amount > 8;

    if (isGrid) {
      if (hasMany) {
        return (
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

      return (
        <div>
          <div className={`grid grid-${this.props.amount}`}>
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

export default DisplayAuthor;
