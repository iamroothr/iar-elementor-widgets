<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

if ( ! empty( GITHUB_REPO_URL ) && ! empty( GITHUB_REPO_PROD_BRANCH ) ) {
	require IAR_ELEMENTOR_WIDGETS_PATH . 'plugin-update-checker/plugin-update-checker.php';

	$myUpdateChecker = PucFactory::buildUpdateChecker(
		GITHUB_REPO_URL,
		IAR_ELEMENTOR_WIDGETS_FILE,
		IAR_ELEMENTOR_WIDGETS_SLUG
	);

	$myUpdateChecker->setBranch( GITHUB_REPO_PROD_BRANCH );
}

function get_language_suffix(): string {
	$wpml_exist = defined( 'ICL_SITEPRESS_VERSION' );
	$suffix		= '';

	if ( $wpml_exist ) {
		$current_language = apply_filters( 'wpml_current_language', null );
		$suffix			  = ! empty( $current_language ) ? '_' . $current_language : '';
	}

	return $suffix;
}
