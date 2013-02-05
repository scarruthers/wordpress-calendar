<?php

class Event_Manager {
	
	public function __construct() {
		if(isset($_POST['event_type_submit'])) {
			$this->updateEventTypes();
		}
	}
	
	public function updateEventTypes() {
		// Iterate through all form fields and update the wordpress option

		// Reconstruct data format
		$event_types = new Event_Types;
		
		foreach($_POST['name'] as $index => $name) {
			$event_type = new Event_Type;
			
			$event_type->setName($name);
			$event_type->setType($_POST['type'][$index]);
			$event_type->setDescription($_POST['description'][$index]);
			$event_type->setColor($_POST['color'][$index]);
			
			if($event_type->getName() != "") {
				$event_types->addEventType($event_type);
			}
		}
		
		$event_types->setEventTypes();
	}
}

?>
