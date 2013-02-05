<h3>Manage Event Types</h3>
<form action='' id='event_types' method='POST'>
	<div class='formRow addEventType'>
		<label><a class='add_event_type'>+ Add Event Type</a></label>
		<label><a class='remove_all_event_types'>- Remove All Event Types</a></label>
	</div>
	
	<div id='event_type_container'>

	<?php
	$event_types = new Event_Types;
	
	$cur_event_types = $event_types->getEventTypes();
	$n = 0;
	foreach($cur_event_types as $event_type) {
		?>
		<div class='formRow'>
			<h3>Event Type Details:</h3>
			<label>Name: <input type='text' name='name[<?php echo $n; ?>]' value='<?php echo $event_type->getName(); ?>' /></label>
			<label>Type: <input type='text' name='type[<?php echo $n; ?>]' value='<?php echo $event_type->getType(); ?>' /></label>
			<label>Description: <input type='text' name='description[<?php echo $n; ?>]' value='<?php echo $event_type->getDescription(); ?>' /></label>
			<label>Color: <input type='text' name='color[<?php echo $n; ?>]' id='colorpicker' value='<?php echo $event_type->getColor(); ?>' /></label>
			<label><a class='remove_event_type'>Remove Event Type</a></label>
		</div>
		<?php
		$n++;
	}
	?>
	</div>
	

	<div class='formRow verticalSpace'>
		<label><input type='submit' name='event_type_submit' value='Update' /> (If you have removed event types, you will need to update.)</label>
	</div>
</form>