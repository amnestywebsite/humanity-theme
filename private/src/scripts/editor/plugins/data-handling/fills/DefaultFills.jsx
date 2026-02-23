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
      <Fill name="amnesty/metadata/group/ownership">
        <Byline />
      </Fill>
      <Fill name="amnesty/metadata/group/editorial">
        <PublishedDate />
        <UpdatedDate />
      </Fill>
      <Fill name="amnesty/metadata/group/curation">
        <FeatureOnTermArchive />
        <div style={{ height: '50px' }} />
        <RelatedContent />
      </Fill>
      <Fill name="amnesty/metadata/group/appearance">
        <FeaturedImage />
        <div style={{ height: '50px' }} />
        <Sidebar />
      </Fill>
      <Fill name="amnesty/metadata/group/visibility">
        <ShareButtons />
      </Fill>
    </>
  );
}
