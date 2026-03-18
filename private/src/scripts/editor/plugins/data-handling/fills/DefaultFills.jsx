import Byline from '../components/Byline.jsx';
import Excerpt from '../components/Excerpt.jsx';
import FeatureOnTermArchive from '../components/FeatureOnTermArchive.jsx';
import FeaturedImage from '../components/FeaturedImage.jsx';
import PublishedDate from '../components/PublishedDate.jsx';
import RelatedContent from '../components/RelatedContent.jsx';
import ShareButtons from '../components/ShareButtons.jsx';
import Sidebar from '../components/Sidebar.jsx';
import Spacer from '../components/Spacer.jsx';
import Taxonomies from '../components/Taxonomies.jsx';
import UpdatedDate from '../components/UpdatedDate.jsx';

const { Fill } = wp.components;
const { PostFeaturedImage, PostFeaturedImageCheck } = wp.editor;

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
            <PostFeaturedImageCheck>
              <PostFeaturedImage />
            </PostFeaturedImageCheck>
            <Spacer height="20px" />
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
            <Excerpt {...props} />
            <Taxonomies {...props} />
            <FeatureOnTermArchive {...props} />
          </>
        )}
      </Fill>
    </>
  );
}
