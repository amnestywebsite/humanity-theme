<?php

/**
 * Global partial, site footer
 *
 * @package Amnesty\Partials
 */

$twitter   = amnesty_get_option( '_social_twitter' );
$facebook  = amnesty_get_option( '_social_facebook' );
$instagram = amnesty_get_option( '_social_instagram' );
$youtube   = amnesty_get_option( '_social_youtube' );
$telegram  = amnesty_get_option( '_social_telegram' );

$has_social = $twitter || $facebook || $instagram || $youtube || $telegram;

$footer_section = [
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

$sites = amnesty_get_sites();

?>
	<footer class="page-footer" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Footer', 'amnesty' ); ?>">
		<div class="container">
		<?php spaceless(); ?>
			<ul class="page-footerSections" aria-label="<?php /* translators: [front] ARIA */esc_attr_e( 'Footer Menus', 'amnesty' ); ?>">
			<?php amnesty_nav( 'footer-navigation' ); ?>

			<?php if ( $footer_section['title'] || $footer_section['content'] || array_filter( $footer_section['cta'] ) ) : ?>
				<li class="page-footerSection page-footerSection--large">
				<?php if ( $footer_section['title'] ) : ?>
					<a><?php echo wp_kses_post( $footer_section['title'] ); ?></a>
				<?php endif; ?>

				<?php if ( $footer_section['content'] ) : ?>
					<?php echo wp_kses_post( wpautop( $footer_section['content'] ) ); ?>
				<?php endif; ?>

				<?php if ( $footer_section['cta']['text'] && $footer_section['cta']['url'] ) : ?>
					<a class="btn btn--white" href="<?php echo esc_url( $footer_section['cta']['url'] ); ?>" aria-label="<?php echo esc_attr( format_for_aria_label( $footer_section['cta']['text'] ) ); ?>"><?php echo esc_html( $footer_section['cta']['text'] ); ?></a>
				<?php endif; ?>
				</li>
			<?php endif; ?>
			</ul>
		<?php endspaceless(); ?>

			<div class="page-footerBottom">
				<section class="page-footerBottomHalf">
				<?php if ( amnesty_nav_should_display( 'footer-legal' ) ) : ?>
					<nav class="page-footerBottomNav">
						<ul><?php amnesty_nav( 'footer-legal' ); ?></ul>
					</nav>
				<?php endif; ?>
					<span class="page-footerCopyright">&copy; <?php echo esc_html( apply_filters( 'amnesty_footer_copyright', sprintf( '%s %s', gmdate( 'Y' ), get_bloginfo( 'title' ) ) ) ); ?></span>
				</section>
			<?php if ( $has_social ) : ?>
				<section class="page-footerBottomHalf page-footerSocialContainer">
					<h2 class="page-footerBottomTitle" aria-label="<?php /* translators: [front] ARIA Social Media sharing options */ esc_attr_e( 'Follow us on:', 'amnesty' ); ?>"><?php /* translators: [front] Social Media sharing options */ esc_html_e( 'Follow us on:', 'amnesty' ); ?> </h2>
					<ul class="page-footerSocial">
					<?php if ( $facebook ) : ?>
						<li><a target="_blank" rel="noreferrer noopener" href="<?php echo esc_url( $facebook ); ?>" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Follow us on Facebook', 'amnesty' ); ?>"><span class="social-facebook">Facebook</span></a></li>
					<?php endif; ?>
					<?php if ( $twitter ) : ?>
						<li><a target="_blank" rel="noreferrer noopener" href="https://twitter.com/<?php echo esc_attr( str_replace( '@', '', $twitter ) ); ?>" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Follow us on Twitter', 'amnesty' ); ?>"><span class="social-twitter">Twitter</span></a></li>
					<?php endif; ?>
					<?php if ( $youtube ) : ?>
						<li><a target="_blank" rel="noreferrer noopener" href="<?php echo esc_url( $youtube ); ?>" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Subscribe to our YouTube channel', 'amnesty' ); ?>"><span class="social-youtube">YouTube</span></a></li>
					<?php endif; ?>
					<?php if ( $instagram ) : ?>
						<li><a target="_blank" rel="noreferrer noopener" href="https://instagram.com/<?php echo esc_attr( str_replace( '@', '', $instagram ) ); ?>" aria-label="<?php /* translators: [front] */ esc_attr_e( 'Follow us on Instagram', 'amnesty' ); ?>"><span class="social-instagram">Instagram</span></a></li>
					<?php endif; ?>
					<?php if ( $telegram ) : ?>
						<li><a target="_blank" rel="noreferrer noopener" href="https://t.me/<?php echo esc_attr( str_replace( '@', '', $telegram ) ); ?>" aria-label="<?php /* translators: [front] */ esc_attr_e( 'Follow us on Telegram', 'amnesty' ); ?>"><span class="social-telegram">Telegram</span></a></li>
					<?php endif; ?>
					</ul>
				</section>
			<?php endif; ?>
			</div>
		</div>
	</footer>
	<?php wp_footer(); ?>
	<?php /* phpcs:ignore WordPress.WP.CapitalPDangit */ ?>
	<script>(function(w,C,c){w[C]&&w[C].get(c)&&-1!==w[C].get(c).indexOf('wordpress')&&w[C].remove(c)}(window,'Cookies','CookieControl'))</script>
	</body>
</html>
