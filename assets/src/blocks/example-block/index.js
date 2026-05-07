import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import save from './save';
import './editor.scss';
import './style.scss';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	save,
} );
