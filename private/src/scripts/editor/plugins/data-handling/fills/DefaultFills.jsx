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
      <Fill name="amnesty/metadata/group/post-options">
        {(props) => (
          <>
            <FeaturedImage {...props} />
            <div className="amnesty-data-handling-spacer" />
            <RelatedContent {...props} />
            <div className="amnesty-data-handling-spacer" />
            <FeatureOnTermArchive {...props} />
          </>
        )}
      </Fill>
      <Fill name="amnesty/metadata/group/sidebar">
        {(props) => (
          <>
            <Sidebar {...props} />
          </>
        )}
      </Fill>
      <Fill name="amnesty/metadata/group/metadata">
        {(props) => (
          <>
            <PublishedDate {...props} />
            <UpdatedDate {...props} />
            <div className="amnesty-data-handling-spacer" />
            <ShareButtons {...props} />
            <Byline {...props} />
          </>
        )}
      </Fill>
    </>
  );
}
