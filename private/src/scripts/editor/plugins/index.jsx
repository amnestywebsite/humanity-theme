import DataHandling from './data-handling/index.jsx';
import PopInSettings from './pop-in/index.jsx';

const { registerPlugin } = wp.plugins;

registerPlugin('amnesty-data-handling', {
  render: DataHandling,
});

registerPlugin('amnesty-core-pop-in', {
  render: PopInSettings,
});
