<?php

/**
 * Global partial, site header
 *
 * @package Amnesty\Partials
 */

?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php

get_template_part( 'partials/language-selector' );
get_template_part( 'partials/navigation/desktop' );
get_template_part( 'partials/header' ); // old
get_template_part( 'partials/hero' );   // new
