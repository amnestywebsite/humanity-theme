<?php
/**
 * Title: 404 Page Content
 * Description: Translatable content for the 404 page.
 * Slug: amnesty/404
 * Inserter: no
 */
?>

<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="wp-block-heading has-text-align-center"><?php echo esc_html( /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/templates/t006-404/  */ __( 'Page Not Found', 'amnesty' ) ); ?></h1>
<!-- /wp:heading -->

<!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'The page you are looking for does not exist.', 'amnesty' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( get_home_url() ); ?>"><?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/templates/t006-404/ */ esc_html_e( 'Go to the Homepage', 'amnesty' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
