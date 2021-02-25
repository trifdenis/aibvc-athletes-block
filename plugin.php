<?php
/**
 * Plugin Name: aibvc-athletes-block
 * Plugin URI: https://www.aibvc.it
 * Description: Blocco Goutenberg per la visualizzazione della classifica atleti AIBVC.
 * Author: 5G
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package aibvc-athletes-block
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
