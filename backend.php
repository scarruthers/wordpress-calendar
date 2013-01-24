<?php

// We need to check if the user added an event.  If they did, process the data

if( isset($_POST['event_timestamp'] ) && intval( $_POST['event_timestamp'] ) > 0 && !isset( $_POST['edit_event'] ) )
	echo "<div id='cc_msg'>" . addEvent() . "</div>";

if( isset( $_GET['delete'] ) && intval( $_GET['delete'] > 0 ) )
	echo "<div id='cc_msg'>" . removeEvent( $_GET['delete'] ) . "</div>";

if( isset( $_POST['edit_event'] ) && intval( $_POST['edit_event'] ) > 0 )
	echo "<div id='cc_msg'>" . editEvent( $_POST['edit_event'] ) . "</div>";

displayCalendar();

?>