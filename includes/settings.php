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
					// output security fields for the registered setting "wrmr_options"
					settings_fields('wrmr_options');

					// output setting sections and their fields
					do_settings_sections('wrmr');

					// output save settings button
					submit_button(__('Save Settings', 'wrmr'));
				?>
			</form>
		</div>
	<?php
}
