<?php

namespace IarElementorWidgets\Widgets\HeroWidget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class HeroWidget extends Widget_Base {

    public function get_name(): string {
        return 'iar_custom_hero';
    }

    public function get_title(): string {
        return __( 'Custom Hero', 'iar-elementor-widgets' );
    }

	public function get_icon(): string {
		return 'eicon-banner';
	}

	public function get_categories(): array {
		return [ 'iar-widgets' ];
	}

	public function get_style_depends(): array {
		return [ 'iar-hero-widget' ];
	}

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        // Register the widget stylesheet with the plugin version constant instead of a hardcoded string.
        wp_register_style( 'iar-hero-widget', IAR_ELEMENTOR_WIDGETS_URL . 'public/heroWidget/style.css', [], IAR_ELEMENTOR_WIDGETS_VERSION );
    }

    /**
     * Register widget controls.
     */
    protected function register_controls(): void {
        $this->start_controls_section( 'layout_section', [
            'label' => __( 'Layout', 'iar-elementor-widgets' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

		$this->add_responsive_control( 'hero_height', [
            'label'      => __( 'Hero Height', 'iar-elementor-widgets' ),
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
				'{{WRAPPER}} .iar-custom-hero__background' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'background_section', [
            'label' => __( 'Background', 'iar-elementor-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'background_image', [
            'label'   => __( 'Background Image', 'iar-elementor-widgets' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		] );

		$this->end_controls_section();

        // Use a unique section ID for alignment to avoid duplicate IDs.
        $this->start_controls_section( 'alignment_section', [
            // Change label from 'Layout' to 'Alignment' for clarity in the editor UI.
            'label' => __( 'Alignment', 'iar-elementor-widgets' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

		$this->add_control( 'content_alignment', [
            'label'   => __( 'Content Alignment', 'iar-elementor-widgets' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'left'   => [
                    'title' => __( 'Left', 'iar-elementor-widgets' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
                    'title' => __( 'Center', 'iar-elementor-widgets' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'  => [
                    'title' => __( 'Right', 'iar-elementor-widgets' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'default' => 'center',
			'toggle'  => true,
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'content_section', [
            'label' => __( 'Content', 'iar-elementor-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'subtitle', [
            'label'     => __( 'Subtitle', 'iar-elementor-widgets' ),
            'type'      => Controls_Manager::TEXTAREA,
            'default'   => __( 'Your Subtitle Here', 'iar-elementor-widgets' ),
			'rows'      => 4,
			'separator' => 'before',
		] );

		$this->add_control( 'title', [
            'label'     => __( 'Title', 'iar-elementor-widgets' ),
            'type'      => Controls_Manager::TEXTAREA,
            'default'   => __( 'Your Hero Title', 'iar-elementor-widgets' ),
			'rows'      => 4,
			'separator' => 'before',
		] );

		$this->add_control( 'button_text', [
            'label'   => __( 'Button Text', 'iar-elementor-widgets' ),
            'type'    => Controls_Manager::TEXT,
            'default' => __( 'Learn More', 'iar-elementor-widgets' ),
		] );

		$this->add_control( 'button_link_type', [
            'label'   => __( 'Link Type', 'iar-elementor-widgets' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'custom',
			'options' => [
                'custom' => __( 'Custom URL', 'iar-elementor-widgets' ),
                'page'   => __( 'Page', 'iar-elementor-widgets' ),
			],
		] );

		$this->add_control( 'button_link', [
            'label'       => __( 'Link', 'iar-elementor-widgets' ),
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
            'label'     => __( 'Select Page', 'iar-elementor-widgets' ),
			'type'      => Controls_Manager::SELECT2,
			'options'   => $this->get_pages_list(),
			'condition' => [
				'button_link_type' => 'page',
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

	private function get_pages_list(): array {
		$pages   = get_pages();
		$options = [];

		foreach ( $pages as $page ) {
			$options[ $page->ID ] = $page->post_title;
		}

		return $options;
	}

	protected function render(): void {
		iar_render_view( 'hero-widget.index', [ 'settings' => $this->get_settings_for_display() ] );
	}
}
