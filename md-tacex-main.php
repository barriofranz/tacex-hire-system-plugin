<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://morningstardigital.com.au/
 * @since             1.0.0
 * @package           Md_Tacex_Main
 *
 * @wordpress-plugin
 * Plugin Name:       Tacex main
 * Plugin URI:        https://morningstardigital.com.au/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Morningstar Digital
 * Author URI:        https://morningstardigital.com.au/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       md-tacex-main
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MD_TACEX_MAIN_VERSION', '1.0.0' );
define('THSP_UPLOAD_URL', wp_upload_dir()['baseurl'] . '/thsp_uploads' . '/');

function thsp_create_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'thsp_infos';

	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {

		$wpdb_collate = $wpdb->collate;
		$sql =
			"CREATE TABLE $table_name (
			id mediumint(8) NOT NULL AUTO_INCREMENT ,
			title varchar(255) NULL,
			descriptions TEXT NULL,
			order_num int NOT NULL,
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
			)
			COLLATE $wpdb_collate";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta( $sql );
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-md-tacex-main-activator.php
 */
function activate_md_tacex_main() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-md-tacex-main-activator.php';
	Md_Tacex_Main_Activator::activate();

	thsp_create_table();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-md-tacex-main-deactivator.php
 */
function deactivate_md_tacex_main() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-md-tacex-main-deactivator.php';
	Md_Tacex_Main_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_md_tacex_main' );
register_deactivation_hook( __FILE__, 'deactivate_md_tacex_main' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-md-tacex-main.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_md_tacex_main() {

	$plugin = new Md_Tacex_Main();
	$plugin->run();

}
run_md_tacex_main();



// [home2_sc foo="foo-value"]
function home2_sc( $atts ) {
	$a = shortcode_atts( array(
		'foo' => 'something',
		'bar' => 'something else',
	), $atts );

	return "foo = {$a['foo']}";
}
add_shortcode( 'home2_sc', 'home2_sc' );



add_action( 'init', 'tacex_index_endpoints' );
function tacex_index_endpoints() { // set tacex-index endpoint on front
	add_rewrite_endpoint( THSP_ENDPOINT, EP_ROOT | EP_PAGES );
}

add_filter( 'query_vars', 'tacex_index_query_vars', 0 );
function tacex_index_query_vars( $vars ) { // set tacex-index endpoint on front too?
	$vars[] = THSP_ENDPOINT;

	return $vars;
}

add_action( 'init', 'tacex_index_flush_rewrite_rules' ); // run init once
function tacex_index_flush_rewrite_rules() { // add tacex-index endpoint once
	add_rewrite_endpoint( THSP_ENDPOINT, EP_ROOT | EP_PAGES );
	flush_rewrite_rules();
}


add_action("wp_head", "tacex_head");
function tacex_head() {
	include plugin_dir_path( __FILE__ ) . 'public/templates/tacex_header.php';
}


// add_filter( 'template_include', 'tacex_template' );
//
// function tacex_template( $template )
// {
//
//     $template = plugin_dir_path( __FILE__ ) . 'public/templates/front-page.php';
//
//     return $template;
// }


add_action("wp_footer", "tacex_foot");
function tacex_foot() {
	include plugin_dir_path( __FILE__ ) . 'public/templates/tacex_footer.php';
}



add_action('wp_enqueue_scripts','tacex_scripts');
function tacex_scripts() {
    wp_enqueue_script( 'tacex-js-combotree', plugins_url( '/public/js/comboTreePlugin.js', __FILE__ ));
}
