import './style.scss';

import edit from './edit';
import metadata from './block.json';

import PostsWrapper from '../post-list/PostsWrapper';

const PetitionEditCompose = PostsWrapper(edit, {
  style: 'petition',
});

// const petitionSlug = window?.postTypes?.petition;

import { registerBlockType } from '@wordpress/blocks';

registerBlockType(metadata, {
  ...metadata,
  edit: PetitionEditCompose,
  save: () => null,
});
