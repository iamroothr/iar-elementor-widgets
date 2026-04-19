<?php

namespace IarElementorWidgets\Widgets\ImageGridWidget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class ImageGridWidget extends Widget_Base {
    public function get_name(): string {
        return 'iar_image_grid';
    }

    public function get_title(): string {
        return __( 'Image Grid', 'iar-elementor-widgets' );
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

        // Register the widget stylesheet with the plugin version constant instead of a hardcoded string.
        wp_register_style( 'iar-image-grid-widget', IAR_ELEMENTOR_WIDGETS_URL . 'public/imageGridWidget/style.css', [], IAR_ELEMENTOR_WIDGETS_VERSION );
    }

	protected function register_controls(): void {
		$this->start_controls_section( 'layout_section', [
            'label' => __( 'Layout', 'iar-elementor-widgets' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

		$this->add_responsive_control( 'grid_height', [
            'label'      => __( 'Grid Height', 'iar-elementor-widgets' ),
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
            'label'      => __( 'Grid Gap', 'iar-elementor-widgets' ),
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
            'label' => __( 'Grid Items', 'iar-elementor-widgets' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

		$repeater = new Repeater();

		$repeater->add_control( 'background_image', [
            'label'   => __( 'Background Image', 'iar-elementor-widgets' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [
                'url' => Utils::get_placeholder_image_src(),
            ],
        ] );

		$repeater->add_control( 'title', [
            'label'   => __( 'Title', 'iar-elementor-widgets' ),
            'type'    => Controls_Manager::TEXT,
            'default' => __( 'Grid Item Title', 'iar-elementor-widgets' ),
        ] );

		$repeater->add_control( 'subtitle', [
            'label'   => __( 'Subtitle', 'iar-elementor-widgets' ),
            'type'    => Controls_Manager::TEXT,
            'default' => __( 'Grid Item Subtitle', 'iar-elementor-widgets' ),
        ] );

		$repeater->add_control( 'link', [
            'label'       => __( 'Link', 'iar-elementor-widgets' ),
            'type'        => Controls_Manager::URL,
            'placeholder' => __( 'https://example.com', 'iar-elementor-widgets' ),
            'default'     => [
                'url' => '#',
            ],
        ] );

		$repeater->add_control( 'link_label', [
            'label'   => __( 'Link Label', 'iar-elementor-widgets' ),
            'type'    => Controls_Manager::TEXT,
            'default' => __( 'Open', 'iar-elementor-widgets' ),
        ] );

        $this->add_control( 'grid_items_notice', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => __( 'Maximum of 4 grid items will be displayed.', 'iar-elementor-widgets' ),
            'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
        ] );

        $this->add_control( 'grid_items', [
            'label'       => __( 'Grid Items', 'iar-elementor-widgets' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'title'    => __( 'Item #1', 'iar-elementor-widgets' ),
                    'subtitle' => __( 'Subtitle #1', 'iar-elementor-widgets' ),
                ],
            ],
            'title_field' => '{{{ title }}}',
        ] );

		$this->end_controls_section();

		$this->start_controls_section( 'style_section', [
            'label' => __( 'Style', 'iar-elementor-widgets' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

		$this->add_control( 'title_color', [
            'label'     => __( 'Title Color', 'iar-elementor-widgets' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .iar-image-grid__title' => 'color: {{VALUE}}',
            ],
        ] );

		$this->add_control( 'subtitle_color', [
            'label'     => __( 'Subtitle Color', 'iar-elementor-widgets' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .iar-image-grid__subtitle' => 'color: {{VALUE}}',
            ],
        ] );

		$this->end_controls_section();

		// --- Button Style Section ---
		$this->start_controls_section( 'button_style_section', [
			'label' => __( 'Button', 'iar-elementor-widgets' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'button_typography',
			'selector' => '{{WRAPPER}} .elementor-button',
		] );

		$this->add_control( 'button_text_color', [
			'label'     => __( 'Text Color', 'iar-elementor-widgets' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'button_bg_color', [
			'label'     => __( 'Background Color', 'iar-elementor-widgets' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'button_padding', [
			'label'      => __( 'Padding', 'iar-elementor-widgets' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_control( 'button_border_radius', [
			'label'      => __( 'Border Radius', 'iar-elementor-widgets' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [ '{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_control( 'button_show_arrow', [
			'label'        => __( 'Show Arrow Icon', 'iar-elementor-widgets' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'iar-elementor-widgets' ),
			'label_off'    => __( 'No', 'iar-elementor-widgets' ),
			'return_value' => 'yes',
			'default'      => '',
			'separator'    => 'before',
		] );

		$this->end_controls_section();
	}

	protected function render(): void {
        $settings = $this->get_settings_for_display();
        $items    = $settings['grid_items'];

        // Limit items to a maximum of 4. Elementor repeaters don't provide a
        // native `max_items` option, so enforce it server-side by slicing the
        // array before rendering the view.
        $items = is_array( $items ) ? array_slice( $items, 0, 4 ) : [];

        $count    = count( $items );

		if ( $count === 0 ) {
			return;
		}

		iar_render_view( 'image-grid-widget.index', [ 'count' => $count, 'items' => $items, 'settings' => $settings ] );
	}
}
