<?php

if ( ! function_exists( 'amnesty_limit_location_taxonomy_archive_posts_per_page' ) ) {
	/**
	 * Limit location taxonomy posts per page to 4
	 *
	 * On Location templates, we only show the most recent four posts.
	 * Additionally, we scope the results to those with the "News"
	 * category (if present).
	 *
	 * Because of this, we can disable pagination in the query,
	 * to improve performance.
	 *
	 * Further, for "Regions" and "Subregions" (the "type" termmeta),
	 * we add a flag for the Amnesty SharePoint Runner plugin, so that
	 * it can modify the query further, for allowing results from
	 * SharePoint Documents. This has no impact when the plugin is inactive.
	 *
	 * @package Amnesty\Filters\WPQuery
	 *
	 * @param WP_Query $query the query object
	 *
	 * @return void
	 */
	function amnesty_limit_location_taxonomy_archive_posts_per_page( WP_Query $query ): void {
		// only target main query on front end
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$taxonomy = get_option( 'amnesty_location_slug' ) ?: 'location';

		// only target queries on location taxonomy templates
		if ( ! $query->is_tax( $taxonomy ) ) {
			return;
		}

		$term_check_fn = 'term_exists';
		if ( function_exists( 'wpcom_vip_term_exists' ) ) {
			$term_check_fn = 'wpcom_vip_term_exists';
		}

		// "News" category doesn't exist - don't filter the results
		if ( ! $term_check_fn( 'news', 'category' ) ) {
			return;
		}

		$type = get_term_meta( get_queried_object_id(), 'type', true );

		// ignore modifications if location is a report
		if ( 'report' === $type ) {
			return;
		}

		// improve perf
		$query->set( 'no_found_rows', true );
		// scope to "News" term
		$query->set( 'category_name', 'news' );

		// set per page limit & allow SharePoint document injection on "regions"/"subregions"
		if ( 'default' !== $type ) {
			// this is to trigger an override in the SharePoint Runner plugin
			$query->set( 'SHAREPOINT_LIST_FILTERS', true );
			$query->set( 'posts_per_page', 4 );
			return;
		}

		// set per-page limit
		$query->set( 'posts_per_page', 3 );
	}
}

add_action( 'pre_get_posts', 'amnesty_limit_location_taxonomy_archive_posts_per_page' );
