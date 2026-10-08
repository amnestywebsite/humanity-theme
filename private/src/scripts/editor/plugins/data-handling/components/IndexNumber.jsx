import { getClassification, isValidIndexNumber } from '../utils/index-number';

const { useCallback, useState } = React;
const { TextControl } = wp.components;
const { __ } = wp.i18n;

export default function IndexNumber({ postMeta: meta, editMeta }) {
  const [isValid, setIsValid] = useState(true);

  const editIndexNumber = useCallback(
    (indexNumber) => {
      editMeta('amnesty_index_number')(indexNumber);
      setIsValid(isValidIndexNumber(indexNumber));
    },
    [editMeta],
  );

  let label = '';
  if ((meta?.amnesty_index_number ?? '')?.length > 0 && isValid) {
    label = getClassification(meta.amnesty_index_number);
  }

  return (
    <>
      <TextControl
        label={__('Index Number', 'amnesty')}
        value={meta?.amnesty_index_number ?? ''}
        placeholder={`ABC 12/3456/${new Date().getFullYear()}`}
        onChange={editIndexNumber}
      />
      {!isValid && (
        <small>{__('This input should meet AI Index Number formatting criteria', 'amnesty')}</small>
      )}
      {label && (
        <>
          <span>
            <strong>{__('Type:', 'amnesty')}</strong>&nbsp;<span>{label}</span>
          </span>
        </>
      )}
    </>
  );
}
