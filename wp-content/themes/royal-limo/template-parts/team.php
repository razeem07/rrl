<?php
/**
 * About page "Key Persons" section — eyebrow/heading/description, then
 * a grid of team_member CPT cards (photo, name, role, social links
 * revealed on hover). Content set via Customizer > Key Persons Section
 * and the Team Members admin screen.
 */
$team_query = new WP_Query( array(
	'post_type'      => 'team_member',
	'posts_per_page' => 8,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
$team_header = royal_limo_team_section();
?>
<section class="rl-team rl-section" id="team">
	<div class="container">
		<div class="rl-section__header rl-reveal">
			<p class="rl-eyebrow"><?php echo esc_html( $team_header['eyebrow'] ); ?></p>
			<h2><?php echo esc_html( $team_header['heading'] ); ?></h2>
			<p><?php echo esc_html( $team_header['description'] ); ?></p>
		</div>

		<?php if ( $team_query->have_posts() ) : ?>
			<div class="rl-grid rl-grid--3">
				<?php
				while ( $team_query->have_posts() ) :
					$team_query->the_post();
					$role    = get_post_meta( get_the_ID(), '_team_role', true );
					$socials = array(
						'facebook'  => get_post_meta( get_the_ID(), '_team_facebook', true ),
						'twitter'   => get_post_meta( get_the_ID(), '_team_twitter', true ),
						'linkedin'  => get_post_meta( get_the_ID(), '_team_linkedin', true ),
						'instagram' => get_post_meta( get_the_ID(), '_team_instagram', true ),
					);
					?>
					<div class="rl-team-card rl-reveal">
						<div class="rl-team-card__media">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'fleet-card', array( 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
							<?php else : ?>
								<span class="rl-team-card__initial" aria-hidden="true"><?php echo esc_html( mb_substr( get_the_title(), 0, 1 ) ); ?></span>
							<?php endif; ?>

							<?php if ( array_filter( $socials ) ) : ?>
								<div class="rl-team-card__socials">
									<?php foreach ( $socials as $network => $url ) : ?>
										<?php if ( $url ) : ?>
											<a href="<?php echo esc_url( $url ); ?>" class="rl-icon-tile" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>">
												<?php echo esc_html( strtoupper( substr( $network, 0, 1 ) ) ); ?>
											</a>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
						<h3 class="rl-card__title"><?php the_title(); ?></h3>
						<?php if ( $role ) : ?>
							<p class="rl-team-card__role"><?php echo esc_html( $role ); ?></p>
						<?php endif; ?>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'Team members will appear here once added under Team Members in wp-admin.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>
