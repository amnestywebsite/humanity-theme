import ActionButton from './fills/ActionButton.jsx';
import DefaultFills from './fills/DefaultFills.jsx';

const {
  Button,
  Fill,
  Modal,
  Slot,
  // eslint-disable-next-line @wordpress/no-unsafe-wp-apis
  __experimentalToggleGroupControl: ToggleGroupControl,
  // eslint-disable-next-line @wordpress/no-unsafe-wp-apis
  __experimentalToggleGroupControlOption: ToggleGroupControlOption,
} = wp.components;
const { useSelect } = wp.data;
const { store: editorStore } = wp.editor;
const { useState } = wp.element;
const { applyFilters } = wp.hooks;
const { __ } = wp.i18n;

const defaultGroups = [
  {
    label: __('Ownership', 'amnesty'),
    value: 'ownership',
  },
  {
    label: __('Editorial', 'amnesty'),
    value: 'editorial',
  },
  {
    label: __('Curation', 'amnesty'),
    value: 'curation',
  },
  {
    label: __('Appearance', 'amnesty'),
    value: 'appearance',
  },
  {
    label: __('Visibility', 'amnesty'),
    value: 'visibility',
  },
];

export default function DataHandling() {
  const postType = useSelect((select) => select(editorStore).getCurrentPostType(), []);
  const postId = useSelect((select) => select(editorStore).getCurrentPostId(), []);

  const [modalOpen, setModalOpen] = useState(false);
  const toggleModal = () => setModalOpen(!modalOpen);

  const [activeGroup, setActiveGroup] = useState(defaultGroups[0].value);

  if (!postId || !postType || !['page', 'post'].includes(postType)) {
    return null;
  }

  const groups = applyFilters('amnesty/metadata/groups', defaultGroups);

  return (
    <>
      <ActionButton isPressed={modalOpen} onClick={toggleModal} />
      {modalOpen && (
        <Modal title={__('Metadata', 'amnesty')} size="large" onRequestClose={toggleModal}>
          <ToggleGroupControl
            isBlock
            isDeselectable={false}
            label={__('Options', 'amnesty')}
            hideLabelFromVision
            value={activeGroup}
            onChange={(value) => setActiveGroup(value)}
            style={{ marginBlockEnd: '50px' }}
          >
            {groups.map(({ value, label }) => (
              <ToggleGroupControlOption key={value} value={value} label={label} />
            ))}
          </ToggleGroupControl>
          <Slot name={`amnesty/metadata/group/${activeGroup}`} />
          <hr style={{ marginBlockStart: '50px' }} />
          <Button variant="primary" onClick={toggleModal}>
            {__('Confirm', 'amnesty')}
          </Button>
        </Modal>
      )}
      {groups.map(({ value }) => (
        <Fill key={value} name={`amnesty/metadata/group/${value}`} />
      ))}
      <DefaultFills />
    </>
  );
}
