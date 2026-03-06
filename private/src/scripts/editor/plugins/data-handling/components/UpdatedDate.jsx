const { Button, DateTimePicker, Dropdown, ToggleControl } = wp.components;
const { __ } = wp.i18n;

/**
 * Render the component for managing an entity's updated date visibility
 *
 * @param {object} param0 props passed to the component
 * @param {object} param0.postMeta the entity's meta object
 * @param {function} param0.editMeta callback for manipulating entity meta
 *
 * @return {JSX.Element}
 */
export default function UpdatedDate({ postMeta: meta, editMeta }) {
  return (
    <>
      <ToggleControl
        label={__('Show updated date', 'amnesty')}
        help={__('Show the date the entity was updated', 'amnesty')}
        checked={meta?.show_updated_date}
        onChange={(value) => editMeta('show_updated_date')(value)}
      />
      {meta?.show_updated_date && (
        <div style={{ marginInlineStart: 'var(--wp--preset--spacing--double)' }}>
          <strong>{__('Updated at', 'amnesty')}</strong>
          <Dropdown
            renderToggle={({ isOpen, onToggle }) => (
              <Button onClick={onToggle} aria-expanded={isOpen}>
                {meta?.amnesty_updated || __('Choose Date', 'amnesty')}
              </Button>
            )}
            renderContent={() => (
              <DateTimePicker
                label={__('Updated Date', 'amnesty')}
                currentDate={meta?.amnesty_updated}
                onChange={(value) => editMeta('amnesty_updated')(value.replace('T', ' '))}
              />
            )}
          />
          <Button isLink onClick={() => editMeta('amnesty_updated')('')}>
            {__('Clear', 'amnesty')}
          </Button>
        </div>
      )}
    </>
  );
}
