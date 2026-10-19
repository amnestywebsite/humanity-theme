import Sidebar from '../components/Sidebar.jsx';
import Taxonomies from '../components/Taxonomies.jsx';

const { Fill } = wp.components;

export default function DefaultFills() {
  return (
    <>
      <Fill name="amnesty/metadata/group/features">
        {(props) => (
          <>
            <Sidebar {...props} />
          </>
        )}
      </Fill>
      <Fill name="amnesty/metadata/group/curation">
        {(props) => (
          <>
            <Taxonomies {...props} />
          </>
        )}
      </Fill>
    </>
  );
}
