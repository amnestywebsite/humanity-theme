/**
 * Render a spacer component
 *
 * @param {object} param0 props passed to the component
 * @param {string} param0.height the spacer height
 *
 * @return {JSX.Element}
 */
export default function Spacer({ height = 'var(--wp--preset--spacing--single)' }) {
  return <div className="amnesty-data-handling-spacer" style={{ height }} />;
}
