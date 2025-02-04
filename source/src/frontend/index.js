import './index.scss';
import 'intersection-observer';

import Expose from './modules/Expose';
import Overlays from './modules/overlays';
import fluidText from './modules/fluid-text';
import languageSelector from './modules/language-selector';
import header from './modules/header';
import mobileMenu from './modules/navigation';
import subcatDrops from './modules/subcategory-dropdown';
import latestFilters from './modules/latest-filters';
import searchFilters from './modules/search-filters';
import filterPosts from './modules/filter-posts';
import loadVideos from './modules/video-loaded';
import categorySlider from './modules/category-slider';
import tabbedNav from './modules/tabbed-nav';
import addFlickityToTabs from './modules/tabbed-content-flickity';

const App = () => {
  languageSelector();
  header();
  mobileMenu();
  Overlays();
  subcatDrops();
  latestFilters();
  searchFilters();
  filterPosts();
  categorySlider();
  tabbedNav();
  loadVideos();
  addFlickityToTabs();

  fluidText(document.getElementsByClassName('article-shareTitle'), 0.9);

  return {
    using: Expose(),
  };
};

/**
 * Export to `window.App.default()`.
 */
export default App;
