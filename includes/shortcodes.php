<?php

/**
 * Register plugin shortcodes
 */
function wrmr_init() {
	// Register shortcode
	add_shortcode(WRMR_SHORTCODE_TAG, 'wrmr_shortcode');
}
add_action('init', 'wrmr_init');

/**
 * Parse shortcode
 *
 * @param array $user_atts Attributes passed in shortcode
 * @param mixed $content Content inside shortcode
 * @param string $tag Shortcode tag (name). Default empty.
 * @return string
 */
function wrmr_shortcode($user_atts = [], $content = null, $tag = '') {

	// change all attribute keys to lowercase
	$user_atts = array_change_key_case((array)$user_atts, CASE_LOWER);

	$default_atts = [
		'genres' => null,
		'title' => __('Related Movie Reviews', 'wrmr'),
	];

	$atts = shortcode_atts($default_atts, $user_atts, WRMR_SHORTCODE_TAG);

	// Add title to output
	$output = sprintf('<h2 class="related-movie-reviews-heading">%s</h2>', $atts['title']);

	// $atts['genres'] = null|"genre,genre"
	// $output .= "<pre>" . print_r($atts, true) . "</pre>";

	$reviews = wrmr_get_related_movie_reviews($atts['genres']);
	if (!empty($reviews)) {
		$output .= '<div class="related-movie-reviews">';

		foreach ($reviews as $review) {
			$output .= sprintf(
				'<div class="related-movie-review">
					<a href="%s">
						<img src="%s">
						<h3>%s</h3>
					</a>
					<p>%s</p>
				</div>',
				$review['permalink'],
				wp_get_attachment_image_url($review['thumbnail_id'], 'medium'),
				$review['title'],
				$review['excerpt']
			);
		}

		$output .= '</div>';
	}

	return $output;
}
