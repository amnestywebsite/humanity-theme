import classnames from 'classnames';
import { makeHtmlId } from '../../../utils';

export default function Control({ attributes, className, isOpen, onClick }) {
  const blockClasses = classnames('checkboxGroup', 'is-control', className);
  const buttonClasses = classnames('checkboxGroup-button', { 'is-active': isOpen });
  const makeId = (value) => makeHtmlId(`${attributes.name}-${value}`);

  let buttonText;

  if (attributes.options[attributes.active]) {
    buttonText = attributes.options[attributes.active];
  } else if (attributes.showLabel && attributes.label) {
    buttonText = attributes.label;
  }

  if (!buttonText) {
    buttonText = attributes.options[Object.keys(attributes.options)[0]];
  }

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
        {buttonText}
      </button>
      <fieldset className="checkboxGroup-list">
        {Object.keys(attributes.options).map((key) => (
          <span className="checkboxGroup-item" key={key}>
            <input
              id={makeId(key)}
              className="screen-reader-text"
              type="radio"
              name={attributes.name}
              checked={key === attributes.active}
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
