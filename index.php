<?php
/*
 Plugin Name: WordPress Calendar
 Plugin URI: N/A
 Description: ...
 Author: Hetzel Creative
 License: Attribution-NonCommercial-ShareAlike 3.0 Unported
(http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode)
*/
DEFINE('WPC_PATH', dirname( __FILE__ ) . '/');

require_once(WPC_PATH . 'config.php');


// wpcBackend() displays the calendar to edit and add events
function wpcBackend() {
    // Perform event updates
    $event_manager = new Event_Manager;

    if($event_manager->hasMessage()) {
        echo $event_manager->getMessage();
    }

    // Output calendar
    $calendar = new Calendar;

    echo $calendar->returnCalendar();
}

// wpcTypes() allows the user to add/edit/remove event types
function wpcTypes() {
	require_once(WPC_PATH . 'types.php');
}

// wpcOptions() allows the user to set certain options for the calendar
function wpcOptions() {
    require_once(WPC_PATH . 'options.php');
}

// wpcCreateMenu() generates and displays the navigation menu
function wpcCreateMenu() {
	add_menu_page(WPC_DISPLAY_NAME, WPC_DISPLAY_NAME, 'edit_posts', 'wpc-main', 'wpcBackend');

	add_submenu_page('wpc-main', 'Event Type Management', 'Event Types', 'edit_posts', 'wpc-types', 'wpcTypes');
    add_submenu_page('wpc-main', 'Wordpress Calendar Options', 'Options', 'edit_posts', 'wpc-options', 'wpcOptions');
}

// Manage what stylesheets need to be loaded
function wpcAddStylesheets($stylesheets) {
	if(is_admin()) {
		$stylesheets = array("css/calendar.css", "css/backend_style.css", "css/jqueryui-dark-hive/jquery-ui-1.8.16.custom.css");
	} else {
		$stylesheets = array("css/calendar.css", "css/frontend_style.css");
	}
	$n = 0;
	foreach ($stylesheets as $stylesheet) {
		$style_url = WP_PLUGIN_URL . '/' . WPC_PLUGIN_NAME . '/' . $stylesheet;
		$style_file = WP_PLUGIN_DIR . '/' . WPC_PLUGIN_NAME . '/' . $stylesheet;

		if (file_exists($style_file)) {
			wp_register_style('wpcStyleSheets-' . $n, $style_url);
			wp_enqueue_style('wpcStyleSheets-' . $n);
		}
		$n++;
	}
}

function wpcAddScripts() {
	wp_deregister_script('jquery');

	// Register scripts
	wp_register_script('jquery',       WP_PLUGIN_URL . '/' . WPC_PLUGIN_NAME . '/jquery/jquery-1.9.0.min.js');
	wp_register_script('jquerytools',  WP_PLUGIN_URL . '/' . WPC_PLUGIN_NAME . '/jquery/jquery.tools.custom.min.js');
    wp_register_script('wpc_customjs',  WP_PLUGIN_URL . '/' . WPC_PLUGIN_NAME . '/jquery/custom.js');

	// Enqueue the scripts
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquerytools');
	wp_enqueue_script('wpc_customjs');
}

// Actions, Filters, Hooks

add_action('admin_menu', 'wpcCreateMenu');

add_action('wp_print_styles', 'wpcAddStylesheets');
add_action('admin_print_styles', 'wpcAddStylesheets');

add_action('wp_enqueue_scripts', 'wpcAddScripts');
add_action('admin_enqueue_scripts', 'wpcAddScripts');

add_shortcode("calendar", 'displayCalendar');
?>