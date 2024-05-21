const blockAttributes = {
  sliderId: {
    type: 'string',
  },
  slides: {
    type: 'array',
    default: [],
  },
  hasArrows: {
    type: 'boolean',
    default: true,
  },
  showTabs: {
    type: 'boolean',
    default: true,
  },
  hasContent: {
    type: 'boolean',
    default: true,
  },
  sliderTitle: {
    type: 'string',
    default: '',
  },
  timelineCaptionStyle: {
    type: 'string',
    default: 'dark',
  },
};

export default blockAttributes;
