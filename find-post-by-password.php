<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://lukasbesch.com
 * @since             1.0.0
 * @package           Find_Post_By_Password
 *
 * @wordpress-plugin
 * Plugin Name:       Find post by password
 * Plugin URI:        https://lukasbesch.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Lukas Besch
 * Author URI:        https://lukasbesch.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       find-post-by-password
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-find-post-by-password-activator.php
 */
function activate_find_post_by_password() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-find-post-by-password-activator.php';
	Find_Post_By_Password_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-find-post-by-password-deactivator.php
 */
function deactivate_find_post_by_password() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-find-post-by-password-deactivator.php';
	Find_Post_By_Password_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_find_post_by_password' );
register_deactivation_hook( __FILE__, 'deactivate_find_post_by_password' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-find-post-by-password.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_find_post_by_password() {

	$plugin = new Find_Post_By_Password();
	$plugin->run();

}
run_find_post_by_password();
