//WAIT FOR PAGE TO LOAD
jQuery(document).ready(function()
{
	jQuery("#slider")
	.anythingSlider(
	{
		theme: "default",
		expand: false,
		resizeContents: false,
		showMultiple: false,
		easing: "swing",
		
		// Navigation
		startPanel          : 0,
		changeBy            : 1,
		hashTags            : true,
		infiniteSlides      : true,
		navigationFormatter : null,
		navigationSize      : false,
		
		// Slideshow options
		autoPlay            : true,
		autoPlayLocked      : false,
		autoPlayDelayed     : false,
		pauseOnHover        : true,
		stopAtEnd           : false,
		playRtl             : false,
		buildStartStop		: false,
		
		// Times
		delay               : 5000,
		resumeDelay         : 5000,
		animationTime       : 600,
		delayBeforeAnimate  : 0
	})
	
	.anythingSliderFx(
	{
		".panel": ["fade", 300, "easeInCirc"],
		".caption-bottom" : ["caption-Bottom", "50px"]
	});
});	