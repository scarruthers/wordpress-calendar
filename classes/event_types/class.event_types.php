<?php

class Event_Types {

    private $event_types;
    private $event_select;
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

	public function addEventType($event_type) {
		$this->event_types[] = $event_type;
	}

    public function getEventTypes() {
        if (!is_null($this->event_types)) {
            return $this->event_types;
        }

        $event_types = get_option('wpc_event_types');
        $event_types = explode(";;;", $event_types);
        foreach($event_types as $index => $event_type) {
        	$event_types[$index] = new Event_Type($event_type);
            // = array(event name, event type, event description, event color);
        }
        $this->event_types = $event_types;

        return $this->event_types;
    }

	public function setEventTypes() {
		$option_data = array();
		echo "<pre>";var_dump($this->event_types);echo "</pre>";
		foreach($this->event_types as $event_type) {
			$option_data[] = $event_type->renderJSON();
		}
		
		update_option('wpc_event_types', implode(";;;", $option_data));
	}

    public function getEventTypeSelect($select_name, $select_id = null, $active_event_type = null) {
        if (!is_null($this->event_select)) {
            return $this->event_select;
        }

        $select = "<select name='{$select_name}' id='{$select_id}'>";
        foreach ($this->getEventTypes() as $index => $event_type) {
        	$name = $event_type->getName();
            $select .= "<option value='{$name}' " . ($name == $active_event_type ? "checked" : "") . ">{$name}";
        }
        $select .= "</select>";

        return $select;
    }
}
?>