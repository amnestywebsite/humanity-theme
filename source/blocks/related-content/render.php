<?php

if ( ! class_exists( '\Amnesty\Related_Content' ) ) {
	return;
}

if ( ! get_the_ID() ) {
	return;
}

new \Amnesty\Related_Content();
