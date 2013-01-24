<?php
function addEvent() {
	global $wpdb;
	$data = array();
	$data["cc_date"] = date("Y-m-d", intval($_POST['event_timestamp']));

	$fields = array("cc_event_type", "cc_time", "cc_title", "cc_description");

	foreach ($fields as $field) {
		$data[$field] = $_POST[$field];
	}

	if ($wpdb->insert(WPC_DB, $data)) {
		$msg = "Event added to the calendar.";
	} else {
		$msg = "Due to an error, the event was not added.";
	}

	return $msg;
}

function editEvent($eventID) {
	global $wpdb;
	$data = array();
	$data["cc_date"] = date("Y-m-d", intval($_POST['event_timestamp']));

	$fields = array("cc_event_type", "cc_time", "cc_title", "cc_description");

	$where = array('id' => $eventID);

	foreach ($fields as $field) {

		$data[$field] = $_POST[$field];

	}

	if ($wpdb->update(WPC_DB, $data, $where)) {
		$msg = "Changes to the event were made.";
	} else {
		$msg = "Due to an error, the changes were not made.";
	}

	return $msg;

}

function removeEvent($eventID) {
	global $wpdb;

	$sql = "DELETE FROM " . WPC_DB . " WHERE id = " . $eventID . " LIMIT 1";

	if ($wpdb->query($sql)) {
		$msg = "Event deleted succesfully.";
	} else {
		$msg = "Due to an error, the event was not deleted.";
	}
	return $msg;
}
?>