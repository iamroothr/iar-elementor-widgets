<?php

use IarElementorWidgets\Core\ViewFinder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function iar_get_view( $partial, $data = null ): string {
	return ViewFinder::iar_get_instance()->iar_get_view( $partial, $data );
}

function iar_render_view( $partial, $data = null ): void {
	ViewFinder::iar_get_instance()->iar_render_view( $partial, $data );
}
