import ActionButton from './fills/ActionButton.jsx';
import DefaultFills from './fills/DefaultFills.jsx';
import ModalNavigation from './components/ModalNavigation.jsx';

const { Button, Fill, Modal, Slot } = wp.components;
const { useEntityProp } = wp.coreData;
const { useSelect } = wp.data;
const { store: editorStore } = wp.editor;
const { useCallback, useState } = wp.element;
const { applyFilters } = wp.hooks;
const { __, sprintf } = wp.i18n;

const SlotFillNamespace = 'amnesty/metadata/group';

const defaultGroups = [
  {
    label: __('Post Options', 'amnesty'),
    value: 'post-options',
  },
  {
    label: __('Sidebar', 'amnesty'),
    value: 'sidebar',
  },
  {
    label: __('Metadata', 'amnesty'),
    value: 'metadata',
  },
];

function useEditPostMeta(meta, setMeta) {
  return useCallback(
    (key) => (value) => {
      setMeta({ ...meta, [key]: value });
    },
    [meta, setMeta],
  );
}

export default function DataHandling() {
  const postType = useSelect((select) => select(editorStore).getCurrentPostType(), []);
  const postId = useSelect((select) => select(editorStore).getCurrentPostId(), []);
  const [meta, setMeta] = useEntityProp('postType', postType, 'meta', postId);
  const editMeta = useEditPostMeta(meta, setMeta);

  const [modalOpen, setModalOpen] = useState(false);
  const toggleModal = () => setModalOpen(!modalOpen);

  const [activeGroup, setActiveGroup] = useState(defaultGroups[0].value);

  if (!postId || !postType || !['page', 'post'].includes(postType)) {
    return null;
  }

  const groups = applyFilters('amnesty.metadata.groups', defaultGroups);

  const fillProps = {
    postId,
    postType,
    postMeta: meta,
    editMeta,
    modalOpen,
    setModalOpen,
  };

  if (!modalOpen) {
    return <ActionButton isPressed={modalOpen} onClick={toggleModal} />;
  }

  // translators: %s: the post type (e.g. "post", "page")
  const title = sprintf(__('%s configuration', 'amnesty'), postType)
    .toLowerCase()
    .replace(/\b[a-z]/g, (c) => c.toUpperCase());

  return (
    <>
      <ActionButton isPressed={modalOpen} onClick={toggleModal} />

      <Modal
        title={title}
        className="amnesty-data-handling-modal"
        size="fill"
        onRequestClose={toggleModal}
      >
        <ModalNavigation items={groups} current={activeGroup} onChange={setActiveGroup} />

        <div>
          <Slot name={`${SlotFillNamespace}/${activeGroup}`} fillProps={fillProps} />
        </div>

        <div style={{ textAlign: 'end' }}>
          <hr />
          <Button variant="primary" onClick={toggleModal}>
            {__('Confirm', 'amnesty')}
          </Button>
        </div>
      </Modal>

      {groups.map(({ value }) => (
        <Fill key={value} name={`${SlotFillNamespace}/${value}`} />
      ))}

      <DefaultFills />
    </>
  );
}
