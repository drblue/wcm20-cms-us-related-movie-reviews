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

define('WRMR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WRMR_SHORTCODE_TAG', 'related-movie-reviews');

/**
 * Include dependencies.
 */
require_once(WRMR_PLUGIN_DIR . 'includes/functions.php');
require_once(WRMR_PLUGIN_DIR . 'includes/filters.php');
require_once(WRMR_PLUGIN_DIR . 'includes/settings.php');
require_once(WRMR_PLUGIN_DIR . 'includes/shortcodes.php');
require_once(WRMR_PLUGIN_DIR . 'includes/widgets.php');
