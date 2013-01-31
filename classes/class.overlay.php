<?php

class Overlay {

	static private $num_overlays;
	private $event_types;

	public function __construct() {
		$this->event_types = new Event_Types;
	}

	public function returnNonEditableOverlay($event) {
		$overlay = "<h2>{$event->event_title}</h2>";

		if ($event->event_description != "") {
			$overlay .= "<span><strong>Description: </strong>{$event->event_description}</span>";
		}
		if ($event->event_start_time != "") {
			$overlay .= "<strong>Time: </strong>{$event->event_start_time}<br />";
		}
		self::$num_overlays++;

		return $overlay;
	}

	public function returnEditableOverlay($timestamp, $new_event = true, $event = null) {
		if ($new_event == true) {
			$identifier = $timestamp;
			$action = "add";

			$overlay = "<a class='add_event' rel='#{$identifier}'>+Add Event</a>";
			$overlay .= "<div class='modal' id='{$identifier}'>";
			$overlay .= "<h3>Add Event on " . date("n/j/Y", $timestamp) . "</h3><br />";

            // Create a filler event to prevent object access errors
            $event = (object) array("id" => "", "event_type" => "", "event_title" => "", "event_description" => "", "event_start_time" => "");
		} else {
			$identifier = $timestamp . "_" . $event->id;
			$action = "edit";
		}
		$long_name = "{$action}_cal_event_{$identifier}";

		$overlay .= "<form name='{$long_name}' id='{$long_name}' action='' method='POST'>
						<input type='hidden' name='event_timestamp' value='{$timestamp}' />
						<input type='hidden' name='{$action}_event' value='{$event->id}' />
						<div class='labelDiv'>
							<label>Event type:</label>" . $this->event_types->getEventTypeSelect('event_type', $identifier, $event->event_type) . "
						</div>
						<div class='labelDiv'>
							<label>Title:</label>
							<input type='text' name='event_title' value='{$event->event_title}' />
						</div>
						<div class='labelDiv'>
							<label>Time:</label>
							<input type='text' name='cc_time' value='{$event->event_start_time}' size='8' />
						</div>
						<div class='labelDiv'>
							<label>Description:</label>
							<textarea name='event_description'>{$event->event_description}</textarea>
						</div>

						<input type='submit' onclick='javascript: jQuery(\"#{$long_name}\").submit();' name='submit_form' value='Update' style='float: left;' />" .
						($new_event ? "" : "<input type='button' name='delete_event' value='Delete' onclick='window.location.replace(\"" . site_url() . "/wp-admin/admin.php?page=cc-main&delete={$event->id}\")' style='float: left;' />") .
						"</form>";

		if ($new_event == true) {
			$overlay .= "</div>";
		}

		self::$num_overlays++;

		return $overlay;
	}

}
?>
