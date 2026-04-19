<?php
/**
 * @var $settings
 */

$link_url = $settings['button_link_type'] === 'page' && ! empty( $settings['button_page'] ) ? get_permalink( $settings['button_page'] ) : $settings['button_link']['url'];
?>

<div class="iar-custom-hero">
	<div class="iar-custom-hero__background" style="background-image: url('<?php echo esc_url( $settings['background_image']['url'] ); ?>');">
		<div class="iar-custom-hero__content" style="text-align: <?php echo esc_attr( $settings['content_alignment'] ); ?>;">
			<?php if ( $settings['subtitle'] ) : ?>
				<h2 class="iar-custom-hero__subtitle"><?php echo nl2br( esc_html( $settings['subtitle'] ) ); ?></h2>
			<?php endif; ?>

			<?php if ( $settings['title'] ) : ?>
				<h1 class="iar-custom-hero__title"><?php echo nl2br( esc_html( $settings['title'] ) ); ?></h1>
			<?php endif; ?>

            <?php if ( $settings['button_text'] ) : ?>
                <a class="iar-custom-hero__button elementor-button<?php echo ( $settings['button_show_arrow'] ?? '' ) === 'yes' ? ' iar-button--has-arrow' : ''; ?>" href="<?php echo esc_url( $link_url ); ?>"
					<?php echo $settings['button_link']['is_external'] ? 'target="_blank"' : ''; ?>
					<?php echo $settings['button_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>>
					<?php echo esc_html( $settings['button_text'] ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>
