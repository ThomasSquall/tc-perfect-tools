<?php
/**
 * Plugin Name: TC Perfect Tools
 * Description: Add useful tools to your Elementor and Elementor Pro plugins!
 * Plugin URI: https://thomascocchiara.it/wordpress/tc-perfect-tools/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Author: Thomas Cocchiara
 * Version: 1.0.1
 * Author URI: https://thomascocchiara.it/?utm_source=wp-plugins&utm_campaign=author-uri&utm_medium=wp-dash
 *
 * Text Domain: tc-perfect-tools
 *
 * @package TCPerfectToots
 * @category Core
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'get_plugins' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( ! function_exists( 'is_elementor_installed' ) ) {

	function is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}

if ( is_elementor_installed() ) {
	define( 'TC_PERFECT_TOOLS_PATH', plugin_dir_path(__FILE__) );
	define( 'TC_PERFECT_TOOLS_URL', plugins_url( '/', __FILE__ ) );
	define( 'TC_PERFECT_TOOLS_MODULES_URL', TC_PERFECT_TOOLS_URL . 'modules/' );

	function tc_perfect_tools_load_plugin() {
		require TC_PERFECT_TOOLS_PATH . 'plugin.php';
	}

	add_action( 'plugins_loaded', 'tc_perfect_tools_load_plugin' );
}