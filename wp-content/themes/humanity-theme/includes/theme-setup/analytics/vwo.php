<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_output_vwo' ) ) {
	/**
	 * Visual Website Optimizer
	 *
	 * @package Amnesty\ThemeSetup\Analytics
	 *
	 * @return void
	 */
	function amnesty_output_vwo(): void {
		if ( is_admin() ) {
			return;
		}

		$vwo = amnesty_get_option( '_analytics_vwo', 'amnesty_analytics_options_page' );

		if ( empty( $vwo ) ) {
			return;
		}

		wp_enqueue_script( 'vwo', '', [], '1.0.0', false );
		wp_add_inline_script( 'vwo', "var _vwo_code=(function(d){var f=0,h=d.getElementsByTagName('head')[0];return{use_existing_jquery:function(){return !0},library_tolerance:function(){return 2500},finish:function(){if(!f){f=!0;var a=d.getElementById('_vis_opt_path_hides');if(a)a.parentNode.removeChild(a)}},finished:function(){return f},load:function(a){var b=d.createElement('script');b.src=a;b.innerText;b.onerror=function(){_vwo_code.finish()};h.appendChild(b)},init:function(){var t=setTimeout('_vwo_code.finish()',2000);this.load('//dev.visualwebsiteoptimizer.com/j.php?a=vwo&u='+encodeURIComponent(d.URL)+'&r='+Math.random());var a=d.createElement('style'),b='body{opacity:0!important;filter:alpha(opacity=0)!important;background:none!important}';a.setAttribute('id','_vis_opt_path_hides');if(a.styleSheet)a.styleSheet.cssText=b;else a.appendChild(d.createTextNode(b));h.appendChild(a);return t}};}(document));_vwo_settings_timer=_vwo_code.init();", 'before' );
	}
}

add_action( 'init', 'amnesty_output_vwo' );
