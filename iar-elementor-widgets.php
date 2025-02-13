<?php
/**
 * Plugin Name: IAR Elementor Widgets
 * Plugin URI: https://iamroot.agency
 * Description: IAR Custom Elementor widgets.
 * Version: 1.0.1
 * Author: Drazen Biljak
 * Author URI: https://iamroot.agency
 * License: GPL2+
 */

if ( ! defined( 'WPINC' ) ) {
	exit;
}

require_once 'vendor/autoload.php';

define( 'IAR_ELEMENTOR_WIDGETS_VERSION', '1.0.1' );
define( 'IAR_ELEMENTOR_WIDGETS_SLUG', 'iar-elementor-widgets' );
define( 'IAR_ELEMENTOR_WIDGETS_TITLE', 'Elementor Widgets by I am Root' );
define( 'IAR_ELEMENTOR_WIDGETS_URL', plugin_dir_url( __FILE__ ) );
define( 'IAR_ELEMENTOR_WIDGETS_PATH', plugin_dir_path( __FILE__ ) );
define( 'IAR_ELEMENTOR_WIDGETS_BASENAME', plugin_basename( __FILE__ ) );
define( 'IAR_ELEMENTOR_WIDGETS_DIR', __DIR__ );
define( 'IAR_ELEMENTOR_WIDGETS_FILE', __FILE__ );
define( 'API_NAMESPACE', 'iar-elementor-widgets/v1' );
define( 'GITHUB_REPO_URL', 'https://github.com/iamroothr/iar-elementor-widgets' );
define( 'GITHUB_REPO_PROD_BRANCH', 'main' );

$files = [ 'config', 'setup', 'ajax', 'widgets' ];

foreach ( $files as $file ) {
	$file_path = IAR_ELEMENTOR_WIDGETS_PATH . "app/{$file}.php";

	if ( ! $file_path ) {
		wp_die(
			sprintf( __( 'Error locating <code>%s</code> for inclusion.', 'sage' ), "app/{$file}.php" )
		);
	}

	require_once $file_path;
}
