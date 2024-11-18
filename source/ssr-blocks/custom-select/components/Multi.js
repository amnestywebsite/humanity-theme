import classnames from 'classnames';
import { makeHtmlId } from '../../../utils';

const Multi = ({ attributes, className, isOpen, onClick }) => {
  const blockClasses = classnames('checkboxGroup', className);
  const buttonClasses = classnames('checkboxGroup-button', { 'is-active': isOpen });
  const makeId = (value) => makeHtmlId(`${attributes.name}-${value}`);

  let active = attributes.active;
  if (!Array.isArray(active)) {
    active = [active];
  }

  return (
    <div className={blockClasses}>
      <button className={buttonClasses} type="button" aria-haspopup="listbox" aria-expanded={`${isOpen}`} disabled={attributes.disabled} onClick={onClick}>
        {attributes.label}
      </button>
      <fieldset className="checkboxGroup-list">
        {Object.keys(attributes.options).map((key) => (
          <span className="checkboxGroup-item" key={key}>
            <input id={makeId(key)} type="checkbox" name={`${attributes.name}[]`} checked={active.includes(key)} value={key} readOnly={true} />
            <label htmlFor={makeId(key)} data-value={key}>{attributes.options[key]}</label>
          </span>
        ))}
      </fieldset>
    </div>
  );
};

export default Multi;
