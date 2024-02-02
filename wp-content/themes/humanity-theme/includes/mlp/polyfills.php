<?php

declare( strict_types = 1 );

add_action(
	'admin_enqueue_scripts',
	function () {
		/**
		 * MLP JS still references the archaic `.live` event handler,
		 * which is incompatible with WP v5.6, and results in breakages.
		 * This will backfill `.live` with a call to `.on` (its successor),
		 * so that features such as the Language Manager works correctly.
		 * It is imperative that the Language Manager operates, as otherwise
		 * Arabic sites that don't specify a locale (e.g. Arabic (Somewhere))
		 * do not function correctly without defining them within the Language
		 * Manager.
		 */
		wp_add_inline_script(
			'multilingualpress-language-manager',
			'jQuery.fn.extend({live:function(types,data,callback){this.selector&&jQuery(document).on(types,this.selector,data,callback);return this}})',
			'before'
		);
	} 
);
