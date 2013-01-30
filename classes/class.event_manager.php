<?php

class Event_Manager {
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;

        // Check post values / if anything needs to be done
        // We need to check if the user added an event.  If they did, process the
        // data

        if (isset($_POST['event_timestamp']) && intval($_POST['event_timestamp']) > 0 && !isset($_POST['edit_event']))
            echo "<div id='cc_msg'>" . addEvent() . "</div>";

        if (isset($_GET['delete']) && intval($_GET['delete'] > 0))
            echo "<div id='cc_msg'>" . removeEvent($_GET['delete']) . "</div>";

        if (isset($_POST['edit_event']) && intval($_POST['edit_event']) > 0)
            echo "<div id='cc_msg'>" . editEvent($_POST['edit_event']) . "</div>";
    }

    public function addEvent() {
        $data = array();
        $data["cc_date"] = date("Y-m-d", intval($_POST['event_timestamp']));

        $fields = array("cc_event_type", "cc_time", "cc_title", "cc_description");

        foreach ($fields as $field) {
            $data[$field] = $_POST[$field];
        }

        if ($this->wpdb->insert(WPC_DB, $data)) {
            $msg = "Event added to the calendar.";
        } else {
            $msg = "Due to an error, the event was not added.";
        }

        return $msg;
    }

    public function editEvent($eventID) {
        $data = array();
        $data["cc_date"] = date("Y-m-d", intval($_POST['event_timestamp']));

        $fields = array("cc_event_type", "cc_time", "cc_title", "cc_description");

        $where = array('id' => $eventID);

        foreach ($fields as $field) {
            $data[$field] = $_POST[$field];
        }

        if ($this->wpdb->update(WPC_DB, $data, $where)) {
            $msg = "Changes to the event were made.";
        } else {
            $msg = "Due to an error, the changes were not made.";
        }

        return $msg;
    }

    public function removeEvent($eventID) {
        $sql = "DELETE FROM " . WPC_DB . " WHERE id = " . $eventID . " LIMIT 1";

        if ($this->wpdb->query($sql)) {
            $msg = "Event deleted succesfully.";
        } else {
            $msg = "Due to an error, the event was not deleted.";
        }

        return $msg;
    }

}
?>
