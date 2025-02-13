<?php

namespace IarElementorWidgets\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Exception;

class ViewFinder {

	/**
	 * @var null|ViewFinder
	 */
	private static ?ViewFinder $instance = null;

	private const VIEW_FOLDER = 'resources/views';

	/**
	 * @return ViewFinder|null
	 */
	public static function iar_get_instance(): ?ViewFinder {
		if ( null === self::$instance ) {
			self::$instance = new ViewFinder();
		}

		return self::$instance;
	}

	public function iar_get_view( $view, $data = null ): string {
		$file_path = $this->iar_get_view_path( $view );

		if ( ! empty( $file_path ) ) {
			return $this->iar_get_internal( $file_path, $data );
		}

		return '';
	}

	public function iar_render_view( $view, $data = null ): void {
		$file_path = $this->iar_get_view_path( $view );

		if ( ! empty( $file_path ) ) {
			$this->iar_render_internal( $file_path, $data );
		}
	}

	private function iar_get_view_path( $view ): string {
		$view   = str_replace( [ '.php', '.' ], [ '', '/' ], $view );
		$file_path = IAR_ELEMENTOR_WIDGETS_PATH . self::VIEW_FOLDER . DIRECTORY_SEPARATOR . $view . '.php';

		if ( file_exists( $file_path ) ) {
			return $file_path;
		}

		return '';
	}

	private function iar_render_internal( string $_view_file_, array $_data_ = null ): void {
		if ( $_data_ !== null ) {
			extract( $_data_ );
		}

		require $_view_file_;
	}

	private function iar_get_internal( $_view_file_, array $_data_ = null ) {
		if ( $_data_ !== null ) {
			extract( $_data_, EXTR_OVERWRITE );
		}

		ob_start();
		ob_implicit_flush( 0 );
		require $_view_file_;

		return ob_get_clean();
	}
}

