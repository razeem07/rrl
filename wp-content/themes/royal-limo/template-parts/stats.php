<?php
/**
 * Stats / why-choose-us counters.
 */
$stats = array(
	array( 'number' => 15, 'suffix' => '+', 'label' => __( 'Years in Business', 'royal-limo' ) ),
	array( 'number' => 40, 'suffix' => '+', 'label' => __( 'Vehicles in Fleet', 'royal-limo' ) ),
	array( 'number' => 12000, 'suffix' => '+', 'label' => __( 'Rides Completed', 'royal-limo' ) ),
	array( 'number' => 25, 'suffix' => '', 'label' => __( 'Cities Served', 'royal-limo' ) ),
);
?>
<section class="rl-stats-section rl-section" id="why-us">
	<div class="container">
		<div class="rl-stats rl-reveal">
			<?php foreach ( $stats as $stat ) : ?>
				<div class="rl-stat">
					<div class="rl-stat__number" data-rl-counter data-target="<?php echo esc_attr( $stat['number'] ); ?>" data-suffix="<?php echo esc_attr( $stat['suffix'] ); ?>"><?php echo esc_html( number_format_i18n( $stat['number'] ) . $stat['suffix'] ); ?></div>
					<div class="rl-stat__label"><?php echo esc_html( $stat['label'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
