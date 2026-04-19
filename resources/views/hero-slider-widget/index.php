<?php
/**
 * Hero Slider Widget view.
 *
 * @var array $settings
 * @var string $widget_id
 */

$slides    = $settings['slides'];
$effect    = $settings['effect'] ?? 'slide';
$loop      = ( $settings['loop'] ?? '' ) === 'yes';
$autoplay  = ( $settings['autoplay'] ?? '' ) === 'yes';
$delay     = ! empty( $settings['autoplay_delay']['size'] ) ? (int) $settings['autoplay_delay']['size'] : 5000;
$duration  = ! empty( $settings['transition_duration']['size'] ) ? (int) $settings['transition_duration']['size'] : 600;
$arrows    = ( $settings['show_arrows'] ?? '' ) === 'yes';
$alignment = $settings['content_alignment'] ?? 'center';

$slider_classes = [ 'iar-hero-slider', 'embla' ];
if ( $effect === 'fade' ) {
	$slider_classes[] = 'iar-hero-slider--fade';
} elseif ( $effect === 'parallax' ) {
	$slider_classes[] = 'iar-hero-slider--parallax';
}
?>

<div class="<?php echo esc_attr( implode( ' ', $slider_classes ) ); ?>"
     id="iar-hero-slider-<?php echo esc_attr( $widget_id ); ?>"
     data-loop="<?php echo esc_attr( $loop ? 'true' : 'false' ); ?>"
     data-autoplay="<?php echo esc_attr( $autoplay ? 'true' : 'false' ); ?>"
     data-delay="<?php echo esc_attr( $delay ); ?>"
      data-effect="<?php echo esc_attr( $effect ); ?>"
      data-duration="<?php echo esc_attr( $duration ); ?>">

	<div class="embla__container iar-hero-slider__container">
		<?php foreach ( $slides as $slide ) :
			$image_url = ! empty( $slide['background_image']['url'] ) ? $slide['background_image']['url'] : '';
			$link_url  = $slide['button_link_type'] === 'page' && ! empty( $slide['button_page'] )
				? get_permalink( $slide['button_page'] )
				: ( $slide['button_link']['url'] ?? '#' );
			$is_external = ! empty( $slide['button_link']['is_external'] );
			$nofollow    = ! empty( $slide['button_link']['nofollow'] );
		?>
			<div class="embla__slide iar-hero-slider__slide"
			     style="background-image: url('<?php echo esc_url( $image_url ); ?>');">
				<div class="iar-hero-slider__content" style="text-align: <?php echo esc_attr( $alignment ); ?>;">
					<?php if ( ! empty( $slide['subtitle'] ) ) : ?>
						<p class="iar-hero-slider__subtitle"><?php echo esc_html( $slide['subtitle'] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $slide['title'] ) ) : ?>
						<h2 class="iar-hero-slider__title"><?php echo nl2br( esc_html( $slide['title'] ) ); ?></h2>
					<?php endif; ?>

                    <?php if ( ! empty( $slide['button_text'] ) ) : ?>
                        <a class="iar-hero-slider__button elementor-button<?php echo ( $settings['button_show_arrow'] ?? '' ) === 'yes' ? ' iar-button--has-arrow' : ''; ?>" href="<?php echo esc_url( $link_url ); ?>"
<?php
$rel_parts = [];
if ( $nofollow ) $rel_parts[] = 'nofollow';
if ( $is_external ) $rel_parts[] = 'noopener noreferrer';
$rel_attr = ! empty( $rel_parts ) ? 'rel="' . esc_attr( implode( ' ', $rel_parts ) ) . '"' : '';
?>
							<?php echo $is_external ? 'target="_blank"' : ''; ?>
							<?php echo $rel_attr; ?>>
							<?php echo esc_html( $slide['button_text'] ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ( $arrows ) : ?>
		<button class="iar-hero-slider__arrow iar-hero-slider__arrow--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'iar-elementor-widgets' ); ?>">
			<svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
		</button>
		<button class="iar-hero-slider__arrow iar-hero-slider__arrow--next" aria-label="<?php esc_attr_e( 'Next slide', 'iar-elementor-widgets' ); ?>">
			<svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
		</button>
	<?php endif; ?>

    <!-- Dot navigation removed from template -->
</div>
