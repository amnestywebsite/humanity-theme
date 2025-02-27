import { useBlockProps } from '@wordpress/block-editor';
import { useState } from '@wordpress/element';

import Control from './components/Control.jsx';
import Form from './components/Form.jsx';
import Multi from './components/Multi.jsx';
import Nav from './components/Nav.jsx';

const getComponent = (attributes) => {
  if (attributes.isForm) {
    return Form;
  }

  if (attributes.isNav) {
    return Nav;
  }

  if (attributes.multiple) {
    return Multi;
  }

  return Control;
};

export default function Edit(props) {
  const Component = getComponent(props.attributes);
  const [isOpen, setIsOpen] = useState(false);
  const onClick = () => setIsOpen(!isOpen);

  return (
    <div {...useBlockProps()}>
      <Component {...props} isOpen={isOpen} onClick={onClick} />
    </div>
  );
}
