<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package aibvc-athletes-block
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Require the API fetcher.
 */
require __DIR__ . '/../fetcher.php';

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend.
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function aibvc_athletes_block_cgb_block_assets() { // phpcs:ignore
	// Register block styles for both frontend + backend.
	wp_register_style(
		'aibvc_athletes_block-cgb-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		is_admin() ? array( 'wp-editor' ) : null, // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);

	// Register block editor script for backend.
	wp_register_script(
		'aibvc_athletes_block-cgb-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Register block frontend script for displaying tables.
	wp_register_script(
		'aibvc_athletes_block-frontend-js',
		plugins_url( '/dist/tables.build.js', dirname( __FILE__ ) )
	);

	// Register block editor styles for backend.
	wp_register_style(
		'aibvc_athletes_block-cgb-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);

	// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
	wp_localize_script(
		'aibvc_athletes_block-cgb-block-js',
		'cgbGlobal', // Array containing dynamic data for a JS Global.
		[
			'pluginDirPath' => plugin_dir_path( __DIR__ ),
			'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
			// Add more data here that you want to access from `cgbGlobal` object.
		]
	);

	/**
	 * Register Gutenberg block on server-side.
	 *
	 * Register the block on server-side to ensure that the block
	 * scripts and styles for both frontend and backend are
	 * enqueued when the editor loads.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
	 * @since 1.16.0
	 */
	register_block_type(
		'cgb/block-aibvc-athletes-block', array(
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'         => 'aibvc_athletes_block-cgb-style-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'aibvc_athletes_block-cgb-block-js',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'  => 'aibvc_athletes_block-cgb-block-editor-css',
			// Register rendering callback.
			'render_callback' => 'aibvc_render_athletes_block'
		)
	);

	wp_enqueue_script('gridjs', 'https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.production.min.js');
	wp_enqueue_style('gridjs-css', 'https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css');
}

function aibvc_render_athletes_block( $attributes ) {
	wp_enqueue_script('aibvc_athletes_block-frontend-js');
	$genere = $attributes['genere']; // genere salvato nella creazione del blocco.
	if ( ! $genere ) { $genere = "m"; }
	$athleteData = get_transient(sprintf('aibvc_athletes_%s', $genere));
	if ( ! $athleteData ) {
		$athleteData = aibvc_athletes_fetch($genere);
		// save athletes data for a day.
		set_transient(sprintf('aibvc_athletes_%s', $genere), $athleteData, DAY_IN_SECONDS / 2);
	}

	$data = [];
	$p = 1;
	foreach ( $athleteData as $athlete ) {
		$data[] = [ $p, $athlete['nome'], $athlete['cognome'], $athlete['punteggi'] ];
		$p++;
	}

	ob_start();
	?>
		<div class="wp-block-cgb-block-aibvc-athletes-block aibvc-athletes-wrapper" athletes-data="<?php echo esc_attr(json_encode($data)); ?>"></div>
	<?php
	return ob_get_clean();

}

// Hook: Block assets.
add_action( 'init', 'aibvc_athletes_block_cgb_block_assets' );
