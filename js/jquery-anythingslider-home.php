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
	jQuery("#slider").anythingSlider(
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
		navigationFormatter : null,      		// Details at the top of the file on this use (advanced use) 
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
		delayBeforeAnimate  : 500        		// How long to pause slide animation before going to the desired slide (used if you want your "out" FX to show).
	}).anythingSliderFx(
    {},
    {
		dataAnimate: 'data-animate'
    });
});
<?php
#SEND THE OUTPUT BUFFER AND TURN OFF OUTPUT BUFFERING 
ob_end_flush();
?>