<?php

DEFINE('WPC_DB', $wpdb->prefix . "wp_calendar");
DEFINE('WPC_VERSION', '1.3');
DEFINE('WPC_PLUGIN_NAME', "wordpress_calendar");
DEFINE('WPC_DISPLAY_NAME', "WordPress Calendar");
DEFINE('WPC_ERRORS', FALSE);

if (WPC_ERRORS == TRUE) {
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

require_once (ABSPATH . WPC_PATH . 'functions.php');
require_once (ABSPATH . WPC_PATH . 'frontend.php');
require_once (ABSPATH . WPC_PATH . 'user_mods.php');
require_once (ABSPATH . WPC_PATH . 'install.php');

?>