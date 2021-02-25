/**
 * BLOCK: aibvc-athletes-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'cgb/block-aibvc-athletes-block', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'Classifica atleti AIBVC' ), // Block title.
	icon: 'universal-access', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'aibvc-athletes-block' ),
		__( 'AIBVC' ),
		__( 'atleti' ),
	],
	attributes: {
		genere: {
			type: 'string',
		}
	},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @param {Object} props Props.
	 * @returns {Mixed} JSX Component.
	 */
	edit: ( props ) => {

		if ( ! props.attributes.genere || props.attributes.genere === "" ) {
			props.setAttributes( { genere: 'm' } ); // Classifica maschile di default.
		}

		function applyChanges( e ) {
			props.setAttributes( {
				genere: e.target.value,
			} )
		}

		return (
			<div className={ props.className }>
				<h4>Classifica Atleti</h4>
				<div>
					<label for="genre-select">Genere</label>
				</div>
				<div>
					<select onChange={ applyChanges } class="aibvc-genre-select" name="aibvc-goutenberg-genre-select" value={ props.attributes.genere }>
						<option value="m">Maschile</option>
						<option value="f">Femminile</option>					
					</select>
				</div>
			</div>
		);
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @param {Object} props Props.
	 * @returns {Mixed} JSX Frontend HTML.
	 */
	save: ( props ) => {
		return null;
	},
} );
