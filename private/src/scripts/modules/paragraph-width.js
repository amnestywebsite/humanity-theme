const restrictParagraphWidth = () => {
  // Get all paragraphs on a post
  const paragraphs = document.querySelectorAll('p');
  // For each paragraph, check if its width is greater than 1000px
  paragraphs.forEach((p) => {
    if (p.clientWidth > 1000) {
      // If so, restrict its width to 860px
      p.style.maxWidth = '860px';
      // Center the paragraph
      p.style.marginLeft = 'auto';
      p.style.marginRight = 'auto';
    }
  });
};

export default restrictParagraphWidth;
