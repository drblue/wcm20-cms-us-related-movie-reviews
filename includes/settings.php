<?php

/**
 * Settings for plugin
 *
 */

/**
 * Add options page submenu
 *
 * @return void
 */
function wrmr_add_options_page() {
	add_options_page(
		'Related Movie Reviews Settings',	// Page Title
		'Related Movie Reviews',			// Menu Title
		'manage_options',					// Minimum Capability
		'wrmr-settings',					// Slug for our page
		'wrmr_settings_page'				// Callback for rendering page
	);
}
add_action('admin_menu', 'wrmr_add_options_page');

/**
 * Render settings page
 *
 * @return void
 */
function wrmr_settings_page() {
	// check user capabilities
	if (!current_user_can('manage_options')) {
		return;
	}
	?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<form action="options.php" method="post">
				<?php
					// output security fields for the registered settings section "wrmr-general-options"
					settings_fields('wrmr-general-options');

					// output setting sections and their fields for page 'wrmr-settings'
					do_settings_sections('wrmr-settings');

					// output save settings button
					submit_button(__('Save Settings', 'wrmr'));
				?>
			</form>

			<?php if (wp_get_environment_type() === "development"): ?>
				<h2>Config</h2>
				<?php
					echo "<pre>";
					print_r([
						'wrmr_default_title' => get_option('wrmr_default_title'),
						'wrmr_posts_to_show' => get_option('wrmr_posts_to_show'),
						'wrmr_add_to_posts' => get_option('wrmr_add_to_posts'),
					]);
					echo "</pre>";
				?>
			<?php endif; ?>
		</div>
	<?php
}

/**
 * Register all options for our settings page
 *
 * @return void
 */
function wrmr_settings() {
	/**
	 * Add Settings Section 'General Options'
	 */
	add_settings_section(
		'wrmr-general-options',				// ID
		'General Options',					// Title
		'wrmr_settings_section_general',	// Callback for rendering content below the section title and above the settings fields
		'wrmr-settings'						// Page to add this settings section to (slug of what we added using `add_options_page()`)
	);

	/**
	 * Add Settings Fields to Settings Section 'General Options'
	 */

	// Default title
	add_settings_field(
		'wrmr_default_title',		// ID
		'Default Title',			// Label
		'wrmr_default_title_cb',	// Callback for rendering form field
		'wrmr-settings',			// Page to add settings field to (slug of what we added using `add_options_page()`)
		'wrmr-general-options'		// Section to add settings field to (ID of what we added using `add_settings_section()`)
	);
	register_setting('wrmr-general-options', 'wrmr_default_title');

	// Number of posts to show
	add_settings_field(
		'wrmr_posts_to_show',		// ID
		'Number of posts to show',	// Label
		'wrmr_posts_to_show_cb',	// Callback for rendering form field
		'wrmr-settings',			// Page to add settings field to (slug of what we added using `add_options_page()`)
		'wrmr-general-options'		// Section to add settings field to (ID of what we added using `add_settings_section()`)
	);
	register_setting('wrmr-general-options', 'wrmr_posts_to_show');

	// Add to posts?
	add_settings_field(
		'wrmr_add_to_posts',		// ID
		'Add related movie reviews to all reviews',	// Label
		'wrmr_add_to_posts_cb',		// Callback for rendering form field
		'wrmr-settings',			// Page to add settings field to (slug of what we added using `add_options_page()`)
		'wrmr-general-options'		// Section to add settings field to (ID of what we added using `add_settings_section()`)
	);
	register_setting('wrmr-general-options', 'wrmr_add_to_posts');
}
add_action('admin_init', 'wrmr_settings');

/**
 * Content below section title 'General Options' and above the settings fields
 *
 * @return void
 */
function wrmr_settings_section_general() {
	?>
		<p>This is a very nice section. Probably the nicest section ever.</p>
	<?php
}

/**
 * Render settings field 'wrmr_default_title'
 *
 * @return void
 */
function wrmr_default_title_cb() {
	?>
		<input
			type="text"
			id="wrmr_default_title"
			name="wrmr_default_title"
			value="<?php echo get_option('wrmr_default_title', __('Related Movie Reviews', 'wrmr')); ?>"
		>
	<?php
}

/**
 * Render settings field 'wrmr_posts_to_show'
 *
 * @return void
 */
function wrmr_posts_to_show_cb() {
	?>
		<input
			type="number"
			min=1
			id="wrmr_posts_to_show"
			name="wrmr_posts_to_show"
			value="<?php echo get_option('wrmr_posts_to_show', 3); ?>"
		>
	<?php
}

/**
 * Render settings field 'wrmr_posts_to_show'
 *
 * @return void
 */
function wrmr_add_to_posts_cb() {
	?>
		<input
			type="checkbox"
			id="wrmr_add_to_posts"
			name="wrmr_add_to_posts"
			value="1"
			<?php //if (get_option('wrmr_add_to_posts') == "1") { echo "checked"; } ?>
			<?php checked(get_option('wrmr_add_to_posts')); ?>
		>
	<?php
}
