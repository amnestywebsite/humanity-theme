<?php

/**
 * 404 page template
 *
 * @package Amnesty\Templates
 */

get_header();

?>
<main id="main">
	<section class="section">
		<div class="container u-textCenter">
			<h1 aria-label="<?php echo esc_attr( /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/templates/t006-404/ ARIA */ __( 'Page Not Found', 'amnesty' ) ); ?>">
				<?php echo esc_html( /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/templates/t006-404/  */ __( 'Page Not Found', 'amnesty' ) ); ?>
			</h1>
			<h3><?php esc_html_e( 'The page you are looking for does not exist.', 'amnesty' ); ?></h3>
			<a class="btn btn--large btn--white" href="<?php echo esc_url( get_home_url() ); ?>"><?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/templates/t006-404/ */ esc_html_e( 'Go to the Homepage', 'amnesty' ); ?></a>
		</div>
	</section>
</main>
<?php get_footer(); ?>
