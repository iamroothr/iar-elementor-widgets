<?php

namespace IarElementorWidgets\Widgets\HeroSliderWidget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class HeroSliderWidget extends Widget_Base {

	public function get_name(): string {
		return 'iar_hero_slider';
	}

	public function get_title(): string {
		return __( 'Hero Slider', 'iar-elementor-widgets' );
	}

	public function get_icon(): string {
		return 'eicon-slides';
	}

	public function get_categories(): array {
		return [ 'iar-widgets' ];
	}

	public function get_style_depends(): array {
		return [ 'iar-hero-slider-widget' ];
	}

	public function get_script_depends(): array {
		return [ 'iar-hero-slider-widget' ];
	}

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style( 'iar-hero-slider-widget', IAR_ELEMENTOR_WIDGETS_URL . 'public/heroSliderWidget/style.css', [], IAR_ELEMENTOR_WIDGETS_VERSION );
		wp_register_script( 'iar-hero-slider-widget', IAR_ELEMENTOR_WIDGETS_URL . 'public/heroSliderWidget/bundle.js', [], IAR_ELEMENTOR_WIDGETS_VERSION, true );
	}

	protected function register_controls(): void {
		// --- Layout Section ---
		$this->start_controls_section( 'layout_section', [
			'label' => __( 'Layout', 'iar-elementor-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_responsive_control( 'slider_height', [
			'label'      => __( 'Slider Height', 'iar-elementor-widgets' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'vh' ],
			'range'      => [
				'px' => [ 'min' => 200, 'max' => 1200, 'step' => 10 ],
				'vh' => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
			],
            'default' => [ 'unit' => 'vh', 'size' => 80 ],
            'selectors' => [
                // Use a CSS custom property so we can compute final height with an offset.
                '{{WRAPPER}} .iar-hero-slider' => '--iar-slider-height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        // Add a new responsive control for subtracting an offset (e.g. menu height)
        $this->add_responsive_control( 'slider_height_offset', [
            'label'      => __( 'Height Offset', 'iar-elementor-widgets' ),
            'description' => __( 'Subtract from slider height (e.g. menu height)', 'iar-elementor-widgets' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [
                'px' => [ 'min' => 0, 'max' => 500, 'step' => 1 ],
            ],
            'default' => [ 'unit' => 'px', 'size' => 0 ],
            'selectors' => [
                // Store offset in a CSS custom property for use in calc().
                '{{WRAPPER}} .iar-hero-slider' => '--iar-slider-offset: {{SIZE}}{{UNIT}};',
            ],
        ] );

		$this->add_control( 'overlay_opacity', [
			'label'   => __( 'Overlay Opacity', 'iar-elementor-widgets' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
			'default' => [ 'size' => 0.4 ],
			'selectors' => [
				'{{WRAPPER}} .iar-hero-slider__slide::before' => 'background: rgba(0, 0, 0, {{SIZE}});',
			],
		] );

		$this->add_responsive_control( 'slide_bg_size', [
			'label'   => __( 'Background Size', 'iar-elementor-widgets' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'cover'   => __( 'Cover', 'iar-elementor-widgets' ),
				'contain' => __( 'Contain', 'iar-elementor-widgets' ),
				'auto'    => __( 'Auto', 'iar-elementor-widgets' ),
			],
			'default'   => 'cover',
			'selectors' => [
				'{{WRAPPER}} .iar-hero-slider__slide' => 'background-size: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'slide_bg_position', [
			'label'   => __( 'Background Position', 'iar-elementor-widgets' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'center center' => __( 'Center Center', 'iar-elementor-widgets' ),
				'center top'    => __( 'Center Top', 'iar-elementor-widgets' ),
				'center bottom' => __( 'Center Bottom', 'iar-elementor-widgets' ),
				'left center'   => __( 'Left Center', 'iar-elementor-widgets' ),
				'right center'  => __( 'Right Center', 'iar-elementor-widgets' ),
                // Use horizontal-first ordering for CSS background-position values
                // (Elementor outputs these directly into CSS). Labels remain the same.
                'left top'      => __( 'Top Left', 'iar-elementor-widgets' ),
                'right top'     => __( 'Top Right', 'iar-elementor-widgets' ),
                'left bottom'   => __( 'Bottom Left', 'iar-elementor-widgets' ),
                'right bottom'  => __( 'Bottom Right', 'iar-elementor-widgets' ),
			],
			'default'   => 'center center',
			'selectors' => [
				'{{WRAPPER}} .iar-hero-slider__slide' => 'background-position: {{VALUE}};',
			],
		] );

		$this->add_control( 'content_alignment', [
			'label'     => __( 'Content Alignment', 'iar-elementor-widgets' ),
			'type'      => Controls_Manager::CHOOSE,
			'separator' => 'before',
			'options' => [
				'left'   => [ 'title' => __( 'Left', 'iar-elementor-widgets' ), 'icon' => 'eicon-text-align-left' ],
				'center' => [ 'title' => __( 'Center', 'iar-elementor-widgets' ), 'icon' => 'eicon-text-align-center' ],
				'right'  => [ 'title' => __( 'Right', 'iar-elementor-widgets' ), 'icon' => 'eicon-text-align-right' ],
			],
			'default' => 'center',
			'toggle'  => true,
		] );

		$this->end_controls_section();

		// --- Slides Section (Repeater) ---
		$this->start_controls_section( 'slides_section', [
			'label' => __( 'Slides', 'iar-elementor-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'background_image', [
			'label'   => __( 'Background Image', 'iar-elementor-widgets' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => Utils::get_placeholder_image_src() ],
		] );

		$repeater->add_control( 'subtitle', [
			'label'   => __( 'Subtitle', 'iar-elementor-widgets' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Your Subtitle', 'iar-elementor-widgets' ),
		] );

		$repeater->add_control( 'title', [
			'label'   => __( 'Title', 'iar-elementor-widgets' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => __( 'Your Hero Title', 'iar-elementor-widgets' ),
			'rows'    => 3,
		] );

		$repeater->add_control( 'button_text', [
			'label'   => __( 'Button Text', 'iar-elementor-widgets' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Learn More', 'iar-elementor-widgets' ),
		] );

		$repeater->add_control( 'button_link_type', [
			'label'   => __( 'Link Type', 'iar-elementor-widgets' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'custom',
			'options' => [
				'custom' => __( 'Custom URL', 'iar-elementor-widgets' ),
				'page'   => __( 'Page', 'iar-elementor-widgets' ),
			],
		] );

		$repeater->add_control( 'button_link', [
			'label'       => __( 'Link', 'iar-elementor-widgets' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => 'https://your-link.com',
			'condition'   => [ 'button_link_type' => 'custom' ],
			'default'     => [ 'url' => '#' ],
		] );

		$repeater->add_control( 'button_page', [
			'label'     => __( 'Select Page', 'iar-elementor-widgets' ),
			'type'      => Controls_Manager::SELECT2,
			'options'   => $this->get_pages_list(),
			'condition' => [ 'button_link_type' => 'page' ],
		] );

		$this->add_control( 'slides', [
			'label'       => __( 'Slides', 'iar-elementor-widgets' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[ 'title' => __( 'First Slide', 'iar-elementor-widgets' ), 'subtitle' => __( 'Subtitle', 'iar-elementor-widgets' ) ],
				[ 'title' => __( 'Second Slide', 'iar-elementor-widgets' ), 'subtitle' => __( 'Subtitle', 'iar-elementor-widgets' ) ],
			],
			'title_field' => '{{{ title }}}',
		] );

		$this->end_controls_section();

		// --- Slider Settings Section ---
		$this->start_controls_section( 'slider_settings_section', [
			'label' => __( 'Slider Settings', 'iar-elementor-widgets' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'loop', [
			'label'        => __( 'Loop', 'iar-elementor-widgets' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'iar-elementor-widgets' ),
			'label_off'    => __( 'No', 'iar-elementor-widgets' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'autoplay', [
			'label'        => __( 'Autoplay', 'iar-elementor-widgets' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'iar-elementor-widgets' ),
			'label_off'    => __( 'No', 'iar-elementor-widgets' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'autoplay_delay', [
			'label'     => __( 'Autoplay Delay (ms)', 'iar-elementor-widgets' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 1000, 'max' => 10000, 'step' => 500 ] ],
			'default'   => [ 'size' => 5000 ],
			'condition' => [ 'autoplay' => 'yes' ],
		] );

		// Transition duration control (milliseconds). Provides a slider to control
		// the length of the slide transition. Converted later in JS to Embla's
		// duration weight where lower = faster.
		$this->add_control( 'transition_duration', [
			'label'     => __( 'Transition Duration (ms)', 'iar-elementor-widgets' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 100, 'max' => 3000, 'step' => 50 ] ],
			'default'   => [ 'size' => 600 ],
		] );

		$this->add_control( 'effect', [
			'label'   => __( 'Effect', 'iar-elementor-widgets' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'slide',
			'options' => [
				'slide'    => __( 'Slide', 'iar-elementor-widgets' ),
				'fade'     => __( 'Fade', 'iar-elementor-widgets' ),
				'parallax' => __( 'Parallax', 'iar-elementor-widgets' ),
			],
		] );

		$this->add_control( 'show_arrows', [
			'label'        => __( 'Show Arrows', 'iar-elementor-widgets' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'iar-elementor-widgets' ),
			'label_off'    => __( 'No', 'iar-elementor-widgets' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'dynamic_slide_order', [
			'label'        => __( 'Dynamic Slide Order', 'iar-elementor-widgets' ),
			'description'  => __( 'Rotate the order of the first N slides on each page load.', 'iar-elementor-widgets' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'iar-elementor-widgets' ),
			'label_off'    => __( 'No', 'iar-elementor-widgets' ),
			'return_value' => 'yes',
			'default'      => '',
			'separator'    => 'before',
		] );

		$this->add_control( 'dynamic_slide_count', [
			'label'       => __( 'Slides Included', 'iar-elementor-widgets' ),
			'description' => __( 'Number of first slides to rotate.', 'iar-elementor-widgets' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 2,
			'max'         => 10,
			'default'     => 2,
			'condition'   => [ 'dynamic_slide_order' => 'yes' ],
		] );

		// Show dots control removed — dot navigation is no longer supported.
		$this->end_controls_section();

		// --- Style Section ---
		$this->start_controls_section( 'style_section', [
			'label' => __( 'Style', 'iar-elementor-widgets' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'title_color', [
			'label'     => __( 'Title Color', 'iar-elementor-widgets' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .iar-hero-slider__title' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'subtitle_color', [
			'label'     => __( 'Subtitle Color', 'iar-elementor-widgets' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .iar-hero-slider__subtitle' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => __( 'Title Typography', 'iar-elementor-widgets' ),
			'selector' => '{{WRAPPER}} .iar-hero-slider__title',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'subtitle_typography',
			'label'    => __( 'Subtitle Typography', 'iar-elementor-widgets' ),
			'selector' => '{{WRAPPER}} .iar-hero-slider__subtitle',
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

	/**
	 * Get a list of all published pages for the page selector control.
	 *
	 * @return array<int, string> Page ID => Page title pairs.
	 */
	private function get_pages_list(): array {
		$pages   = get_pages();
		$options = [];

		foreach ( $pages as $page ) {
			$options[ $page->ID ] = $page->post_title;
		}

		return $options;
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['slides'] ) ) {
			return;
		}

		iar_render_view( 'hero-slider-widget.index', [
			'settings'  => $settings,
			'widget_id' => $this->get_id(),
		] );
	}
}
