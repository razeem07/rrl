<?php
/**
 * Video Showcase section — eyebrow/heading/description/checklist, a
 * showcase image with an optional play button (opens a lightbox — see
 * assets/js/main.js), and a "brand_logo" CPT marquee across the bottom
 * of the image. Content set via Customizer > Video Showcase Section.
 */
$video = royal_limo_video_showcase();

$logos_query = new WP_Query( array(
	'post_type'      => 'brand_logo',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
$logos = $logos_query->posts;
?>
<section class="rl-video-showcase rl-section rl-video-showcase--full-bleed" id="video-showcase">
		<div class="rl-video-showcase__panel">
			<div class="rl-video-showcase__copy" data-rl-cascade>
				<span class="rl-pill-badge">
					<span class="rl-pill-badge__dot" aria-hidden="true"></span>
					<?php echo esc_html( $video['eyebrow'] ); ?>
				</span>
				<h2><?php echo esc_html( $video['heading'] ); ?></h2>
				<p><?php echo esc_html( $video['description'] ); ?></p>

				<div class="rl-video-showcase__checks">
					<?php foreach ( array( $video['check1'], $video['check2'] ) as $check ) : ?>
						<?php if ( $check ) : ?>
							<span class="rl-video-showcase__check">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
								<?php echo esc_html( $check ); ?>
							</span>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="rl-video-card" data-rl-cascade <?php if ( $video['image_url'] ) : ?>style="background-image: linear-gradient(180deg, rgba(10,10,10,.1), rgba(10,10,10,.5)), url('<?php echo esc_url( $video['image_url'] ); ?>');"<?php endif; ?>>
				<?php if ( $video['video_url'] ) : ?>
					<div class="rl-video-card__bg" data-rl-video-bg data-video-url="<?php echo esc_url( $video['video_url'] ); ?>" aria-hidden="true"></div>
					<button type="button" class="rl-video-card__play" data-rl-video-play data-video-url="<?php echo esc_url( $video['video_url'] ); ?>" aria-label="<?php esc_attr_e( 'Play video with sound', 'royal-limo' ); ?>">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
					</button>
				<?php endif; ?>

				<?php if ( $logos ) : ?>
					<div class="rl-brand-marquee">
						<div class="rl-brand-marquee__track">
							<?php
							// Rendered twice in sequence — standard seamless-loop
							// marquee technique (see assets/css/layout.css).
							for ( $pass = 0; $pass < 2; $pass++ ) :
								foreach ( $logos as $logo ) :
									$logo_img = get_the_post_thumbnail( $logo->ID, 'medium', array( 'loading' => 'lazy', 'alt' => get_the_title( $logo ) ) );
									if ( ! $logo_img ) {
										continue;
									}
									?>
									<span class="rl-brand-marquee__logo"><?php echo $logo_img; ?></span>
									<?php
								endforeach;
							endfor;
							?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
</section>
