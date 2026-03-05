import ActionButton from './fills/ActionButton.jsx';
import DefaultFills from './fills/DefaultFills.jsx';

const { Button, Fill, Modal, Slot } = wp.components;
const { useEntityProp } = wp.coreData;
const { useSelect } = wp.data;
const { store: editorStore } = wp.editor;
const { createRef, useCallback, useEffect, useRef, useState } = wp.element;
const { applyFilters } = wp.hooks;
const { __, sprintf } = wp.i18n;

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const SlotFillNamespace = 'amnesty/metadata/group';

const defaultGroups = [
  {
    label: __('Header', 'amnesty'),
    value: 'header',
  },
  {
    label: __('Features', 'amnesty'),
    value: 'features',
  },
  {
    label: __('Curation', 'amnesty'),
    value: 'curation',
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
  const scrollRefs = useRef({});
  const contentRef = useRef();

  const groups = applyFilters('amnesty.metadata.groups', defaultGroups);
  groups.forEach((group) => {
    scrollRefs.current[group.value] = createRef();
  });

  useEffect(() => {
    Object.keys(scrollRefs.current).forEach((group) => {
      scrollRefs.current[group].current?.classList.remove('is-active-group');
    });

    scrollRefs.current[activeGroup].current?.classList.add('is-active-group');
  }, [activeGroup, scrollRefs]);

  useEffect(() => {
    if (!modalOpen) {
      return () => null;
    }

    const scrollContainer = contentRef.current;
    if (!scrollContainer) {
      return () => null;
    }

    const observer = new IntersectionObserver(
      (entries) => {
        const closest = {
          target: null,
          distance: null,
        };

        entries.forEach((entry) => {
          if (!entry.isIntersecting) {
            return;
          }

          const { rootBounds } = entry;
          if (!rootBounds) {
            return;
          }

          const rootMidY = rootBounds.top + rootBounds.height / 2;
          const targetDims = entry.boundingClientRect;
          const targetIntersectPoint = targetDims.top;
          const offset = Math.abs(targetIntersectPoint - rootMidY);

          if (!closest.target || offset < closest.distance) {
            closest.target = entry.target;
            closest.distance = offset;
          }
        });

        if (closest.target) {
          setActiveGroup(closest.target.dataset.group);
        }
      },
      {
        root: scrollContainer,
        rootMargin: '-50% 0px',
        threshold: 0,
      },
    );

    Object.keys(scrollRefs.current).forEach((group) => {
      observer.observe(scrollRefs.current[group].current);
    });

    return () => {
      observer.disconnect();
    };
  }, [modalOpen]);

  if (!postId || !postType || !['page', 'post'].includes(postType)) {
    return null;
  }

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

  const onButtonClick = (group) => (event) => {
    event.preventDefault();
    setActiveGroup(group);
    scrollRefs.current[group].current?.scrollIntoView({
      behaviour: prefersReducedMotion ? 'instant' : 'smooth',
    });
  };

  return (
    <>
      <ActionButton isPressed={modalOpen} onClick={toggleModal} />

      <Modal
        title={title}
        className="amnesty-data-handling-modal"
        size="fill"
        onRequestClose={toggleModal}
      >
        <nav className="amnesty-data-handling-modal-navigation">
          <ul>
            {groups.map(({ value, label }) => (
              <li key={value}>
                <Button
                  variant="link"
                  isPressed={value === activeGroup}
                  onClick={onButtonClick(value)}
                  href={`#group-${value}`}
                >
                  {label}
                </Button>
              </li>
            ))}
          </ul>
        </nav>

        <div className="amnesty-data-handling-modal-content" ref={contentRef}>
          {groups.map(({ value, label }) => (
            <div
              key={value}
              id={`group-${value}`}
              ref={scrollRefs.current[value]}
              data-group={value}
              className="amnesty-data-handling-modal-group"
            >
              <h2 className="amnesty-data-handling-modal-group-title">{label}</h2>
              <Slot name={`${SlotFillNamespace}/${value}`} fillProps={fillProps} />
            </div>
          ))}
        </div>

        <div className="amnesty-data-handling-modal-actions">
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
