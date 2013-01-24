<?php

// Enable TinyMCE Edtior for event descriptions

/**
 * 
 * This file is becoming obsolete and will soon be replaced with calls to
 * class.calendar.php
 * 
 * 
 */

if (function_exists('wp_tiny_mce')) {
	add_filter('teeny_mce_before_init', create_function('$a', '
    $a["theme"] = "advanced";
    $a["skin"] = "wp_theme";
    $a["height"] = "200";
    $a["width"] = "800";
    $a["onpageload"] = "";
    $a["mode"] = "textareas";
    $a["elements"] = "cc_description";

    $a["editor_selector"] = "mceEditor";
	$a["theme_advanced_buttons1"] = "bold,italic,underline,strikethrough,fontsizeselect";
    
    $a["forced_root_block"] = false;
    $a["force_br_newlines"] = true;
    $a["force_p_newlines"] = false;
    $a["convert_newlines_to_brs"] = true;

    return $a;'));

	wp_tiny_mce(true);
}

function displayHomepageCalendar() {
	global $wpdb;
	$now = date("Y-m-d");

	$sql = "SELECT event_start_date, event_title, event_location FROM " . WPC_DB . " WHERE event_start_date >= CURDATE() ORDER BY event_start_date ASC LIMIT 3";
	$events = $wpdb->get_results($sql);

	for ($i = 0; $i < count($events); $i++) {
		$event = array_map("stripslashes", $events[$i]);
		echo "<div class='eventContainer'><div class='dateWrapper'>" . strtoupper(date("D", strtotime($event->event_start_date)));
		echo "<br /><div class='dayDiv'>" . date("j", strtotime($event->event_start_date)) . "</div></div>";
		echo "<div class='upcomingWrapper'><h2>" . $event->event_title . "</h2>Location: " . $event->event_location . "</div></div>";
		if ($i != $wpdb->num_rows) {
			$content .= "<div class='blogDividerHome'></div>";
		}
	}
	return $content;
}

function displayCalendar($atts = "") {
	global $wpdb;

	@extract(shortcode_atts($atts));

	$monthNames = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

	for ($v = 0; $v < $num_months; $v++) {
		for ($i = 1; $i <= ($maxday + $startday); $i++) {

			if ($i < $startday + 1) {
				// filler day
				$content .= "<div class='day'></div>\n";
			} else {
				$cDay = ($i - $startday);
				// check to see if there is an event on this day, if there is then add a link
				// and special background
				$mysqlDate = mktime(0, 0, 0, $cMonth, $cDay, $cYear);
				$mysqlDate = date("Y-m-d", $mysqlDate);
				$sql = "SELECT * FROM " . WPC_DB . " WHERE cc_date = '" . $mysqlDate . "'";
				$events = $wpdb->get_results($sql);

				$dayTimeStamp = strtotime($monthNames[$cMonth - 1] . " " . $cDay . ", " . $cYear);

				$num_breaks = 4 - $wpdb->num_rows;
				$breaks = ($num_breaks > 0 ? str_repeat("<br />", $num_breaks) : "");

				if ($wpdb->num_rows > 0) {// there are auctions on this day
					$event_html = "";
					foreach ($events as $event) {
						$start_date = date("F j\<\s\u\p\>S\<\/\s\u\p\>, Y h:i A", strtotime($event->cc_date . " " . $event->cc_time));

						if ($onFrontend == TRUE) {
							// On frontend, always display details
			

						} else {

							
						}
						$tooltip_text = ($oeType == true ? ($event->cc_title == "" ? "Other Event" : $event->cc_title) : $event->cc_event_type);
						$event_html .= "<div class='tooltipContainer {$eClass}'><a class='view_event_norm'>{$tooltip_text}</a></div><div class='tooltip_event'>{$event_details}</div><!--<div class='blogDividerCal'></div>-->";
					}
					$content .= "<div class='dayOn'><div class='dayNumber'>" . $cDay . "</div><br /><br />" . $event_html . $breaks . $addEventHtml . "</div>\n";
				} else {
					$content .= "<div class='day'><div class='dayNumber'>$cDay</div><br /><br />$breaks $addEventHtml</div>\n";
				}
			}
		}
	}
	$content .= "</div></div></div><!--- END CALENDAR -->";

	echo $content;
}
?>