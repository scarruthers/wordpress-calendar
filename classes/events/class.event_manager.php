<?php

class Event_Manager {
    private $wpdb;
    private $fields;
	private $msg;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->fields = array("event_title", "event_type", "event_location", "event_description", "event_start_date", "event_start_time", "event_end_date", "event_end_time");

        // Check post values / if anything needs to be done
        if (isset($_POST['event_timestamp']) && intval($_POST['event_timestamp']) > 0) {
            if (isset($_POST['add_event'])) {
                $this->addEvent();
            } else if (isset($_POST['edit_event']) && intval($_POST['edit_event']) > 0) {
                $this->editEvent($_POST['edit_event']);
            }
        } else if (isset($_GET['delete']) && intval($_GET['delete'] > 0)) {
            $this->removeEvent($_GET['delete']);
        }
    }

    private function hasMessage() {
        return !is_null($this->msg);
    }

    public function getMessage() {
        if ($this->hasMessage()) {
			return "<div id='wpc_msg'>" . $this->msg . "</div>";
        }
    }

    public function addEvent() {
        $data = array();
        $data["cc_date"] = date("Y-m-d", intval($_POST['event_timestamp']));

        foreach ($this->fields as $field) {
            $data[$field] = $_POST[$field];
        }

        if ($this->wpdb->insert(WPC_DB, $data)) {
            $msg = "Event added to the calendar.";
        } else {
            $msg = "Due to an error, the event was not added.";
        }
        $this->msg = $msg;
    }

    public function editEvent($eventID) {
        $data = array();
        $data["cc_date"] = date("Y-m-d", intval($_POST['event_timestamp']));

        $where = array('id' => $eventID);

        foreach ($this->fields as $field) {
            $data[$field] = $_POST[$field];
        }

        if ($this->wpdb->update(WPC_DB, $data, $where)) {
            $msg = "Changes to the event were made.";
        } else {
            $msg = "Due to an error, the changes were not made to the event (id: {$eventID}).";
        }

        $this->msg = $msg;
    }

    public function removeEvent($eventID) {
        $sql = "DELETE FROM " . WPC_DB . " WHERE id = " . $eventID . " LIMIT 1";

        if ($this->wpdb->query($sql)) {
            $msg = "Event deleted succesfully.";
        } else {
            $msg = "Due to an error, the event (id: {$eventID}) was not deleted.";
        }

        $this->msg = $msg;
    }

}
?>
