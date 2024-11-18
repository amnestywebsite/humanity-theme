import Control from './components/Control';
import Form from './components/Form';
import Multi from './components/Multi';
import Nav from './components/Nav';

import { useBlockProps } from '@wordpress/block-editor';
import { useState } from '@wordpress/element';

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

const edit = (props) => {
  let Component = getComponent(props.attributes);
  const [isOpen, setIsOpen] = useState(false);
  const onClick = () => setIsOpen(!isOpen);

  return (
    <div {...useBlockProps()}>
      <Component {...props} isOpen={isOpen} onClick={onClick} />
    </div>
  );
};

export default edit;
