import Byline from '../components/Byline.jsx';
import FeaturedImage from '../components/FeaturedImage.jsx';
import FeatureOnTermArchive from '../components/FeatureOnTermArchive.jsx';
import PublishedDate from '../components/PublishedDate.jsx';
import UpdatedDate from '../components/UpdatedDate.jsx';
import RelatedContent from '../components/RelatedContent.jsx';
import ShareButtons from '../components/ShareButtons.jsx';
import Sidebar from '../components/Sidebar.jsx';

const { Fill } = wp.components;

export default function DefaultFills() {
  return (
    <>
      <Fill name="amnesty/metadata/group/ownership">{(props) => <Byline {...props} />}</Fill>
      <Fill name="amnesty/metadata/group/editorial">
        {(props) => (
          <>
            <PublishedDate {...props} />
            <UpdatedDate {...props} />
          </>
        )}
      </Fill>
      <Fill name="amnesty/metadata/group/curation">
        {(props) => (
          <>
            <FeatureOnTermArchive {...props} />
            <div style={{ height: '50px' }} />
            <RelatedContent {...props} />
          </>
        )}
      </Fill>
      <Fill name="amnesty/metadata/group/appearance">
        {(props) => (
          <>
            <FeaturedImage {...props} />
            <div style={{ height: '50px' }} />
            <Sidebar {...props} />
          </>
        )}
      </Fill>
      <Fill name="amnesty/metadata/group/visibility">{(props) => <ShareButtons {...props} />}</Fill>
    </>
  );
}
