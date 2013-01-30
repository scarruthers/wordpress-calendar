<?php

class Event_Types {

    private $event_types;
    private $event_select;
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function getEventTypes() {
        if (!is_null($this->event_types)) {
            return $this->event_types;
        }

        $event_types = get_option('wpc_event_types');
        $event_types = explode(";", $event_types);
        foreach($event_types as $index => $event_type) {
            // = array(event name, event type, event description, event color);
            $event_types[$index] = explode(":", $event_type);
        }
        $this->event_types = $event_types;

        return $this->event_types;
    }

    public function getEventTypeSelect($select_name, $select_id = null, $active_event_type = null) {
        if (!is_null($this->event_select)) {
            return $this->event_select;
        }

        $select = "<select name='{$select_name}' id='{$select_id}'>";
        foreach ($this->getEventTypes() as $index => $event_data) {
            $select .= "<option value='{$event_data[0]}' " . ($event_data[0] == $active_event_type ? "checked" : "") . ">{$event_data[0]}";
        }
        $select .= "</select>";

        return $select;
    }
}
?>