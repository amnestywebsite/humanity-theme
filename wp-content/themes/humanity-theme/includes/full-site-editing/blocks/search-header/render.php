<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_search_header_block' ) ) {
	/**
	 * Render the post search header block
	 *
	 * @param array<string,mixed> $attributes block attributes
	 * @param string              $content    block content
	 * @param WP_Block            $block      block object
	 *
	 * @return string
	 */
	function render_search_header_block( array $attributes, string $content, WP_Block $block ): string {
		/**
		 * The core/query block neither instantiates nor executes
		 * the query declared in it. For some reason, that's done
		 * in core/post-template.
		 *
		 * This means that anything you'd like to output that has
		 * or requires query context can't actually access it
		 * before core/post-template has been output. This is
		 * highly inconvenient for displaying things like this
		 * block would like to - the number of posts found, total
		 * pages, etc - as we have to essentially re-run the query.
		 *
		 * Thankfully, since we provide the query context to this
		 * block, re-execution of the query by the core/post-template
		 * block should theoretically pull it from the object cache.
		 *
		 * It's still very much not ideal, but I don't see an alternative.
		 *
		 * - @jaymcp
		 */
		$query = build_query_vars_from_query_block( $block->context['query'], get_query_var( 'paged', 1 ) );
		$query = new WP_Query( $query );

		spaceless();

		require __DIR__ . '/views/view.php';

		return endspaceless( false );
	}
}
