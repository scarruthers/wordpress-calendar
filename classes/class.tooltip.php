<?php

class Tooltip {

	static private $num_tooltips;
	private $event_types;

	public function __construct() {
		$this->event_types = new Event_Types;
	}

	public function returnNonEditableTooltip($event) {
		$tooltip = "<h2>{$event->event_title}</h2>";

		if ($event->event_description != "") {
			$tooltip .= "<span><strong>Description: </strong>{$event->event_description}</span>";
		}
		if ($event->event_start_time != "") {
			$tooltip .= "<strong>Time: </strong>{$event->event_start_time}<br />";
		}
		self::$num_tooltips++;

		return $tooltip;
	}

	public function returnEditableTooltip($timestamp, $new_event = true, $event = null) {
		if ($new_event == true) {
			$tooltip = "<a class='add_event'>+ Add Event</a>";
			$tooltip .= "<div class='tooltip'>";

			$identifier = $timestamp;
			$action = "add";

            // Create a filler event to prevent object access errors
            $event = (object) array("id" => "", "event_type" => "", "event_title" => "", "event_description" => "", "event_start_time" => "");
		} else {
			$identifier = $timestamp . "_" . $event->id;
			$action = "edit";
		}
		$long_name = "{$action}_cal_event_{$identifier}";

		$tooltip .= "<form name='{$long_name}' id='{$long_name}' action='' method='POST'>
						<input type='hidden' name='event_timestamp' value='{$timestamp}' />
						<input type='hidden' name='{$action}_event' value='{$event->id}' />" .
						"Event type: " . $this->event_types->getEventTypeSelect('event_type', $identifier, $event->event_type) .
                        "<br />
						<div id='auto_hide_{$identifier}'>
							<div id='other_event_{$identifier}' style=''>
							Title: <input type='text' name='event_title' value='{$event->event_title}' /><br />
							Description:<br />
							<textarea name='event_description'>{$event->event_description}</textarea>
							</div>
							<div id='other_event_time_{$identifier}'>
							Time: <input type='text' name='cc_time' value='{$event->event_start_time}' size='8' />
							</div>
						</div>
						<input type='submit' onclick='javascript: jQuery(\"#{$long_name}\").submit();' name='submit_form' value='Update' style='float: left;' />" .
						($new_event ? "" : "<input type='button' name='delete_event' value='Delete' onclick='window.location.replace(\"" . site_url() . "/wp-admin/admin.php?page=cc-main&delete={$event->id}\")' style='float: left;' />") .
						"</form>";

		if ($new_event == true) {
			$tooltip .= "</div>";
		}

		self::$num_tooltips++;

		return $tooltip;
	}

}
?>
