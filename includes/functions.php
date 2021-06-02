<?php

/**
 * Query WordPress database for movie reviews related to the current post
 *
 * @return array
 */
function wrmr_get_related_movie_reviews($genre_slugs = null, $posts_per_page = 3) {
	// get current post id
	$post_id = get_the_ID();

	if (is_null($genre_slugs)) {
		// get the current post's genres
		$genres = get_the_terms($post_id, 'mbt_movie_genre');
		if (!$genres) {
			$genres = [];
		}

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
		'posts_per_page' => $posts_per_page,
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
