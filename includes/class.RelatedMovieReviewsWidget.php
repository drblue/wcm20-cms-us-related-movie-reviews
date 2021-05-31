<?php

class RelatedMovieReviewsWidget extends WP_Widget {

	const DEFAULT_NBR_POSTS_TO_SHOW = 3;

	/**
	 * Construct a new widget instance.
	 */
	public function __construct() {
		parent::__construct(
			'wcm20-related-movie-reviews-widget', // Base ID
			'Related Movie Reviews', // Name
			[
				'description' => 'Widget for displaying related movie reviews.',
			]
		);
	}

	/**
	 * Front-end display of the widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved option values for this specific instance of the widget.
	 * @return void
	 */
	public function widget($args, $instance) {
		if (!(is_single() && get_post_type() === 'mbt_movie_review')) {
			return;
		}

		// start widget
		echo $args['before_widget'];

		// render title
		if (!empty($instance['title'])) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}

		$nbr_posts = $instance['nbr_posts'] ?? self::DEFAULT_NBR_POSTS_TO_SHOW;
		$reviews = wrmr_get_related_movie_reviews(null, $nbr_posts);
		if (count($reviews) > 0) {
			$output = '<ul class="related-movie-reviews-list">';

			foreach ($reviews as $review) {
				$output .= sprintf(
					'<li class="related-movie-review">
						<a href="%s">%s</a>
					</li>',
					$review['permalink'],
					$review['title'],
				);
			}

			$output .= "</ul>";
		} else {
			$output = "<p><em>Sorry, no related movie reviews found.</em></p>";
		}

		// output latest posts
		echo $output;

		// end widget
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Current saved values for this instance of the widget.
	 * @return void
	 */
	public function form($instance) {

		// do we have a title set? if so, use it, otherwise set empty title
		$title = isset($instance['title'])
			? $instance['title']
			: get_option('wrmr_default_title', __('Related Reviews', 'wrmr'));

		// do we have number of posts to show set? if so, use it, otherwise get option and default to 3
		$nbr_posts = isset($instance['nbr_posts'])
			? $instance['nbr_posts']
			: get_option('wrmr_posts_to_show', self::DEFAULT_NBR_POSTS_TO_SHOW);

		?>
			<!-- title -->
			<p>
				<label for="<?php echo $this->get_field_id('title') ?>">Title:</label>

				<input
					class="widefat"
					id="<?php echo $this->get_field_id('title') ?>"
					name="<?php echo $this->get_field_name('title') ?>"
					type="text"
					value="<?php echo $title; ?>"
				>
			</p>

			<!-- nbr_posts -->
			<p>
				<label for="<?php echo $this->get_field_id('nbr_posts') ?>">Posts to show:</label>

				<input
					class="widefat"
					id="<?php echo $this->get_field_id('nbr_posts') ?>"
					name="<?php echo $this->get_field_name('nbr_posts') ?>"
					type="number"
					value="<?php echo $nbr_posts; ?>"
				>
			</p>
		<?php
	}

	/**
	 * Sanitize widget form data before they are saved to the database.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Form values just sent to be saved.
	 * @param array $old_instance Currently saved values.
	 * @return void
	 */
	public function update($new_instance, $old_instance) {
		$instance = [];

		$instance['title'] = (!empty($new_instance['title']))
			? strip_tags($new_instance['title'])
			: '';

		$instance['nbr_posts'] = (!empty($new_instance['nbr_posts']))
			? $new_instance['nbr_posts']
			: get_option('wrmr_posts_to_show', self::DEFAULT_NBR_POSTS_TO_SHOW);

		return $instance;
	}
}
