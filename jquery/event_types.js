jQuery(document).ready(function() {
	
	window.event_counter = $('#event_type_container').children().size();
	
	$('#event_type_container').on('click', 'a.remove_event_type', function(event) {
		var remove = confirm('Are you sure you want to remove this event type?\n\nThis cannot be undone.');
		
		if(remove == true) {
			// Go ahead and remove the event
			$(this).parents('.formRow').remove();
		}	
	});
	
	$('.add_event_type').click(function() {
		window.event_counter += 1
		
		// Append another formrow
		var i = window.event_counter - 1;
		var html = [
			"<div class='formRow'>",
			"<h3>Event Type Details:</h3>",
			"<label>Name: <input type='text' name='name[" + i + "]' /></label>",
			"<label>Type: <input type='text' name='type[" + i + "]' /></label>",
			"<label>Description: <input type='text' name='description[" + i + "]' /></label>",
			"<label>Color: <input type='text' name='color[" + i + "]' /></label>",
			"<label><a class='remove_event_type'>Remove Event Type</a></label>",
			"</div>"	
			].join('\n');
		
		$('#event_type_container').append(html);
	});
	
	$('.remove_all_event_types').click(function() {
		var remove = confirm('Are you sure you want to remove ALL the event types?\n\nThis cannot be undone.');
		$('#event_type_container').children().remove();
	});
});