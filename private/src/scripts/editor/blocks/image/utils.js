/* eslint-disable import/prefer-default-export */

export const uniqueId = () => {
  const valid = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  const count = valid.length;
  let length = 5;
  let id = '';

  // eslint-disable-next-line no-plusplus
  while (length-- > 0) {
    id += valid.charAt(Math.floor(Math.random() * count));
  }

  return id;
};
