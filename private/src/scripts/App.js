import '../styles/app.scss';
import './polyfills';

import Expose from './modules/Expose';
import Overlays from './modules/overlays';
import popIn from './modules/pop-in';
import fluidText from './modules/fluid-text';
import languageSelector from './modules/language-selector';
import header from './modules/header';
import mobileMenu from './modules/navigation';
import subcatDrops from './modules/subcategory-dropdown';
import latestFilters from './modules/latest-filters';
import searchFilters from './modules/search-filters';
import filterPosts from './modules/filter-posts';
import loadVideos from './modules/video-loaded';
import fluidIframe from './modules/fluid-iframe';
import categorySlider from './modules/category-slider';
// import sliderBlock from './modules/slider-block';
import counters from './modules/counter';
import tabbedNav from './modules/tabbed-nav';
import browserDetector from './modules/browser-detector';
import addFlickityToTabs from './modules/tabbed-content-flickity';

const App = () => {
  browserDetector();
  popIn();
  languageSelector();
  header();
  mobileMenu();
  Overlays();
  subcatDrops();
  latestFilters();
  searchFilters();
  filterPosts();
  fluidIframe();
  categorySlider();
  // sliderBlock();
  counters();
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
