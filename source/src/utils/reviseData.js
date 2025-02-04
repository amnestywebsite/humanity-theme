/**
 * Takes the previous meta and the new meta,
 * it filters out the unchanged values to prevent an rest api error
 * @param oldData
 * @param newData
 * @returns {{}}
 */
const reviseData = (oldData, newData) =>
  Object.keys(newData).reduce((prev, key) => {
    if (oldData[key] === newData[key]) {
      return prev;
    }

    return {
      ...prev,
      [key]: newData[key],
    };
  }, {});

export default reviseData;
