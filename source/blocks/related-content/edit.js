import { getFeaturedImage } from './utils';
import pin from './icon.jsx';

import { Placeholder, Spinner } from '@wordpress/components';
import { useInstanceId } from '@wordpress/compose';
import { useDispatch, useSelect } from '@wordpress/data';
import { useEffect, useRef } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { addQueryArgs } from '@wordpress/url';

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

const edit = () => {
  const createRedirectionPreventionNoticeCallback = useDispatch((dispatch) => {
    const { createWarningNotice, removeNotice } = dispatch('core/notices');

    const callback = (instanceId) => {
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

    return callback;
  });

  const instanceId = useInstanceId(edit);
  const showRedirectionPreventedNotice = createRedirectionPreventionNoticeCallback(instanceId);

  const { posts, taxonomies } = useSelect((select) => {
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
  });

  const [related, setRelated] = useState(null);

  const fetchRelated = () => {
    const path = addQueryArgs(`amnesty/v1/related/${post}`, taxonomies);
    wp.apiFetch({ path }).then((posts) => setRelated(posts));
  };

  const mounted = useRef();
  useEffect(() => {
    if (!mounted?.current) {
      mounted.current = true;
      fetchRelated();
    }
  }, []);

  useEffect(fetchRelated, [taxonomies]);

  if (!Array.isArray(related)) {
    return <Loading />;
  }

  if (!related.length) {
    return <NoPosts />;
  }

  return (
    <div>
      <div className="grid grid-5">
        {related.map((item) => (
          <GridItem key={item.id} item={item} onClick={showRedirectionPreventedNotice} />
        ))}
      </div>
    </div>
  );
};

export default edit;
