<?php
#TURN ON OUTPUT BUFFERING
if(!ob_start("ob_gzhandler"))
{
	ob_start();
}

#INITIALISE WP-LOAD.PHP FILE PATH
$wp_include_path = "../wp-load.php";

#SEARCH FOR WP-LOAD.PHP FILE PATH
for($counter = 0; $counter < 10; $counter ++)
{
	#WP-LOAD.PHP FILE DOES NOT EXIST AT THIS PATH
	if(!file_exists($wp_include_path))
	{
		$wp_include_path = "../$wp_include_path";
	}
	#WP-LOAD.PHP FILE PATH FOUND
	else
	{	
		break;
	}
}

#LOAD WORDPRESS
require($wp_include_path);

#SET FILE TYPE AS JAVASCRIPT
header("content-type: application/x-javascript");
?>
//WAIT FOR PAGE TO LOAD
jQuery(document).ready(function()
{
	jQuery("#anythingslider").anythingSlider(
	{
		//APPEARANCE 
		theme               : "default", 		// Theme name 
		expand              : false,     		// If true, the entire slider will expand to fit the parent element 
		resizeContents      : true,      		// If true, solitary images/objects in the panel will expand to fit the viewport 
		showMultiple        : false,     		// Set this value to a number and it will show that many slides at once
							
		//NAVIGATION 
		startPanel          : 1,         		// This sets the initial panel 
		changeBy            : 1,         		// Amount to go forward or back when changing panels. 
		hashTags            : true,      		// Should links change the hashtag in the URL? 
		infiniteSlides      : false,      		// if false, the slider will not wrap & not clone any panels 
		navigationFormatter : function(index, panel)
		{
    		return [<?php mp_options::mp_display_slide_titles(); ?>][index - 1];
		},										// Format navigation labels with text
		navigationSize      : 5,				// Set this to the maximum number of visible navigation tabs; false to disable
		buildArrows         : false,      		// If true, builds the forwards and backwards buttons 
  		buildNavigation     : true,     		// If true, builds a list of anchor links to link to each panel 
  		buildStartStop      : true,      		// If true, builds the start/stop button
		
		//SLIDESHOW 
		autoPlay            : true,     		// If true, the slideshow will start running; replaces "startStopped" option 
		autoPlayLocked      : false,     		// If true, user changing slides will not stop the slideshow 
		autoPlayDelayed     : false,     		// If true, starting a slideshow will delay advancing slides; if false, the slider will immediately advance to the next slide when slideshow starts 
		pauseOnHover        : true,      		// If true & the slideshow is active, the slideshow will pause on hover 
		stopAtEnd           : false,     		// If true & the slideshow is active, the slideshow will stop on the last page. This also stops the rewind effect when infiniteSlides is false. 
		playRtl             : false,     		// If true, the slideshow will move right-to-left 
		
		//TIMES
		delay               : 10000,      		// How long between slideshow transitions in AutoPlay mode (in milliseconds) 
		resumeDelay         : 5000,     		// Resume slideshow after user interaction, only if autoplayLocked is true (in milliseconds). 
		animationTime       : 0,       			// How long the slideshow transition takes (in milliseconds) 
		delayBeforeAnimate  : 500,        		// How long to pause slide animation before going to the desired slide (used if you want your "out" FX to show).
		
		// Video 
		resumeOnVideoEnd    : true,      		// If true & the slideshow is active & a supported video is playing, it will pause the autoplay until the video is complete 
		resumeOnVisible     : false,      		// If true the video will resume playing (if previously paused, except for YouTube iframe - known issue); if false, the video remains paused. 
		addWmodeToObject    : "opaque",  		// If your slider has an embedded object, the script will automatically add a wmode parameter with this setting 
		isVideoPlaying      : function(base){ return false; } // return true if video is playing or false if not - used by video extension 
	}).anythingSliderFx(
    {},
    {
		dataAnimate: "data-animate"
    });
	
	jQuery("#featured_work_wrapper").jCarouselLite(
	{
		btnPrev	: ".home_previous",
        btnNext	: ".home_next",
		auto	: null,
		speed	: 500,
		easing	: "swing",
		vertical: false,
		circular: true,
		visible	: 2,
		start	: 0,
		scroll	: 1        
    });
});
<?php
#SEND THE OUTPUT BUFFER AND TURN OFF OUTPUT BUFFERING 
ob_end_flush();
?>