<?php
echo '<pre>';
var_dump($content);
echo '</pre>';
?>

<aside id="pop-in" class="u-textCenter pop-in is-closed">
	<button id="pop-in-close" class="pop-in-close">X</button>
	<div class="section section--small">
		<div class="container container--small">
			<?php echo $content; ?>
		</div>
	</div>
</aside>
