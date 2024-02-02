<?php

/**
 * Navigation partial, mobile
 *
 * @package Amnesty\Partials
 */

?>

<div id="mobile-menu" class="mobile-menu" aria-hidden="true" aria-modal="true">
	<ul>
		<?php amnesty_nav( 'main-menu', new \Amnesty\Mobile_Nav_Walker() ); ?>
	</ul>
</div>
