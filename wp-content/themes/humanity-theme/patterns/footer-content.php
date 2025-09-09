<?php

/**
 * Title: Footer Content
 * Description: Outputs content for the footer template part
 * Slug: amnesty/footer-content
 * Inserter: yes
 */

$footer_menu_items   = amnesty_get_nav_menu_items( 'footer-navigation' );
$footer_policy_items = amnesty_get_nav_menu_items( 'footer-legal' );

$social = [
	'facebook'  => amnesty_get_option( '_social_facebook' ),
	'instagram' => amnesty_get_option( '_social_instagram' ) ? 'https://instagram.com/' . amnesty_get_option( '_social_instagram' ) : '',
	'telegram'  => amnesty_get_option( '_social_telegram' ) ? 'https://t.me/' . amnesty_get_option( '_social_telegram' ) : '',
	'twitter'   => amnesty_get_option( '_social_twitter' ) ? 'https://twitter.com/' . amnesty_get_option( '_social_twitter' ) : '',
	'youtube'   => amnesty_get_option( '_social_youtube' ),
];

$cta = [
	'title'   => amnesty_get_option( '_footer_title', 'amnesty_footer_options_page' ),
	'content' => amnesty_get_option( '_footer_content', 'amnesty_footer_options_page' ),
	'cta'     => [
		'text' => amnesty_get_option( '_footer_cta_text', 'amnesty_footer_options_page' ),
		'url'  => amnesty_get_option( '_footer_cta_url', 'amnesty_footer_options_page' ),
	],
];

$copyright = amnesty_get_option( 'copyright_info', 'amnesty_footer_options_page' );
if ( $copyright ) {
	add_filter( 'amnesty_footer_copyright', fn (): string => $copyright );
}

?>

<!-- wp:columns -->
<div class="wp-block-columns">
<?php if ( isset( $footer_menu_items['top_level'] ) ) : ?>
	<?php foreach ( $footer_menu_items['top_level'] as $item ) : ?>
	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:heading {"level":4,"className":"linkGroup-title"} -->
		<h4 class="wp-block-heading linkGroup-title"><a href="<?php echo esc_url( $item->url ?: get_permalink( $item->db_id ) ); ?>"><?php echo esc_html( $item->title ); ?></a></h4>
		<!-- /wp:heading -->

		<?php if ( isset( $footer_menu_items['children'][ $item->title ] ) ) : ?>
		<!-- wp:list -->
		<ul>
			<?php foreach ( $footer_menu_items['children'][ $item->title ] as $child ) : ?>
			<!-- wp:list-item -->
			<li><a href="<?php echo esc_url( $child->url ?: get_permalink( $item->db_id ) ); ?>" data-type="<?php echo esc_attr( $child->type ); ?>" data-id="<?php echo absint( $child->db_id ); ?>"><?php echo esc_html( $child->title ); ?></a></li>
			<!-- /wp:list-item -->
			<?php endforeach; ?>
		</ul>
		<!-- /wp:list -->
		<?php endif; ?>
	</div>
	<!-- /wp:column -->
	<?php endforeach; ?>
<?php endif; ?>

<?php if ( $cta['title'] || $cta['content'] || array_filter( $cta['cta'] ) ) : ?>
	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:heading {"level":4} -->
		<h4 class="wp-block-heading"><?php echo wp_kses_post( $cta['title'] ); ?></h4>
		<!-- /wp:heading -->

		<?php echo wp_kses_post( amnesty_string_to_paragraphs( $cta['content'] ) ); ?>

		<!-- wp:buttons -->
		<div class="wp-block-buttons">
		<?php if ( $cta['cta']['text'] && $cta['cta']['url'] ) : ?>
			<!-- wp:button {"className":"is-style-light"} -->
			<div class="wp-block-button is-style-light"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $cta['cta']['text'] ); ?>"><?php echo esc_html( $cta['cta']['text'] ); ?></a></div>
			<!-- /wp:button -->
		<?php endif; ?>
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:column -->
<?php endif; ?>
</div>
<!-- /wp:columns -->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:separator {"className":"is-style-wide"} -->
	<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
	<!-- /wp:separator -->
</div>
<!-- /wp:group -->

<!-- wp:columns -->
<div class="wp-block-columns">
<?php if ( isset( $footer_policy_items['top_level'] ) ) : ?>
	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:list {"className":"amnesty-policy-links"} -->
		<ul class="amnesty-policy-links">
		<?php foreach ( $footer_policy_items['top_level'] as $item ) : ?>
			<!-- wp:list-item -->
			<li><a href="<?php echo esc_url( $item->url ?: get_permalink( $item->db_id ) ); ?>"><?php echo esc_html( $item->title ); ?></a></li>
			<!-- /wp:list-item -->
		<?php endforeach; ?>
		</ul>
		<!-- /wp:list -->

		<!-- wp:paragraph {"className":"amnesty-copyright"} -->
		<p class="amnesty-copyright">&copy; <?php echo esc_html( apply_filters( 'amnesty_footer_copyright', sprintf( '%s %s', gmdate( 'Y' ), get_bloginfo( 'title' ) ) ) ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:column -->
<?php endif; ?>

<?php if ( array_filter( $social ) ) : ?>
	<!-- wp:column {"className":"amnesty-social-container"} -->
	<div class="wp-block-column amnesty-social-container">
		<!-- wp:group {"className":"amnesty-social-group","layout":{"type":"constrained"}} -->
		<div class="wp-block-group amnesty-social-group">
			<!-- wp:heading {"textAlign":"right"} -->
			<h2 class="wp-block-heading has-text-align-right"><?php /* translators: [front] Social Media sharing options */ esc_html_e( 'Follow us on:', 'amnesty' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:social-links -->
			<ul class="wp-block-social-links">
				<?php foreach ( $social as $platform => $the_link ) : ?>
				<!-- wp:social-link {"url":"<?php echo esc_url( $the_link ); ?>","service":"<?php echo esc_attr( $platform ); ?>","className":"social-<?php echo esc_attr( $platform ); ?>"} /-->
				<?php endforeach; ?>
			</ul>
			<!-- /wp:social-links -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:column -->
<?php endif; ?>
</div>
<!-- /wp:columns -->
