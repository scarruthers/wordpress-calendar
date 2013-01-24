<?php

class Tooltip {

	static private $num_tooltips;

	public function __construct() {
		
	}

	static public function returnNonEditableTooltip($event) {
		$tooltip = "<h2>{$event->event_title}</h2>";

		if ($event->event_description != "") {
			$tooltip .= "<span><strong>Description: </strong>{$event->event_description}</span>";
		}
		if ($event->event_start_time != "") {
			$tooltip .= "<strong>Time: </strong>{$event->event_start_time}<br />";
		}
		$this->num_tooltips++;

		return $tooltip;
	}

	static public function returnEditableTooltip($timestamp, $new_event = true, $event = null) {
		if ($new_event == true) {
			$tooltip = "<a class='add_event'>+ Add Event</a>";
			$tooltip .= "<div class='tooltip'>";

			$identifier = $timestamp;
			$action = "add";
		} else {
			$identifier = $timestamp . "_" . $event->id;
			$action = "edit";
		}
		$long_name = "{$action}_cal_event_{$identifier}";
		$event_details = "<form name='{$long_name}' id='{$long_name}' action='' method='POST'>
						<input type='hidden' name='event_timestamp' value='{$timestamp}' />
						<input type='hidden' name='{$action}_event' value='{$event->id}' />
						Event type: <select name='cc_event_type' id='{$dayTimeStamp}_{$event->id}'>
									<option value='Wedding' " . ($eType == 'Wedding' ? "selected" : "") . ">Wedding
									<option value='Rehearsal Dinner'" . ($eType == 'Rehearsal Dinner' ? "selected" : "") . ">Rehearsal Dinner
									<option value='Other Event' " . ($oeType == true ? "selected" : "") . ">Other Event
									<option value='Event Pending'" . ($eType == 'Event Pending' ? "selected" : "") . ">Event Pending
									</select><br />
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
						<input type='submit' onclick='javascript: jQuery(\"#{$long_name}\").submit();' name='submit_form' value='Update' style='float: left;' />
						<input type='button' name='delete_event' value='Delete' onclick='window.location.replace(\"" . site_url() . "/wp-admin/admin.php?page=cc-main&delete={$event->id}\")' style='float: left;' />
						</form>";

		if ($new_event == true) {
			$tooltip .= "</div>";
		}

		$this->num_tooltips++;

		return $tooltip;
	}

}
?>
