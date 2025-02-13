<?php

use IarElementorWidgets\Widgets\HeaderWidget\HeaderWidget;
use IarElementorWidgets\Widgets\ImageGridWidget\ImageGridWidget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'elementor/elements/categories_registered', function ( $elements_manager ) {
	$elements_manager->add_category( 'iar-widgets', [
		'title' => esc_html__( 'I am root', 'iar-elementor' ),
		'icon'  => 'fa fa-plug',
	] );
} );

add_action( 'plugins_loaded', function (): void {
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', function () {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'IAR Elementor Image Grid plugin requires Elementor to be installed and active.', 'iar_image_grid' ) . '</p></div>';
		} );

		return;
	}

	add_action( 'elementor/widgets/register', function ( $widgets_manager ): void {
		$widgets_manager->register( new ImageGridWidget() );
		$widgets_manager->register( new HeaderWidget() );
	} );
} );
