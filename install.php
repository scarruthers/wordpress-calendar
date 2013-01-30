<?php

require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

// mySQL date format: YYYY-MM-DD
// mySQL time format: HH:MM:SS

// The version is stored in WPC_VERSION

$tables[] = "CREATE TABLE " . WPC_DB . " (
	id mediumint(9) NOT NULL AUTO_INCREMENT,
	event_title varchar(100),
	event_type varchar(100),
	event_location varchar(100),
	event_description text,
	event_start_date DATE,
	event_start_time varchar(20),
	event_end_date DATE,
	event_end_time varchar(20),
	UNIQUE KEY id (id)
	);";

if (get_option("wpc_version") != WPC_VERSION) {
    foreach($tables as $table) {
        dbDelta($table);   
    }
	update_option("wpc_version", WPC_VERSION);
}

?>