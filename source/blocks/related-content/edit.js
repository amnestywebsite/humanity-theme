import pin from './components/Icon.js';

import GridItem from './components/GridItem';
import Loading from './components/Loading';
import NoPosts from './components/NoPosts';

import { fetchRelated } from './utils.js';

import { useInstanceId } from '@wordpress/compose';
import { useEntityRecord } from '@wordpress/core-data';
import { useDispatch } from '@wordpress/data';
import { useCallback, useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

const useRedirectionPreventionNotices = (instanceId) => {
  const { createWarningNotice, removeNotice } = useDispatch('core/notices');

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

const edit = ({ context }) => {
  const instanceId = useInstanceId(edit);
  const showRedirectionPreventedNotice = useRedirectionPreventionNotices(instanceId);
  const [related, setRelated] = useState(null);
  const mounted = useRef();

  const { postId, postType } = context;
  const { editedRecord: post } = useEntityRecord('postType', postType, postId);

  const taxonomies = {};
  if (window?.aiSettings?.taxonomies) {
    Object.keys(window.aiSettings.taxonomies).forEach((tax) => {
      taxonomies[tax] = post[tax];
    });
  }

  useEffect(() => {
    if (!mounted?.current) {
      mounted.current = true;
      fetchRelated(postId, taxonomies, setRelated);
    }
  }, []);

  if (!Array.isArray(related)) {
    return <Loading icon={pin} />;
  }

  if (!related.length) {
    return <NoPosts icon={pin} />;
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
