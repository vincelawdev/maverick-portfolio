<?php
#TURN ON OUTPUT BUFFERING
if(!ob_start('ob_gzhandler'))
{
	ob_start();
}

#INITIALISE WP-LOAD.PHP FILE PATH
$wp_include_path = '../wp-load.php';

#SEARCH FOR WP-LOAD.PHP FILE PATH
for($counter = 0; $counter < 10; $counter ++)
{
	#WP-LOAD.PHP FILE DOES NOT EXIST AT THIS PATH
	if(!file_exists($wp_include_path))
	{
		$wp_include_path = '../' . $wp_include_path;
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
header('content-type: application/x-javascript');
?>
//MAVERICK PORTFOLIO ADMIN MODULE
var mp_module_admin = function()
{
	//IMAGES - PRELOAD, GALLERIFIC & COLORBOX
	images = 
	{
		//THIS METHOD LAUNCHES THE preloader() & colorbox_init() METHODS
		init : function()
		{
			this.preload.init();
			this.colorbox_init();
		},

		//THIS OBJECT CONTAINS THE PRELOADING METHODS & PROPERTIES
		preload :
		{
			//INITIALISE LIST OF IMAGES TO PRELOAD
			preload_images :
			[
				'<?php bloginfo('template_directory'); ?>/images/menu-cp-article-on.png',
				'<?php bloginfo('template_directory'); ?>/images/menu-cp-portfolio-on.png',
				'<?php bloginfo('template_directory'); ?>/images/menu-cp-slide-on.png',
				'<?php bloginfo('template_directory'); ?>/images/menu-cp-testimonial-on.png'
			],
			
			//THIS METHOD PRELOADS THE LIST OF IMAGES
			init : function()
			{
				//INITIALISE PRELOADER IMAGE OBJECT
				preloader_image = new Image();
				
				//PRELOAD LIST OF IMAGES
				for(var image_counter = 0; image_counter  < images.preload.preload_images.length; image_counter ++) 
				{			
					preloader_image.src = images.preload.preload_images[image_counter];
				}
			}
		},
	
		//THIS METHOD INITIALISES THE COLORBOX
		colorbox_init : function()
		{		
			//IMAGES
			jQuery('a.colorbox').colorbox();
		}
	}
	
	//PUBLIC METHODS
	return{
		
		//LAUNCH ALL THE FOLLOWING METHODS AT PAGE LOAD
		run_at_load: function()
		{
			images.init();
		}
	};
}();

//RUN MAVERICK PORTFOLIO MODULE AT PAGE LOAD
jQuery(document).ready(function() 
{
	mp_module_admin.run_at_load();
});
<?php
#SEND THE OUTPUT BUFFER AND TURN OFF OUTPUT BUFFERING 
ob_end_flush();
?>