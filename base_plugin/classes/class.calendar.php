<?php

class Calendar {
	
	private $month_names;
	private $start_month;
	private $end_month;
	private $tooltip;
	
	public function __construct() {
		$this->month_names = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		
		$this->setDateData();
		$this->tooltip = new Tooltip;
	}

	public function returnCalendar() {
		return $this->generateCalendar();
	}

	private function generateCalendar() {
		$calendar = "<div id='cal'><!-- BEGIN CALENDAR --><div class='header'><span class='control' id='leftControl'><div class='arrowleft'></div></span><span class='control' id='rightControl'><div class='arrowright'></div></span></div><div id='slideshow'><div id='slidesContainer'>";
	
		$current_month;
		$current_year;
		for ($v = 0; $v < $num_months; $v++) {
			$calendar .= addMonth($current_month, $current_year);
			
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
		$dates = $wpdb->get_row($sql);
		
		$num_months = ceil((strtotime("now") - strtotime($dates->sdate)) / 2629743);

		$possible_end_date = strtotime($dates->edate . " + 3 months");
		$default_end_date = strtotime("now + 24 months");
	
		if ($default_end_date < $possible_end_date) {
			$num_months += ceil(($possible_end_date - strtotime("now")) / 2629743);
		} else {
			$num_months += 24;
		}
		
		$this->start_month = date("n", strtotime($dates->sdate));
		$this->start_year = date("Y", strtotime($dates->sdate));
		$this->num_months = $num_months;
	}
	
	private function addMonth($current_month, $current_year) {
		$timestamp = mktime(0, 0, 0, $current_month, 1, $current_year);
		$num_days = date("t", $timestamp);
		$thismonth = getdate($timestamp);
		$startday = $thismonth['wday'];
		$month .= "<div class='slide'><div class='month'>{$this->month_names[$current_month-1]} {$current_year}</div><div class='weekDayWrapper'><!--Weekday Wrapper--><div class='weekDayFirst'>Sunday</div><div class='weekDay'>Monday</div><div class='weekDay'>Tuesday</div><div class='weekDay'>Wednesday</div><div class='weekDay'>Thursday</div><div class='weekDay'>Friday</div><div class='weekDayLast'>Saturday</div></div><!-- weekday wrapper -->";
		
		for ($i = 1; $i <= ($maxday + $startday); $i++) {
			if ($i < $startday + 1) {
				$month .= "<div class='day'></div>";
			} else {
				$current_day = ($i - $startday);
				$month .= addDay($current_month, $current_day, $current_year);
			}
		}
		
		$j = 0;
		while (($num_days + $startday + $j) % 7 != 0) {
			$content .= "<div class='day'></div>";
			$j++;
		}
		
		$month .= "</div><!--slide-->";
	}
	
	private function addDay($current_month, $current_day, $current_year) {
		$mysql_date = date("Y-m-d", mktime(0, 0, 0, $current_month, $current_day, $current_year));
		$sql = "SELECT * FROM " . WPC_DB . " WHERE event_start_date = '" . $mysql_date . "'";
		$events = $wpdb->get_results($sql);
		$new_timestamp = strtotime($this->month_names[$current_month-1] . " " . $current_day . ", " . $current_year);		
		if($wpdb->num_rows > 0) {
			// Display event data
			foreach($events as $event) {
				$event = array_map("stripslashes", $event);
				$day .= "<div class='dayOn'><div class='dayNumber'>" . $current_day . "</div>";
				$day .= "<div class='tooltipContainer colorClassGoesHere'><a class='view_event'>{$event->event_title}</a></div><div class='tooltip_event'>";
				
				if(is_admin()) {
					$day .= $this->tooltip->returnEditableTooltip($new_timestamp, false, $event);
					$day .= $this->tooltip->returnAddEventTooltip($new_timestamp);
				} else {
					$day .= $this->tooltip->returnNonEditableTooltip($event);
				}
				
				$day .= "</div><!--tooltip_event--></div><!--dayOn-->";
			}
		} else {
			// Display empty day
			$day .= "<div class='day'><div class='dayNumber'>$current_day</div> " . ($editing ? $this->tooltip->returnAddEventTooltip($new_timestamp) : "") . "</div>";
		}
	}
}

?>
