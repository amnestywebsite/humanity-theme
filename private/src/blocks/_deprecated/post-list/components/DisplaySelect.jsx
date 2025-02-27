import SelectPreview from './SelectPreview.jsx';
import PostSelect from './post-selector/PostSelector.jsx';
import * as api from './post-selector/api';

const { Component } = wp.element;

/**
 * Returns a unique array of objects based on a desired key.
 * @param {array} arr - array of objects.
 * @param {string|int} key - key to filter objects by
 */
export const uniqueBy = (arr, key) => {
  const keys = [];
  return arr.filter((item) => {
    if (keys.indexOf(item[key]) !== -1) {
      return false;
    }

    return keys.push(item[key]);
  });
};

/**
 * Returns a unique array of objects based on the id property.
 * @param {array} arr - array of objects to filter.
 * @returns {*}
 */
export const uniqueById = (arr) => uniqueBy(arr, 'id');

/**
 * Debounce a function by limiting how often it can run.
 * @param {function} func - callback function
 * @param {Integer} wait - Time in milliseconds how long it should wait.
 * @returns {Function}
 */
export const debounce = (func, wait) => {
  let timeout = null;

  return () => {
    const context = this;
    // eslint-disable-next-line
    const args = arguments;

    const later = () => {
      func.apply(context, args);
    };

    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
};

/**
 * PostSelector Component
 */
class DisplaySelect extends Component {
  /**
   * Constructor for PostSelector Component.
   * Sets up state, and creates bindings for functions.
   * @param array args - All arguments.
   */
  constructor(...args) {
    super(...args);

    this.state = {
      filter: '',
      filterLoading: false,
      filterPosts: [],
      initialLoading: false,
      loading: false,
      pages: {},
      pagesTotal: {},
      paging: false,
      posts: [],
      type: 'post',
      types: [],
      taxonomies: [],
      taxonomy: '',
      terms: [],
      term: [],
    };
  }

  /**
   * When the component mounts it calls this function.
   * Fetches posts types, selected posts then makes first call for posts
   */
  componentDidMount() {
    this.setState({
      loading: true,
      initialLoading: true,
    });

    let defaultType;

    if (this.props.overrideTypes) {
      Object.keys(this.props.overrideTypes).map((key, index) => {
        if (index === 0) {
          // this.setState({ type: this.props.overrideTypes[key].rest_base });
          defaultType = key;
        }
        return key;
      });

      this.setState(
        {
          types: this.props.overrideTypes,
          type: defaultType !== null ? defaultType : 'post',
        },
        () => {
          this.retrieveSelectedPosts().then(() => {
            this.setState({
              initialLoading: false,
            });
            this.getPosts().then(() => {
              this.setState({ loading: false });
            });
          });
        },
      );
    } else {
      api.getPostTypes().then((data) => {
        const types = data;
        delete types.attachment;
        delete types.wp_block;
        delete types.sidebar;

        this.setState(
          {
            types,
          },
          () => {
            this.retrieveSelectedPosts().then(() => {
              this.setState({
                initialLoading: false,
              });
              this.getPosts().then(() => {
                this.setState({ loading: false });
              });
            });
          },
        );
      });
    }

    // get taxonomies to filter by
    api.getTaxonomies().then((items) => {
      this.setState({
        taxonomies: items,
      });
    });
  }

  /**
   * GetPosts wrapper, builds the request argument based state and parameters passed/
   * @param {object} args - desired arguments (can be empty).
   * @returns {Promise<T>}
   */
  getPosts = (args = {}) => {
    const pageKey = this.state.filter ? false : this.state.type;
    const { get } = lodash;
    const defaultArgs = {
      per_page: 10,
      type: this.state.type,
      search: this.state.filter,
      page: this.state.pages[pageKey] || 1,
      show_hidden: true,
    };

    const requestArguments = {
      ...defaultArgs,
      ...args,
    };

    Object.keys(this.state.types).filter((key) => {
      if (key === this.state.type) {
        requestArguments.restBase = this.state.types[key].rest_base;
      }
      return this.state.types;
    });

    return api
      .getPosts(requestArguments, this.state.taxonomy, this.state.term)
      .then((data, i, xhr) => {
        const posts = Array.from(data).map((p) => {
          if (!p.featured_media || p.featured_media < 1) {
            return {
              ...p,
              featured_image: false,
            };
          }

          return {
            ...p,
            featured_image:
              get(
                p,
                '_embedded["wp:featuredmedia"][0].media_details.sizes["logomark@2x"].source_url',
              ) || get(p, '_embedded["wp:featuredmedia"][0].source_url', false),
          };
        });

        return {
          xhr,
          data: posts,
        };
      })
      .then(({ data = [], xhr }) => {
        if (requestArguments.search) {
          this.setState({
            filterPosts:
              requestArguments.page > 1 ? uniqueById([...this.state.filterPosts, ...data]) : data,
            pages: {
              ...this.state.pages,
              filter: requestArguments.page,
            },
            pagesTotal: {
              ...this.state.pagesTotal,
              filter: xhr.getResponseHeader('x-wp-totalpages'),
            },
          });

          return { data, xhr };
        }

        this.setState({
          // was posts: data but causes issue with pagination on the post list,
          posts: uniqueById([...this.state.posts, ...data]),
          pages: {
            ...this.state.pages,
            [pageKey]: requestArguments.page,
          },
          pagesTotal: {
            ...this.state.pagesTotal,
            [pageKey]: xhr.getResponseHeader('x-wp-totalpages'),
          },
        });

        // return response to continue the chain
        return { data, xhr };
      });
  };

  /**
   * Gets the selected posts by id from the `posts` state object and
   * sorts them by their position in the selected array.
   *
   * @returns Array of objects.
   */
  getSelectedPosts = () => {
    const { selectedPosts } = this.props;

    const items = this.state.posts
      .filter(({ id }) => selectedPosts.indexOf(id) !== -1)
      .sort((a, b) => {
        const aIndex = this.props.selectedPosts.indexOf(a.id);
        const bIndex = this.props.selectedPosts.indexOf(b.id);

        if (aIndex > bIndex) {
          return 1;
        }

        if (aIndex < bIndex) {
          return -1;
        }

        return 0;
      });

    return items;
  };

  /**
   * Makes the necessary api calls to fetch the selected posts and returns a promise.
   * @returns {*}
   */
  retrieveSelectedPosts = () => {
    const selected = this.props.selectedPosts;
    const { types } = this.state;

    if (!selected.length > 0) {
      // return a fake promise that auto resolves.
      // this possibly needs refactoring.
      // eslint-disable-next-line no-promise-executor-return
      return new Promise((resolve) => resolve());
    }

    return Promise.all(
      Object.keys(types).map((type) =>
        this.getPosts({
          include: this.props.selectedPosts.join(','),
          per_page: 100,
          type,
        }),
      ),
    );
  };

  /**
   * Adds desired post id to the selectedPosts List
   * @param {Integer} post_id
   */
  addPost = (postId) => {
    if (this.state.filter) {
      const post = this.state.filterPosts.filter((p) => p.id === postId);
      const posts = uniqueById([...this.state.posts, ...post]);

      this.setState({
        posts,
      });
    }

    this.updateSelectedPosts([...this.props.selectedPosts, postId]);
  };

  /**
   * Removes desired post id to the selectedPosts List
   * @param {Integer} postId
   */
  removePost = (postId) => {
    this.updateSelectedPosts([...this.props.selectedPosts].filter((id) => id !== postId));
  };

  reorderPosts = (posts) => {
    const items = Array.from(this.props.selectedPosts);
    const [reorderedItem] = items.splice(posts.source.index, 1);
    items.splice(posts.destination.index, 0, reorderedItem);

    this.updateSelectedPosts(items);
  };

  /**
   * Update the selected posts attributes.
   * @param posts
   * @returns {*}
   */
  updateSelectedPosts = (posts) => {
    const uniq = [...new Set(posts)];

    this.props.setAttributes({
      selectedPosts: [...uniq],
    });
  };

  /**
   * Event handler for when the post type select box changes in value.
   * @param string type - comes from the event object target.
   */
  handlePostTypeChange = ({ target: { value: type = '' } = {} } = {}) => {
    this.setState({ type, loading: true }, () => {
      // fetch posts, then set loading = false
      this.getPosts().then(() => this.setState({ loading: false }));
    });
  };

  /**
   * Event handler for when the post type select box changes in value.
   * @param string type - comes from the event object target.
   */
  handleTaxonomyChange = ({ target: { value: taxonomy = '' } = {} } = {}) => {
    const allTerms = api.getTerms(taxonomy);

    allTerms
      .then((data) => {
        this.setState({ terms: data });
      })
      .then(() => {
        this.setState({ taxonomy, loading: true }, () => {
          this.getPosts().then(() => this.setState({ loading: false }));
        });
      });

    // this.setState({ taxonomy, loading: true }, () => {
    //   this.getPosts().then(() => this.setState({ loading: false }));
    // });
  };

  handleTermChange = ({ target: { value: term = '' } = {} } = {}) => {
    this.setState({ term, loading: true }, () => {
      this.getPosts().then(() => this.setState({ loading: false }));
    });
  };

  /**
   * Handles the search box input value
   * @param string type - comes from the event object target.
   */
  handleInputFilterChange = ({ target: { value: filter = '' } = {} } = {}) =>
    this.setState(
      {
        filter,
      },
      () => {
        if (!filter) {
          // remove filtered posts
          return this.setState({ filteredPosts: [], filtering: false });
        }

        return this.doPostFilter();
      },
    );

  /**
   * Actual api call for searching for query, this function is debounced in constructor.
   */
  doPostFilter = () => {
    const { filter = '' } = this.state;

    if (!filter) {
      return;
    }

    this.setState({
      filtering: true,
      filterLoading: true,
    });

    this.getPosts().then(() => {
      this.setState({
        filterLoading: false,
      });
    });
  };

  /**
   * Handles the pagination of post types.
   */
  doPagination = () => {
    this.setState({
      paging: true,
    });

    const pageKey = this.state.filter ? 'filter' : this.state.type;
    const page = parseInt(this.state.pages[pageKey], 10) + 1 || 2;

    this.getPosts({ page }).then(() =>
      this.setState({
        paging: false,
      }),
    );
  };

  /**
   * Strip html tag from the content
   * @param {string} html - Current html content
   * @returns {string}
   */
  static strip = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || '';
  };

  getPreviewPosts() {
    const selectedPosts = this.getSelectedPosts();

    if (!selectedPosts) {
      return [];
    }

    let tags = [];
    const results = selectedPosts.map((resp) => {
      // eslint-disable-next-line no-underscore-dangle
      if (resp._embedded) {
        // eslint-disable-next-line no-underscore-dangle
        tags = (resp._embedded['wp:term'] || [])
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

      let excerpt = DisplaySelect.strip(resp.excerpt.rendered);

      excerpt = excerpt.length > 250 ? `${excerpt.slice(0, 250)}...` : excerpt;

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
    return results;
  }

  /**
   * Renders the PostSelector component.
   */
  render() {
    return (
      <div>
        {this.props.preview && (
          <SelectPreview
            posts={this.getPreviewPosts()}
            loading={this.state.initialLoading}
            style={this.props.style}
            prefix={this.props.prefix}
            showAuthor={this.props.showAuthor}
            showPostDate={this.props.showPostDate}
          />
        )}
        {!this.props.preview && (
          <PostSelect
            state={this.state}
            handleInputFilterChange={this.handleInputFilterChange}
            handlePostTypeChange={this.handlePostTypeChange}
            getSelectedPosts={this.getSelectedPosts}
            removePost={this.removePost}
            addPost={this.addPost}
            doPagination={this.doPagination}
            handleTaxonomyChange={this.handleTaxonomyChange}
            handleTermChange={this.handleTermChange}
            reorderPosts={this.reorderPosts}
          />
        )}
      </div>
    );
  }
}

export default DisplaySelect;
