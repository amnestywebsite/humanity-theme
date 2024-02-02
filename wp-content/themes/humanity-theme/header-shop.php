<?php

/**
 * Global partial, site header, shop area
 *
 * @package Amnesty\Partials
 */

?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php wp_title( '&bull;', true, 'right' ); ?></title>
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'shop' ); ?>>
<a class="skipLink" href="#main" tabindex="1"><?php /* translators: [front] Accessibility label for screen reader/keyboard users */ esc_html_e( 'Skip to main content', 'amnesty' ); ?></a>
<div class="overlay" aria-hidden="true"></div>
<?php

get_template_part( 'partials/pop-in' );
get_template_part( 'partials/language-selector' );
get_template_part( 'partials/navigation/desktop' );
