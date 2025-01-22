export function setSelectedSlide(slider, slide) {
  return {
    type: 'SET_SELECTED_SLIDE',
    slider,
    slide,
  };
}

export function setSlides(slider, slides) {
  return {
    type: 'SET_SLIDES',
    slider,
    slides,
  };
}
