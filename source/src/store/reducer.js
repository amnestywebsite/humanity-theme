const DEFAULT_STATE = {
  count: 0,
  selectedSlides: {},
  slides: {},
};

/**
 * Store reducer
 *
 * @param {Object} state  Current state
 * @param {Object} action Dispatched action
 *
 * @returns {Object} Updated state
 */
const reducer = (state = DEFAULT_STATE, action = {}) => {
  switch (action.type) {
    case 'SET_SELECTED_SLIDE':
      return {
        ...state,
        selectedSlides: {
          ...state.selectedSlides,
          [action.slider]: action.slide,
        },
      };
    case 'SET_SLIDES':
      return {
        ...state,
        slides: {
          ...state.slides,
          // transform array of slides into object, where {slide: slider} for each slide
          ...action.slides.reduce((acc, cur) => ({ ...acc, [cur]: action.slider }), {}),
        },
      };
    default:
      return state;
  }
};

export default reducer;
