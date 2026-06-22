<?php

/**
 * Title: Locations Taxonomy offices
 * Description: Outputs office contact info for a location
 * Slug: amnesty/taxonomy-location-offices
 * Inserter: no
 */

$location_object = get_queried_object();
$template_type   = 'default';
$media_contact   = null;
$office_contacts = null;

if ( is_a( $location_object, WP_Term::class ) ) {
	$template_type   = get_term_meta( get_queried_object_id(), 'type', true );
	$media_contact   = amnesty_get_regional_media_contact();
	$office_contacts = amnesty_get_offices_for_term(); // draft posts are here, too????
}

$is_office_contact = amnesty_get_is_contact_info();

$subregion_term = false;
$subregion_link = false;
if ( amnesty_location_is_region() ) {
	$subregion_term = amnesty_get_office_subregion();

	if ( $subregion_term ) {
		$subregion_link = amnesty_term_link( $subregion_term );
	}
}

do_action( 'amnesty_location_template_before_offices', $template_type );
// location of this action moved - kept named for historical reasons
do_action( 'amnesty_location_template_after_offices', $template_type );

?>

<!-- wp:group {"tagName":"section","className":"section section--small"} -->
<section class="wp-block-group section section--small">
	<!-- wp:group {"tagName":"div","className":"container has-gutter u-flex"} -->
	<div class="wp-block-group container has-gutter u-flex">
		<!-- wp:group {"tagName":"div","className":"officeList"} -->
		<div class="wp-block-group officeList">
		<?php if ( $media_contact ) : ?>
			<!-- wp:group {"tagName":"div","className":"post-content"} -->
			<div class="wp-block-group post-content">
				<!-- wp:heading {"level":1,"className":"address-title"} -->
				<h1 class="wp-block-heading address-title"><?php /* translators: [front] Countries Locations Page */ echo esc_html_x( 'Media Enquiries', 'Contact details heading', 'aitc' ); ?></h1>
				<!-- /wp:heading -->

				<!-- wp:group {"tagName":"div","className":"contactInfo"} -->
				<div class="wp-block-group contactInfo">
				<?php if ( strlen( $media_contact['name'] ) > 0 ) : ?>
					<!-- wp:heading {"level":4} -->
					<h4><?php echo esc_html( $media_contact['name'] ); ?></h4>
					<!-- /wp:heading -->
				<?php endif; ?>
					<!-- wp:paragraph -->
					<p class="wp-block-paragraph">
					<?php

					if ( $media_contact['title'] || $media_contact['phone'] ) {
						echo esc_html( implode( ', ', array_filter( [ $media_contact['title'], $media_contact['phone'] ] ) ) );
						echo '<br>';
					}

					if ( $media_contact['email'] ) {
						printf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $media_contact['email'] ) );
					}

					?>
					</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		<?php endif; ?>

		<?php if ( $office_contacts?->have_posts() || $is_office_contact ) : ?>
			<!-- wp:group {"tagName":"div","className":"post-content"} -->
			<div class="wp-block-group post-content">
			<?php if ( ! empty( $office_contacts->post->post_title ) ) : ?>
				<!-- wp:heading {"level":1,"className":"address-title"} -->
				<h1 class="wp-block-heading address-title"><?php /* translators: [front] Countries Locations Page */ echo esc_html_x( 'Our Offices', 'shown above region office address', 'aitc' ); ?></h1>
				<!-- /wp:heading -->
			<?php endif; ?>
			<?php if ( $office_contacts?->have_posts() ) : ?>
				<!-- wp:group {"tagName":"div","className":"officeList multipleOffices"} -->
				<div class="wp-block-group officeList multipleOffices">
				<?php while ( $office_contacts->have_posts() ) : ?>
					<?php

					$office_contacts->the_post();

					$address = get_post_meta( get_the_ID(), 'address', true );
					$phone   = get_post_meta( get_the_ID(), 'phone', true );
					$email   = get_post_meta( get_the_ID(), 'email', true );
					$website = get_post_meta( get_the_ID(), 'website', true );

					?>

					<!-- wp:group {"tagName":"div","className":"post-content"} -->
					<div class="wp-block-group post-content">
						<!-- wp:heading {"level":4,"className":"addressTitle"} -->
						<h4 class="wp-block-heading addressTitle"><?php the_title(); ?></h4>
						<!-- /wp:heading -->
						<!-- wp:group {"tagName":"div","className":"contactInfo"} -->
						<div class="wp-block-group contactInfo">
							<!-- wp:group {"tagName":"div"} -->
							<div class="wp-block-group">
							<?php if ( $address ) : ?>
								<!-- wp:heading {"level":4} -->
								<h4 class="wp-block-heading" style="margin-block-end:0"><?php /* translators: [front] */ esc_html_e( 'Address', 'aitc' ); ?></h4>
								<!-- /wp:heading -->
								<!-- wp:paragraph -->
								<p><?php echo wp_kses_post( nl2br( get_post_meta( get_the_ID(), 'address', true ) ) ); ?></p>
								<!-- /wp:paragraph -->
							</div>
							<!-- /wp:group -->
							<?php endif; ?>
							<?php if ( $phone ) : ?>
							<!-- wp:group {"tagName":"div"} -->
							<div class="wp-block-group">
								<!-- wp:heading {"level":4} -->
								<h4 class="wp-block-heading" style="margin-block-end:0"><?php /* translators: [front] */ esc_html_e( 'Phone', 'aitc' ); ?></h4>
								<!-- /wp:heading -->
								<!-- wp:paragraph -->
								<p><?php echo wp_kses_post( get_post_meta( get_the_ID(), 'phone', true ) ); ?></p>
								<!-- /wp:paragraph -->
							</div>
							<!-- /wp:group -->
							<?php endif; ?>
							<?php if ( $email ) : ?>
							<!-- wp:group {"tagName":"div"} -->
							<div class="wp-block-group">
								<!-- wp:heading {"level":4} -->
								<h4 class="wp-block-heading" style="margin-block-end:0"><?php /* translators: [front] */ esc_html_e( 'Email', 'aitc' ); ?></h4>
								<!-- /wp:heading -->
								<!-- wp:paragraph -->
								<p><a href="mailto:<?php echo esc_attr( get_post_meta( get_the_ID(), 'email', true ) ); ?>"><?php echo esc_html( get_post_meta( get_the_ID(), 'email', true ) ); ?></a></p>
								<!-- /wp:paragraph -->
							</div>
							<!-- /wp:group -->
							<?php endif; ?>
							<?php if ( $website ) : ?>
							<!-- wp:group {"tagName":"div"} -->
							<div class="wp-block-group">
								<!-- wp:heading {"level":4} -->
								<h4 class="wp-block-heading" style="margin-block-end:0"><?php /* translators: [front] */ esc_html_e( 'Website', 'aitc' ); ?></h4>
								<!-- /wp:heading -->
								<!-- wp:paragraph -->
								<p><a href="<?php echo esc_url( get_post_meta( get_the_ID(), 'website', true ) ); ?>"><?php /* translators: [front] */ esc_html_e( 'Go to section website', 'aitc' ); ?></a></p>
								<!-- /wp:paragraph -->
							</div>
							<!-- /wp:group -->
							<?php endif; ?>
						<?php if ( $subregion_term && $subregion_link ) : ?>
							<!-- wp:group {"tagName":"div"} -->
							<div class="wp-block-group">
								<!-- wp:buttons -->
								<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-light"} -->
								<div class="wp-block-button is-style-light"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $subregion_link ); ?>">
								<span><?php echo esc_html( sprintf( /* translators: [front] %s: location name */ _x( 'View %s', 'Link label', 'aitc' ), $subregion_term->name ) ); ?></span></a></div>
								<!-- /wp:button --></div>
								<!-- /wp:buttons -->
							</div>
							<!-- /wp:group -->
						<?php endif; ?>
						</div>
						<!-- /wp:group -->
					</div>
					<!-- /wp:group -->

				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
				</div>
				<!-- /wp:group -->
			<?php endif; ?>

			<?php if ( $is_office_contact ) : ?>
				<!-- wp:group {"tagName":"div","className":"contactInfo"} -->
				<div class="wp-block-group contactInfo">
				<?php if ( ! empty( $is_office_contact['name'] ) ) : ?>
					<!-- wp:heading {"level":4} -->
					<h4 class="wp-block-heading"><?php echo esc_html( $is_office_contact['name'] ); ?></h4>
					<!-- /wp:heading -->
				<?php endif; ?>

				<?php if ( ! empty( $is_office_contact['page'] ) ) : ?>
					<!-- wp:group {"tagName":"div"} -->
					<div class="wp-block-group">
						<!-- wp:paragraph -->
						<p class="wp-block-paragraph">
							<a class="btn btn--white" href="<?php echo esc_url( get_permalink( $is_office_contact['page'] ) ); ?>">
								<?php /* translators: [front] Countries Locations */ echo esc_html_x( 'View all contact info', 'international contact button', 'aitc' ); ?>
							</a>
						</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				<?php endif; ?>
				</div>
				<!-- /wp:group -->
			<?php endif; ?>
			</div>
			<!-- /wp:group -->
		<?php endif; ?>
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
