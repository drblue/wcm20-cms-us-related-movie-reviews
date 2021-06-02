<?php

/**
 * Filter the content and append Related Movie Reviews
 * if setting is active and current post is single review.
 *
 * @param string $content
 * @return string
 */
function wrmr_filter_the_content($content) {
	if (!is_admin() && is_single() && get_post_type() === 'mbt_movie_review' && get_option('wrmr_add_to_posts')) {
		if (!has_shortcode($content, WRMR_SHORTCODE_TAG)) {
			$content .= wrmr_shortcode();
		}
	}

	return $content;
}
add_filter('the_content', 'wrmr_filter_the_content');
