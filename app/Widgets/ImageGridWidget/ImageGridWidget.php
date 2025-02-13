<?php

namespace IarElementorWidgets\Widgets\ImageGridWidget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

class ImageGridWidget extends Widget_Base {
	public function get_name(): string {
		return 'iar_image_grid';
	}

	public function get_title(): string {
		return __( 'Image Grid', 'iar_image_grid' );
	}

	public function get_icon(): string {
		return 'eicon-gallery-grid';
	}

	public function get_categories(): array {
		return [ 'iar-widgets' ];
	}

	public function get_style_depends(): array {
		return [ 'iar-image-grid-widget' ];
	}

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style( 'iar-image-grid-widget', IAR_ELEMENTOR_WIDGETS_URL . 'public/imageGridWidget/style.css', [], '1.0.0' );
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'layout_section', [
            'label' => __( 'Layout', 'iar_image_grid' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

		$this->add_responsive_control( 'grid_height', [
            'label'      => __( 'Grid Height', 'iar_image_grid' ),
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
                'unit' => 'px',
                'size' => 400,
            ],
            'selectors'  => [
                '{{WRAPPER}} .iar-image-grid' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ] );

		$this->add_responsive_control( 'grid_gap', [
            'label'      => __( 'Grid Gap', 'iar_image_grid' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [
                'px' => [
                    'min'  => 0,
                    'max'  => 100,
                    'step' => 1,
                ],
            ],
            'default'    => [
                'unit' => 'px',
                'size' => 20,
            ],
            'selectors'  => [
                '{{WRAPPER}} .iar-image-grid' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ] );

		$this->end_controls_section();

		$this->start_controls_section( 'items_section', [
            'label' => __( 'Grid Items', 'iar_image_grid' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

		$repeater = new Repeater();

		$repeater->add_control( 'background_image', [
            'label'   => __( 'Background Image', 'iar_image_grid' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [
                'url' => Utils::get_placeholder_image_src(),
            ],
        ] );

		$repeater->add_control( 'title', [
            'label'   => __( 'Title', 'iar_image_grid' ),
            'type'    => Controls_Manager::TEXT,
            'default' => __( 'Grid Item Title', 'iar_image_grid' ),
        ] );

		$repeater->add_control( 'subtitle', [
            'label'   => __( 'Subtitle', 'iar_image_grid' ),
            'type'    => Controls_Manager::TEXT,
            'default' => __( 'Grid Item Subtitle', 'iar_image_grid' ),
        ] );

		$repeater->add_control( 'link', [
            'label'       => __( 'Link', 'iar_image_grid' ),
            'type'        => Controls_Manager::URL,
            'placeholder' => __( 'https://example.com', 'iar_image_grid' ),
            'default'     => [
                'url' => '#',
            ],
        ] );

		$repeater->add_control( 'link_label', [
            'label'   => __( 'Link Label', 'iar_image_grid' ),
            'type'    => Controls_Manager::TEXT,
            'default' => __( 'Open', 'iar_image_grid' ),
        ] );

		$this->add_control( 'grid_items', [
            'label'       => __( 'Grid Items', 'iar_image_grid' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'title'    => __( 'Item #1', 'iar_image_grid' ),
                    'subtitle' => __( 'Subtitle #1', 'iar_image_grid' ),
                ],
            ],
            'title_field' => '{{{ title }}}',
        ] );

		$this->end_controls_section();

		$this->start_controls_section( 'style_section', [
            'label' => __( 'Style', 'iar_image_grid' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

		$this->add_control( 'title_color', [
            'label'     => __( 'Title Color', 'iar_image_grid' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .iar-image-grid__title' => 'color: {{VALUE}}',
            ],
        ] );

		$this->add_control( 'subtitle_color', [
            'label'     => __( 'Subtitle Color', 'iar_image_grid' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .iar-image-grid__subtitle' => 'color: {{VALUE}}',
            ],
        ] );

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$items    = $settings['grid_items'];
		$count    = count( $items );

		if ( $count === 0 ) {
			return;
		}

		iar_render_view( 'image-grid-widget.index', [ 'count' => $count, 'items' => $items ] );
	}
}