const localiseOl = () => {
  const previewOrderedListTag = document.createElement('ol');
  const createPreviewTextTag = document.createElement('p');
  const localeDropdown = document.getElementById('ol_locale_option');

  if (!localeDropdown) {
    return;
  }

  localeDropdown.onchange = (e) => {
    previewOrderedListTag.style.listStyleType = e.target.value;
  };

  const localisationSelection = document.querySelector('.cmb2-id-ol-locale-option');

  if (localisationSelection) {
    localisationSelection.appendChild(createPreviewTextTag);
    const addPreviewText = document.createTextNode('Preview:');
    createPreviewTextTag.appendChild(addPreviewText);

    localisationSelection.appendChild(previewOrderedListTag);

    for (let i = 0; i < 5; i += 1) {
      const previewListItems = document.createElement('li');
      const previewTextContent = document.createTextNode('');
      previewListItems.appendChild(previewTextContent);
      previewOrderedListTag.appendChild(previewListItems);
    }
  }
};

localiseOl();
