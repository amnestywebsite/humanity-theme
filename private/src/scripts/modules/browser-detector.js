export default function browserDetector() {
  // Firefox 1.0+
  const isFirefox = typeof InstallTrigger !== 'undefined';

  // Safari 3.0+ "[object HTMLElementConstructor]"
  const isSafari =
    window.navigator.userAgent.indexOf('Safari') > -1 &&
    window.navigator.userAgent.indexOf('Chrome') === -1;

  // Internet Explorer 6-11
  const isIE = false || !!document.documentMode;

  // Edge 20+
  const isEdge = window.navigator.userAgent.indexOf('Edge/') > -1;

  // Chrome 1 - 71
  const isChrome = !!window.chrome && (!!window.chrome.webstore || !!window.chrome.runtime);

  const { body } = document;

  if (isFirefox) {
    body.classList.add('firefox');
  }

  if (isSafari) {
    body.classList.add('safari');
  }

  if (isIE) {
    body.classList.add('ie');
  }

  if (isEdge) {
    body.classList.add('edge');
  }

  if (isChrome) {
    body.classList.add('chrome');
  }
}
