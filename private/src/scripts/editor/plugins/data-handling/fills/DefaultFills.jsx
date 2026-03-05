import Byline from '../components/Byline.jsx';
import FeaturedImage from '../components/FeaturedImage.jsx';
import FeatureOnTermArchive from '../components/FeatureOnTermArchive.jsx';
import PublishedDate from '../components/PublishedDate.jsx';
import UpdatedDate from '../components/UpdatedDate.jsx';
import RelatedContent from '../components/RelatedContent.jsx';
import ShareButtons from '../components/ShareButtons.jsx';
import Sidebar from '../components/Sidebar.jsx';
import Spacer from '../components/Spacer.jsx';

const { Fill } = wp.components;

export default function DefaultFills() {
  return (
    <>
      <Fill name="amnesty/metadata/group/header">
        {(props) => (
          <>
            <Byline {...props} />
            <Spacer />
            <PublishedDate {...props} />
            <UpdatedDate {...props} />
            <Spacer />
            <ShareButtons {...props} />
          </>
        )}
      </Fill>
      <Fill name="amnesty/metadata/group/features">
        {(props) => (
          <>
            <FeaturedImage {...props} />
            <Spacer />
            <Sidebar {...props} />
            <Spacer />
            <RelatedContent {...props} />
          </>
        )}
      </Fill>
      <Fill name="amnesty/metadata/group/curation">
        {(props) => (
          <>
            <FeatureOnTermArchive {...props} />
          </>
        )}
      </Fill>
    </>
  );
}
