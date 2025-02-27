import classnames from 'classnames';
import { makeHtmlId } from '../../../utils';

export default function Form({ attributes, className, isOpen, onClick }) {
  const blockClasses = classnames('checkboxGroup', 'is-form', className);
  const buttonClasses = classnames('checkboxGroup-button', { 'is-active': isOpen });
  const makeId = (value) => makeHtmlId(`${attributes.name}-${value}`);

  return (
    <div className={blockClasses}>
      {attributes.showLabel && <span className="checkboxGroup-label">{attributes.label}</span>}
      <button
        className={buttonClasses}
        type="button"
        aria-haspopup="listbox"
        aria-expanded={`${isOpen}`}
        disabled={attributes.disabled}
        onClick={onClick}
      >
        {attributes.options[attributes.active] ||
          attributes.options[Object.keys(attributes.options)[0]] ||
          attributes.label}
      </button>
      <fieldset className="checkboxGroup-list">
        {Object.keys(attributes.options).map((key) => (
          <span className="checkboxGroup-item" key={key}>
            <input
              id={makeId(key)}
              type="radio"
              name={attributes.name}
              value={key}
              readOnly={true}
            />
            <label htmlFor={makeId(key)} data-value={key}>
              {attributes.options[key]}
            </label>
          </span>
        ))}
      </fieldset>
    </div>
  );
}
