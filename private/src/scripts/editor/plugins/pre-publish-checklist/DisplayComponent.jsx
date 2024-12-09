const { isFunction } = lodash;
const { useDispatch, useSelect } = wp.data;
const { PluginPrePublishPanel } = wp.editPost;
const { useEffect, useState } = wp.element;
const { __ } = wp.i18n;

const requirements = {
  content: {
    validate: ({ content }) => !content.raw.test(/safelinks\.outlook\.com/),
  },
};

const passesRequirement = (req, post) => {
  if (!isFunction(requirements[req]?.validate)) {
    throw new Error(`Missing validation callback for ${req}`);
  }

  return requirements[req].validate(post);
};

const passesAllRequirements = (post) => {
  let passes = true;

  // loop and call
  passes = passes && passesRequirement('foo', post);

  return passes;
};

const usePostSavePermissions = () => {
  const POST_SAVE_LOCK_NAME = 'amnestyPrePublishChecklist';
  const { lockPostSaving, unlockPostSaving } = useDispatch('core/editor');

  return (allow = true) => {
    if (allow) {
      unlockPostSaving(POST_SAVE_LOCK_NAME);
    } else {
      lockPostSaving(POST_SAVE_LOCK_NAME);
    }
  };
};

const PrePublishRenderer = () => {
  const post = useSelect((select) => select('core/editor').getCurrentPost());
  const postType = useSelect((select) => select('core/editor').getPostType());
  const setPostSavePermission = usePostSavePermissions();
  const [passing, setPassing] = useState(passesAllRequirements(post));
  setPostSavePermission(passing);

  useEffect(() => {
    setPassing(passesAllRequirements(post));
  }, [post]);

  if (!['page', 'post'].includes(postType)) {
    return null;
  }

  return (
    <PluginPrePublishPanel className="foo" title={__('foo', 'amnesty')} initialOpen={!passing}>
      <ul>
        {Object.keys(requirements).map((key) => (
          <li key={key}>
            <span>{passesRequirement(key) ? '✔' : '✘'}</span>
            <span>{requirements[key].label || key}</span>
          </li>
        ))}
      </ul>
    </PluginPrePublishPanel>
  );
};

export default PrePublishRenderer;
