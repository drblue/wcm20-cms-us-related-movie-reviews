<?php

/**
 * Include widget class(es)
 */
require(WRMR_PLUGIN_DIR . 'includes/class.RelatedMovieReviewsWidget.php');

/**
 * Register widget(s)
 */
function wrmr_widgets_init() {
	register_widget('RelatedMovieReviewsWidget');
}
add_action('widgets_init', 'wrmr_widgets_init');
