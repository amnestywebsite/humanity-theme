import Byline from '../components/Byline.jsx';
import Excerpt from '../components/Excerpt.jsx';
import FeatureOnTermArchive from '../components/FeatureOnTermArchive.jsx';
import FeaturedImageMeta from '../components/FeaturedImageMeta.jsx';
import PublishedDate from '../components/PublishedDate.jsx';
import RelatedContent from '../components/RelatedContent.jsx';
import ShareButtons from '../components/ShareButtons.jsx';
import Spacer from '../components/Spacer.jsx';
import UpdatedDate from '../components/UpdatedDate.jsx';

const { Fill } = wp.components;
const { useSelect } = wp.data;
const { PostFeaturedImage, PostFeaturedImageCheck, store: editorStore } = wp.editor;

export default function DefaultFills() {
  const postType = useSelect((selector) => selector(editorStore).getCurrentPostType(), []);

  if (postType !== 'post') {
    return null;
  }

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
            <FeaturedImageMeta {...props} />
            <Spacer />
            <RelatedContent {...props} />
          </>
        )}
      </Fill>
      <Fill name="amnesty/metadata/group/curation">
        {(props) => (
          <>
            <Excerpt {...props} />
            <FeatureOnTermArchive {...props} />
          </>
        )}
      </Fill>
    </>
  );
}
