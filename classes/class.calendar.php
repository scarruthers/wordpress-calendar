<?php

class Calendar {

	private $month_names;
	private $start_month;
	private $start_year;
	private $num_months;
	private $overlay;
	private $wpdb;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->month_names = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

		$this->setDateData();
		$this->overlay = new Overlay;
	}

	public function returnCalendar() {
		return $this->generateCalendar();
	}

	private function generateCalendar() {
		$calendar = "<div id='cal'><!-- BEGIN CALENDAR --><div class='header'><span class='control' id='leftControl'><div class='arrowleft'></div></span><span class='control' id='rightControl'><div class='arrowright'></div></span></div><div id='slideshow'><div id='slidesContainer'>";

		$current_month = $this->start_month;
		$current_year = $this->start_year;
		for ($v = 0; $v < $this->num_months; $v++) {
			$calendar .= $this->addMonth($current_month, $current_year);

			$current_month++;
			if ($current_month == 13) {
				$current_month = 1;
				$current_year++;
			}
		}
		$calendar .= "</div></div></div><!--- END CALENDAR -->";

		return $calendar;
	}

	private function setDateData() {
		$sql = "SELECT MIN(event_start_date) as sdate, MAX(event_end_date) as edate FROM " . WPC_DB;
		$dates = $this->wpdb->get_row($sql);

		$num_months = ceil((strtotime("now") - strtotime($dates->sdate)) / 2629743);

		$possible_end_date = strtotime($dates->edate . " + 3 months");
		$default_end_date = strtotime("now + 24 months");

		if ($default_end_date < $possible_end_date) {
			$num_months += ceil(($possible_end_date - strtotime("now")) / 2629743);
		} else {
			$num_months += 24;
		}

	   if(is_null($dates->sdate)) {
	       // No events in database
		    $this->start_month = date("n");
            $this->start_year = date("Y");
            $num_months = 24;
        } else {
    		$this->start_month = date("n", strtotime($dates->sdate));
    		$this->start_year = date("Y", strtotime($dates->sdate));
        }
		$this->num_months = $num_months;
	}

	private function addMonth($current_month, $current_year) {
		$timestamp = mktime(0, 0, 0, $current_month, 1, $current_year);
		$num_days = date("t", $timestamp);
		$thismonth = getdate($timestamp);
		$startday = $thismonth['wday'];
		$month = "<div class='slide'><div class='month'>{$this->month_names[$current_month-1]} {$current_year}</div><div class='weekDayWrapper'><!--Weekday Wrapper--><div class='weekDayFirst'>Sunday</div><div class='weekDay'>Monday</div><div class='weekDay'>Tuesday</div><div class='weekDay'>Wednesday</div><div class='weekDay'>Thursday</div><div class='weekDay'>Friday</div><div class='weekDayLast'>Saturday</div></div><!-- weekday wrapper -->";

		for ($i = 1; $i <= ($num_days + $startday); $i++) {
			if ($i < $startday + 1) {
				$month .= "<div class='day'></div>";
			} else {
				$current_day = ($i - $startday);
				$month .= $this->addDay($current_month, $current_day, $current_year);
			}
		}

		$j = 0;
		while (($num_days + $startday + $j) % 7 != 0) {
			$month .= "<div class='day'></div>";
			$j++;
		}

		$month .= "</div><!--slide-->";

        return $month;
	}

	private function addDay($current_month, $current_day, $current_year) {
		$mysql_date = date("Y-m-d", mktime(0, 0, 0, $current_month, $current_day, $current_year));
		$sql = "SELECT * FROM " . WPC_DB . " WHERE event_start_date = '" . $mysql_date . "'";
		$events = $this->wpdb->get_results($sql);
		$new_timestamp = strtotime($this->month_names[$current_month-1] . " " . $current_day . ", " . $current_year);
		if(count($events) > 0) {
			// Display event data
			$day = "<div class='dayOn'><div class='dayNumber'>" . $current_day . "</div>";
			foreach($events as $event) {
				$event = array_map("stripslashes", $event);
				$event_id = $new_timestamp . "-" . $event->id;
				$day .= "<div class='overlayContainer colorClassGoesHere'><a class='view_event' rel='#{$event_id}'>{$event->event_title}</a></div><div class='modal' id='{$event_id}'>";
				
				// Event Details Overlay
				if(is_admin()) {
					$day .= $this->overlay->returnEditableOverlay($new_timestamp, false, $event);
				} else {
					$day .= $this->overlay->returnNonEditableOverlay($event);
				}

				$day .= "</div><!--modal-->";
			}
			if(is_admin()) {
				// 'Add Event' Overlay
				$day .= $this->overlay->returnEditableOverlay($new_timestamp, true);
			}
			$day .= "</div><!--dayOn-->";
		} else {
			// Display empty day
			$day = "<div class='day'><div class='dayNumber'>$current_day</div> " . (is_admin() ? $this->overlay->returnEditableOverlay($new_timestamp, true) : "") . "</div>";
		}

        return $day;
	}
}

?>
