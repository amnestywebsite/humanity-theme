<?php

/**
 * Navigation partial, desktop
 *
 * @package Amnesty\Partials
 */

$header_style = amnesty_get_header_style( amnesty_get_header_object_id() );
$donate_url   = amnesty_get_option( '_header_donation' );
$donate_txt   = amnesty_get_option( '_header_donation_label' );

$menu_item_count = count_top_level_menu_items();
$menu_item_lang  = strtolower( preg_replace( '/^(\w{2})[-_]\w{2}$/', '$1', get_locale() ) );

?>
<header class="page-header is-<?php echo esc_attr( $header_style ); ?>" role="banner" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Page Header', 'amnesty' ); ?>">
	<div class="container">
		<div class="page-headerItems">
		<?php amnesty_logo(); ?>

		<?php if ( is_multilingualpress_enabled() ) : ?>
			<?php render_language_selector_dropdown(); ?>
		<?php elseif ( amnesty_nav_should_display( 'site-selection' ) ) : ?>
			<nav class="page-nav page-nav--left" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Amnesty Group Websites', 'amnesty' ); ?>">
				<ul class="site-selector">
					<?php amnesty_nav( 'site-selection' ); ?>
					<div class="site-separator"></div>
				</ul>
			</nav>
		<?php endif; ?>

			<nav class="page-nav page-nav--main" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Primary navigation', 'amnesty' ); ?>">
				<ul><?php amnesty_nav( 'main-menu' ); ?></ul>
				<button
					class="burger"
					data-toggle=".mobile-menu"
					data-state="mobile-menu-open"
					data-focus=".mobile-menu > ul"
					aria-expanded="false"
					aria-controls="mobile-menu"
					aria-label="<?php /* translators: [front] */ esc_attr_e( 'Open navigation', 'amnesty' ); ?>"
				><span class="icon icon-burger"></span><span class="icon icon-close"></span></button>
				<?php get_template_part( 'partials/navigation/mobile' ); ?>
			</nav>
		</div>
	</div>
</header>
