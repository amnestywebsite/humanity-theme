<?php

/**
 * Global partial, language selector bar
 *
 * @package Amnesty\Partials
 */

$sites = amnesty_get_sites();

if ( count( $sites ) < 2 || ! amnesty_feature_is_enabled( 'language-selector' ) ) {
	return;
}

?>

<aside class="language-selector" aria-label="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m005-language-selector/ */ esc_attr_e( 'Language Selection', 'amnesty' ); ?>">
	<div class="container">
		<p><?php /* translators: [front] */ esc_html_e( 'Which language would you like to use this site in?', 'amnesty' ); ?></p>
		<ul class="language-list" aria-label="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m005-language-selector/ */ esc_attr_e( 'Choose a language', 'amnesty' ); ?>">
			<?php foreach ( $sites as $site ) : ?>
				<li class="<?php echo esc_attr( $site->current ? 'is-selected' : '' ); ?>" style="direction:<?php echo esc_attr( $site->direction ); ?>"><a href="<?php echo esc_url( $site->url ); ?>"><?php echo esc_html( $site->lang ); ?></a></li>
			<?php endforeach; ?>
		</ul>
		<button class="btn btn--white language-selectorClose">
			<span><?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m005-language-selector/ */ esc_html_e( 'Close', 'amnesty' ); ?></span>
		</button>
	</div>
</aside>
