<?php

DEFINE('WPC_DB', $wpdb->prefix . "wpc_events");
DEFINE('WPC_EVENT_TYPE_DB', $wpdb->prefix . "wpc_types");

DEFINE('WPC_VERSION', '1.3.4');
DEFINE('WPC_PLUGIN_NAME', basename(dirname( __FILE__ )));
DEFINE('WPC_DISPLAY_NAME', "WP Calendar");

//require_once(WPC_PATH . 'functions.php');
require_once(WPC_PATH . 'install.php');

require_once(WPC_PATH . 'classes/class.calendar.php');


?>