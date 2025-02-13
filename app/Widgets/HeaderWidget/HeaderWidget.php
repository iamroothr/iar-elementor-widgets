<?php

namespace IarElementorWidgets\Widgets\HeaderWidget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class HeaderWidget extends Widget_Base {

	public function get_name(): string {
		return 'iar_custom_header';
	}

	public function get_title(): string {
		return __( 'Custom Header', 'iar_custom_header' );
	}

	public function get_icon(): string {
		return 'eicon-header';
	}

	public function get_categories(): array {
		return [ 'iar-widgets' ];
	}

	public function get_style_depends(): array {
		return [ 'iar-header-widget' ];
	}

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style( 'iar-header-widget', IAR_ELEMENTOR_WIDGETS_URL . 'public/headerWidget/style.css', [], '1.0.0' );
	}

	protected function _register_controls(): void {
		$this->start_controls_section( 'layout_section', [
			'label' => __( 'Layout', 'iar_custom_header' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_responsive_control( 'header_height', [
			'label'      => __( 'Header Height', 'iar_custom_header' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'vh' ],
			'range'      => [
				'px' => [
					'min'  => 200,
					'max'  => 1000,
					'step' => 10,
				],
				'vh' => [
					'min'  => 10,
					'max'  => 100,
					'step' => 1,
				],
			],
			'default'    => [
				'unit' => 'vh',
				'size' => 60,
			],
			'selectors'  => [
				'{{WRAPPER}} .iar-custom-header__background' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'background_section', [
			'label' => __( 'Background', 'iar_custom_header' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'background_image', [
			'label'   => __( 'Background Image', 'iar_custom_header' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'layout_section', [
			'label' => __( 'Layout', 'iar_custom_header' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'content_alignment', [
			'label'   => __( 'Content Alignment', 'iar_custom_header' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'left'   => [
					'title' => __( 'Left', 'iar_custom_header' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
					'title' => __( 'Center', 'iar_custom_header' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'  => [
					'title' => __( 'Right', 'iar_custom_header' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'default' => 'center',
			'toggle'  => true,
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'content_section', [
			'label' => __( 'Content', 'iar_custom_header' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'subtitle', [
			'label'     => __( 'Subtitle', 'iar_custom_header' ),
			'type'      => Controls_Manager::TEXTAREA,
			'default'   => __( 'Your Subtitle Here', 'iar_custom_header' ),
			'rows'      => 4,
			'separator' => 'before',
		] );

		$this->add_control( 'title', [
			'label'     => __( 'Title', 'iar_custom_header' ),
			'type'      => Controls_Manager::TEXTAREA,
			'default'   => __( 'Your Header Title', 'iar_custom_header' ),
			'rows'      => 4,
			'separator' => 'before',
		] );

		$this->add_control( 'button_text', [
			'label'   => __( 'Button Text', 'iar_custom_header' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Learn More', 'iar_custom_header' ),
		] );

		$this->add_control( 'button_link_type', [
			'label'   => __( 'Link Type', 'iar_custom_header' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'custom',
			'options' => [
				'custom' => __( 'Custom URL', 'iar_custom_header' ),
				'page'   => __( 'Page', 'iar_custom_header' ),
			],
		] );

		$this->add_control( 'button_link', [
			'label'       => __( 'Link', 'iar_custom_header' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => 'https://your-link.com',
			'condition'   => [
				'button_link_type' => 'custom',
			],
			'default'     => [
				'url' => '#',
			],
		] );

		$this->add_control( 'button_page', [
			'label'     => __( 'Select Page', 'iar_custom_header' ),
			'type'      => Controls_Manager::SELECT2,
			'options'   => $this->get_pages_list(),
			'condition' => [
				'button_link_type' => 'page',
			],
		] );

		$this->end_controls_section();
	}

	private function get_pages_list(): array {
		$pages   = get_pages();
		$options = [];

		foreach ( $pages as $page ) {
			$options[ $page->ID ] = $page->post_title;
		}

		return $options;
	}

	protected function render(): void {
		iar_render_view( 'header-widget.index', [ 'settings' => $this->get_settings_for_display() ] );
	}
}