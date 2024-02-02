import { getFeaturedImage } from './utils';
import pin from './icon.jsx';

const { Placeholder, Spinner } = wp.components;
const { compose, ifCondition, withInstanceId } = wp.compose;
const { withDispatch, withSelect } = wp.data;
const { __ } = wp.i18n;
const { addQueryArgs } = wp.url;

/**
 * Render a loading area
 *
 * @returns {React.Component}
 */
const Loading = () => (
  <div>
    <Placeholder icon={pin} label={__('Related Content', 'amnesty')}>
      <Spinner />
    </Placeholder>
  </div>
);

/**
 * Render a no-posts-found error area
 *
 * @returns {React.component}
 */
const NoPosts = () => (
  <div>
    <Placeholder icon={pin} label={__('Related Content', 'amnesty')}>
      {__('No posts found.')}
    </Placeholder>
  </div>
);

/**
 * Render an item within the block
 *
 * @param {Object} param0 the props passed in
 *
 * @returns {wp.element.Component}
 */
const GridItem = ({ item, onClick }) => {
  const image = getFeaturedImage(item, 'post-half@2x');
  const style = {};

  if (image) {
    style.backgroundImage = `url('${image}')`;
  }

  return (
    <article id={`${item.type}-id-${item.id}`} className="grid-item" style={style}>
      {item.tagText && item.tagLink && (
        <span className="grid-itemMeta">
          <a href={item.tagLink} rel="noopener noreferer" onClick={onClick}>
            {item.tagText}
          </a>
        </span>
      )}
      {item.title && (
        <h3 className="grid-itemTitle">
          <a href={item.titleLink || '#'} rel="noopener noreferer" onClick={onClick}>
            {item.title}
          </a>
        </h3>
      )}
    </article>
  );
};

class DisplayComponent extends wp.element.Component {
  state = {
    posts: null,
  };

  fetchRelated = () => {
    const { post, taxonomies } = this.props;

    const path = addQueryArgs(`amnesty/v1/related/${post}`, taxonomies);

    wp.apiFetch({ path }).then((posts) => {
      this.setState({ posts });
    });
  };

  componentDidMount() {
    this.fetchRelated();
  }

  componentDidUpdate(prevProps) {
    if (lodash.isEqual(prevProps.taxonomies, this.props.taxonomies)) {
      return;
    }

    this.fetchRelated();
  }

  render() {
    const { createRedirectionPreventionNoticeCallback, instanceId } = this.props;
    const { posts } = this.state;

    const showRedirectionPreventedNotice = createRedirectionPreventionNoticeCallback(instanceId);

    if (!Array.isArray(posts)) {
      return <Loading />;
    }

    if (!posts.length) {
      return <NoPosts />;
    }

    return (
      <div>
        <div className="grid grid-5">
          {posts.map((item) => (
            <GridItem key={item.id} item={item} onClick={showRedirectionPreventedNotice} />
          ))}
        </div>
      </div>
    );
  }
}

export default compose([
  // provide means of creating/dismissing notices
  withDispatch((dispatch) => {
    const { createWarningNotice, removeNotice } = dispatch('core/notices');

    const createRedirectionPreventionNoticeCallback = (instanceId) => {
      let noticeId;

      return (event) => {
        event.preventDefault();

        // remove previous, if any
        removeNotice(instanceId);

        noticeId = `block-library/amnesty-core/related-content/redirection-prevented/${instanceId}`;

        createWarningNotice(__('Links are disabled in the editor.'), {
          id: noticeId,
          type: 'snackbar',
        });
      };
    };

    return { createRedirectionPreventionNoticeCallback };
  }),
  // provide the current post's taxonomy terms to the Component
  withSelect((select) => {
    const { getCurrentPostId, getEditedPostAttribute } = select('core/editor');
    const windowTaxonomies = window?.aiSettings?.taxonomies;
    const post = getCurrentPostId();
    const taxonomies = {};

    if (!windowTaxonomies) {
      return { post, taxonomies };
    }

    Object.keys(windowTaxonomies).forEach((tax) => {
      const taxObject = windowTaxonomies[tax];
      const slug = taxObject?.rest_base || taxObject.name;
      const terms = getEditedPostAttribute(slug);

      if (Array.isArray(terms) && terms.length) {
        taxonomies[slug] = terms;
      }
    });

    return { post, taxonomies };
  })
])(withInstanceId(DisplayComponent));
