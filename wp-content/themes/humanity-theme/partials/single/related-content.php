<?php

/**
 * Post single partial, related content
 *
 * @package Amnesty\Partials
 */

if ( wp_validate_boolean( get_post_meta( get_the_ID(), 'disable_related_content', true ) ) ) {
	return;
}

new \Amnesty\Related_Content();
