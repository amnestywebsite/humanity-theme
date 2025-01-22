import { useBlockProps } from '@wordpress/block-editor';
import { useInstanceId } from '@wordpress/compose';
import { useEntityRecord } from '@wordpress/core-data';
import { useDispatch } from '@wordpress/data';
import { useEffect, useMemo, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import PinIcon from './components/Icon';
import GridItem from './components/GridItem.jsx';
import Loading from './components/Loading.jsx';
import NoPosts from './components/NoPosts.jsx';
import { fetchRelated } from './utils';

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

export default function Edit({ context }) {
  const instanceId = useInstanceId(Edit);
  const showRedirectionPreventedNotice = useRedirectionPreventionNotices(instanceId);
  const [related, setRelated] = useState(null);

  const { postId, postType } = context;
  const { editedRecord: post } = useEntityRecord('postType', postType, postId);

  const taxonomies = useMemo(() => {
    Object.keys(window?.aiSettings?.taxonomies ?? []).forEach((tax) => {
      taxonomies[tax] = post[tax];
    });
  }, [post]);

  useEffect(() => {
    fetchRelated(postId, taxonomies, setRelated);
  }, [postId, taxonomies]);

  const blockProps = useBlockProps();

  if (!Array.isArray(related)) {
    return (
      <div {...blockProps}>
        <Loading icon={PinIcon} />
      </div>
    );
  }

  if (!related.length) {
    return (
      <div {...blockProps}>
        <NoPosts icon={PinIcon} />
      </div>
    );
  }

  return (
    <div {...blockProps}>
      <div className="grid grid-5">
        {related.map((item) => (
          <GridItem key={item.id} item={item} onClick={showRedirectionPreventedNotice} />
        ))}
      </div>
    </div>
  );
}
