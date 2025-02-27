const settingsGeneral = () => {
  const wplang = document.getElementById('WPLANG')?.parentElement?.parentElement;
  const language = document.getElementById('amnesty-language-name')?.parentElement?.parentElement;

  if (!wplang || !language) {
    return;
  }

  wplang.insertAdjacentElement('beforeBegin', language);
};

document.addEventListener('DOMContentLoaded', settingsGeneral);
