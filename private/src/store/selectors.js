/**
 * Get the selected slide for the specified slider
 *
 * @param {Object} state - Current state
 *
 * @returns {String} The client id of the selected slide
 */
export function getSelectedSlide(state, slider) {
  return state.selectedSlides[slider] ?? null;
}

export function getSlider(state, slide) {
  return state.slides[slide] ?? null;
}
