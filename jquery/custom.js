jQuery(document).ready(function() {

	$('.add_event').tooltip({
		delay: 1000,
		events: { def: 'click,blur' }
	});
	$('.view_event').tooltip({
		delay: 30
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
		if(position == 0) {
			$('#leftControl').hide()
		} else {
			$('#leftControl').show()
		}
		// Hide right arrow if position is last slide
		if(position == numberOfSlides - 1) {
			$('#rightControl').hide()
		} else {
			$('#rightControl').show()
		}
	}
});
