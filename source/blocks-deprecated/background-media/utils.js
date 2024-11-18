import { pick } from 'lodash';

export const getUrlV2 = (image, size) => {
  // eslint-disable-next-line camelcase
  if (!image || !image?.media_details) {
    return '';
  }

  // eslint-disable-next-line camelcase
  const { sizes } = image.media_details;

  if (!sizes) {
    return '';
  }

  // eslint-disable-next-line camelcase
  if (sizes[size] && sizes[size]?.source_url) {
    return encodeURI(sizes[size].source_url);
  }

  // eslint-disable-next-line camelcase
  if (sizes?.full?.source_url) {
    return encodeURI(sizes.full.source_url);
  }

  return '';
};

export const getDimensionsV2 = (image, size) => {
  const fallback = { height: 0, width: 0 };

  // eslint-disable-next-line camelcase
  if (!image || !image?.media_details) {
    return fallback;
  }

  // eslint-disable-next-line camelcase
  const { sizes } = image.media_details;

  if (!sizes) {
    return fallback;
  }

  // eslint-disable-next-line camelcase
  if (sizes[size]) {
    return pick(sizes[size], ['height', 'width']);
  }

  // eslint-disable-next-line camelcase
  if (sizes.full) {
    return pick(sizes.full, ['height', 'width']);
  }

  return fallback;
};

export const getEditorCssV2 = (imageObject, uniqId, focalPoint, opacity) => {
  if (!imageObject) {
    return '';
  }

  const bgopacity = parseFloat((1 - parseFloat(opacity)).toPrecision(2));
  const x = Math.round(parseFloat(focalPoint.x) * 100);
  const y = Math.round(parseFloat(focalPoint.y) * 100);
  const lg = '@media screen and (min-width:1440px)';
  let gradient = `linear-gradient(rgba(255,255,255,${bgopacity}),rgba(255,255,255,${bgopacity})),`;

  if (bgopacity === 0) {
    gradient = '';
  }

  return `
  #${uniqId}{background-position:${x}% ${y}%}
  #${uniqId}{background-image:${gradient}url("${getUrlV2(imageObject, 'lwi-block-sm@2x')}")}
  ${lg}{#${uniqId}{background-image:${gradient}url("${getUrlV2(imageObject, 'lwi-block-lg@2x')}")}}
  `.replace(/\s+/, '');
};

export const getUrl = (image, size) => {
  if (!image) {
    return '';
  }

  const obj = image.sizes[size] || image.sizes.full || { url: '' };
  return encodeURI(obj.url);
};

export const getSaveCss = ({ focalPoint, image, opacity, uniqId }) => {
  if (!image) {
    return '';
  }

  const bgopacity = parseFloat((1 - parseFloat(opacity)).toPrecision(2));
  const x = parseFloat(focalPoint.x) * 100;
  const y = parseFloat(focalPoint.y) * 100;
  const med = '@media screen and (min-width:770px)';
  const lg = '@media screen and (min-width:1440px)';
  let gradient = `linear-gradient(rgba(255,255,255,${bgopacity}),rgba(255,255,255,${bgopacity})),`;

  if (bgopacity === 0) {
    gradient = '';
  }

  return `
  #${uniqId}{background-position:${x}% ${y}%}
  #${uniqId}{background-image:${gradient}url("${getUrl(image, 'lwi-block-sm@2x')}")}
  ${med}{#${uniqId}{background-image:${gradient}url("${getUrl(image, 'lwi-block-sm@2x')}")}}
  ${lg}{#${uniqId}{background-image:${gradient}url("${getUrl(image, 'lwi-block-lg@2x')}")}}
  `.replace(/\s+/, '');
};

export const getEditorCss = ({ image, uniqId }, focalPoint, opacity) => {
  if (!image) {
    return '';
  }

  const bgopacity = parseFloat((1 - parseFloat(opacity)).toPrecision(2));
  const x = Math.round(parseFloat(focalPoint.x) * 100);
  const y = Math.round(parseFloat(focalPoint.y) * 100);
  const med = '@media screen and (min-width:770px)';
  const lg = '@media screen and (min-width:1440px)';
  let gradient = `linear-gradient(rgba(255,255,255,${bgopacity}),rgba(255,255,255,${bgopacity})),`;

  if (bgopacity === 0) {
    gradient = '';
  }

  return `
  #${uniqId}{background-position:${x}% ${y}%}
  #${uniqId}{background-image:${gradient}url("${getUrl(image, 'lwi-block-sm@2x')}")}
  ${med}{#${uniqId}{background-image:${gradient}url("${getUrl(image, 'lwi-block-sm@2x')}")}}
  ${lg}{#${uniqId}{background-image:${gradient}url("${getUrl(image, 'lwi-block-lg@2x')}")}}
  `.replace(/\s+/, '');
};

export const getDimensions = (image, size) => {
  const fallback = { height: 0, width: 0 };

  if (!image) {
    return fallback;
  }

  const obj = image.sizes[size] || image.sizes.full || fallback;
  return pick(obj, ['height', 'width']);
};

export const randId = () =>
  Math.random()
    .toString(36)
    .replace(/[^a-z]+/g, '')
    .substr(2, 10);
