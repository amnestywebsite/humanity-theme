<?php

if ( ! class_exists( '\Amnesty\Related_Content' ) ) {
	return '';
}

if ( ! get_the_ID() ) {
	return '';
}

$related_content = new \Amnesty\Related_Content( false );

echo wp_kses_post( $related_content->get_rendered() );
