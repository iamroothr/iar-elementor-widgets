<?php
/**
 * @var $count
 * @var $items
 */
?>

<div class="iar-image-grid iar-image-grid__number-<?php echo $count; ?>">
	<?php foreach ( $items as $item ) : ?>
		<?php if ( ! empty( $item['background_image']['url'] ) ) : ?>
			<?php if ( ! empty( $item['link']['url'] ) ) : ?>
				<a
					href="<?php echo esc_url( $item['link']['url'] ); ?>"
					class="iar-image-grid__item"
					style="background-image: url('<?php echo esc_url( $item['background_image']['url'] ); ?>');"
					<?php echo $item['link']['is_external'] ? 'target="_blank"' : ''; ?>
					<?php echo $item['link']['nofollow'] ? 'rel="nofollow"' : ''; ?>
				>
					<div class="iar-image-grid__content">
						<?php if ( ! empty( $item['subtitle'] ) ) : ?>
							<div class="iar-image-grid__subtitle"><?php echo esc_html( $item['subtitle'] ); ?></div>
						<?php endif; ?>

						<?php if ( ! empty( $item['title'] ) ) : ?>
							<h3 class="iar-image-grid__title"><?php echo esc_html( $item['title'] ); ?></h3>
						<?php endif; ?>

						<?php if ( ! empty( $item['link_label'] ) ) : ?>
							<div class="iar-image-grid__button"><?php echo esc_html( $item['link_label'] ); ?></div>
						<?php endif; ?>
					</div>
				</a>
			<?php else : ?>
				<div class="iar-image-grid__item" style="background-image: url('<?php echo esc_url( $item['background_image']['url'] ); ?>');">
					<div class="iar-image-grid__content">
						<?php if ( ! empty( $item['subtitle'] ) ) : ?>
							<div class="iar-image-grid__subtitle"><?php echo esc_html( $item['subtitle'] ); ?></div>
						<?php endif; ?>

						<?php if ( ! empty( $item['title'] ) ) : ?>
							<h3 class="iar-image-grid__title"><?php echo esc_html( $item['title'] ); ?></h3>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>
</div>