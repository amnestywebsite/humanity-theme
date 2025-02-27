const handleVideoLoaded = (event) => {
  event.target.closest('[class*="video"]').classList.add('is-loaded');
  event.target.removeEventListener('loadeddata', handleVideoLoaded);
};

export default () => {
  const videos = Array.from(document.getElementsByTagName('video'));
  if (!videos.length) {
    return;
  }

  videos.forEach((video) => {
    video.addEventListener('loadeddata', handleVideoLoaded);
  });
};
