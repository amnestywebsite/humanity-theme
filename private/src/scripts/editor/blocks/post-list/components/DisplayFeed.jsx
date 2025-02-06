import LinkList from './display/LinkList.jsx';
import GridItem from './display/GridItem.jsx';
import PetitionItem from './display/PetitionItem.jsx';

const { get, isString } = lodash;
const { Component } = wp.element;
const { __ } = wp.i18n;

class DisplayFeed extends Component {
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
    this.fetchPostsByPostType();
  }

  fetchPostsByPostType() {
    const { amount, overrideTypes } = this.props;

    const postTypes = [];

    Object.keys(overrideTypes).map((key) => {
      postTypes.push(key);
      return postTypes;
    });

    if (!postTypes.length) {
      this.setState({
        results: [],
        types: [],
      });
      return;
    }

    this.setState({
      loading: true,
    });

    const postString = postTypes.join(', ');

    wp.apiRequest({
      path: `/wp/v2/${postString}?_embed&per_page=${amount}`,
    }).then((results) => {
      this.setState({
        results: DisplayFeed.alterResults(results),
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
      const tags = [];
      const sources = [
        '_links["wp:featuredmedia"][0].media_details.sizes["post-half@2x"].source_url',
        '_links["wp:featuredmedia"][0].source_url',
        '_embedded["wp:featuredmedia"][0].media_details.sizes["post-half@2x"].source_url',
        '_embedded["wp:featuredmedia"][0].source_url',
      ];

      let featuredImage = false;

      sources.forEach((source) => {
        featuredImage = featuredImage || get(resp, source, false);
      });

      let excerpt = DisplayFeed.strip(resp.excerpt.rendered);
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

    const isList = style === 'list';
    const isGrid = style === 'grid';
    const isPetition = style === 'petition';
    const hasResults = results.length > 0;

    if (loading) {
      return (
        <div>
          <p>{/* translators: [admin] */ __('Loading…', 'amnesty')}</p>
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
            <div className="grid grid-many">
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

    if (isPetition) {
      if (hasMany) {
        return (
          <div>
            <div className="grid grid-many petition-grid">
              {results
                .filter((item, i) => i < this.props.amount)
                .map((result) => (
                  <PetitionItem key={`${this.props.prefix}-${result.id}`} {...result} />
                ))}
            </div>
          </div>
        );
      }

      return (
        <div className={`grid grid-${results.length} petition-grid`}>
          {results.map((result) => (
            <PetitionItem key={`${this.props.prefix}-${result.id}`} {...result} />
          ))}
        </div>
      );
    }

    return <div></div>;
  }
}

export default DisplayFeed;
