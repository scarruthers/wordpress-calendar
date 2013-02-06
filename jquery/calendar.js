jQuery(document).ready(function() {
	
	$(".add_event, .view_event").overlay({
		// some mask tweaks suitable for modal dialogs
		mask : {
			color : '#ebecff'
		},
		loadSpeed: 200,
		top: '15%',
		left: 'center',
		fixed: true,
		opacity: 0.2,
		
		api: true,
		closeOnClick : false,
		
		onBeforeLoad: function() {
			var time_picker = this.getOverlay().find(".timepicker");
			var date_picker = this.getOverlay().find(".datepicker");
			
			time_picker.timepicker({
				showCloseButton: true,
				closeButtonText: 'Done',
				showDeselectButton: true,
				deselectButtonText: 'Clear'
			});
			
			date_picker.datepicker({
				dateFormat: "yy-mm-dd"
			});
		}
	});
	
	

	var currentPosition = 0;
	var slideWidth = 911;
	var slides = $('.slide');
	var numberOfSlides = slides.length;

	// Wrap all .slides with #slideInner div slides
	slides.wrapAll("<div id='slideInner'></div>")

	// Float left to display horizontally, readjust .slides width
	.css({
		'float' : 'left',
		'width' : slideWidth
	});

	// Set #slideInner width equal to total width of all slides
	$('#slideInner').css('width', slideWidth * numberOfSlides);

	// Hide left arrow control on first load
	manageControls(currentPosition);

	// Create event listeners for .controls clicks
	$('.control').bind('click', function() {
		// Determine new position
		currentPosition = ($(this).attr('id') == 'rightControl') ? currentPosition + 1 : currentPosition - 1;

		// Hide / show controls
		manageControls(currentPosition);
		// Move slideInner using margin-left
		$('#slideInner').animate({
			'marginLeft' : slideWidth * (-currentPosition)
		});
	});

	// manageControls: Hides and Shows controls depending on currentPosition
	function manageControls(position) {
		// Hide left arrow if position is first slide
		if (position == 0) {
			$('#leftControl').hide()
		} else {
			$('#leftControl').show()
		}
		// Hide right arrow if position is last slide
		if (position == numberOfSlides - 1) {
			$('#rightControl').hide()
		} else {
			$('#rightControl').show()
		}
	}
});
