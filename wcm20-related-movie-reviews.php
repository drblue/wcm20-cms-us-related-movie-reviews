<?php
/**
 * Plugin Name:	WCM20 Related Movie Reviews
 * Description:	This plugin adds a shortcode to display related movie reviews
 * Version:		0.1
 * Author:		Johan Nordström
 * Author URI:	https://www.thehiveresistance.com
 * Text Domain:	wrmr
 * Domain Path:	/languages
 */

define('WRMR_SHORTCODE_TAG', 'related-movie-reviews');

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

/**
 * Query WordPress database for movie reviews related to the current post
 *
 * @return array
 */
function wrmr_get_related_movie_reviews($genre_slugs) {
	// get current post id
	$post_id = get_the_ID();

	if (is_null($genre_slugs)) {
		// get the current post's genres
		$genres = get_the_terms($post_id, 'mbt_movie_genre');

		// transform genres to simple array of slugs
		$genre_slugs = array_map(function($genre) {
			return $genre->slug;
		}, $genres);
	} else {
		// extract genre slugs from string
		$genre_slugs = explode(',', $genre_slugs);
	}

	// query for related movie reviews
	$query = new WP_Query([
		'posts_per_page' => 3,
		'post_type' => 'mbt_movie_review',
		'post__not_in' => [$post_id],
		'tax_query' => [
			[
				'taxonomy' => 'mbt_movie_genre',
				'field' => 'slug',
				'terms' => $genre_slugs,
			],
		],
	]);

	$reviews = [];
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();

			$review = [
				'title' => get_the_title(),
				'excerpt' => get_the_excerpt(),
				'permalink' => get_the_permalink(),
				'thumbnail_id' => get_post_thumbnail_id(),
			];

			array_push($reviews, $review);
		}
		wp_reset_postdata();
	}

	return $reviews;
}
