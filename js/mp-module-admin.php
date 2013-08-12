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
	//UPLOADERS - WORDPRESS 3.5 + UPLOADER & OLDER WORDPRESS UPLOADER
	uploaders =
	{
		//THIS METHOD LAUNCHES THE WORDPRESS 3.5 + UPLOADER
		uploader_new : function()
		{
			//LOGO IMAGE UPLOAD BUTTON CLICKED
			jQuery('#mp_logo_image_button').click(function()
			{
				//ADD UPLOADED LOGO TO LOGO IMAGE FIELD
				wp.media.editor.send.attachment = function(props, attachment)
				{
					jQuery('#mp_logo_image').val(attachment.url);
				}
			
				//OPEN WORDPRESS MEDIA UPLOADER
				wp.media.editor.open(this);
			
				return false;
			});
			
			//SLIDE IMAGE UPLOAD BUTTON CLICKED
			jQuery('#slide_image_button').click(function()
			{
				//ADD UPLOADED SLIDE IMAGE TO SLIDE IMAGE FIELD
				wp.media.editor.send.attachment = function(props, attachment)
				{
					jQuery('#slide_image').val(attachment.url);
				}
			
				//OPEN WORDPRESS MEDIA UPLOADER
				wp.media.editor.open(this);
			
				return false;
			});
			
			//LOGO IMAGE UPLOAD BUTTON CLICKED
			jQuery('#testimonial_photo_button').click(function()
			{
				//ADD UPLOADED LOGO TO LOGO IMAGE FIELD
				wp.media.editor.send.attachment = function(props, attachment)
				{
					jQuery('#testimonial_photo').val(attachment.url);
				}
			
				//OPEN WORDPRESS MEDIA UPLOADER
				wp.media.editor.open(this);
			
				return false;
			});
		},
		
		//THIS METHOD LAUNCHES < WORDPRESS 3.5 + UPLOADER
		uploader_old : function()
		{
			//UPLOAD LOGO IMAGE BUTTON CLICKED
			jQuery('#mp_logo_image_button').click(function()
			{
				//LAUNCH LOGO IMAGE THICKBOX
				tb_show('Logo Image', 'media-upload.php?type=image&amp;TB_iframe=true');
				
				return false;
			});
			
			//UPLOAD SLIDE IMAGE BUTTON CLICKED
			jQuery('#slide_image_button').click(function()
			{
				//LAUNCH TESTIMONIAL PHOTO THICKBOX
				tb_show('Slide Image', 'media-upload.php?type=image&amp;TB_iframe=true');
				
				return false;
			});
			
			//UPLOAD TESTIMONIAL PHOTO BUTTON CLICKED
			jQuery('#testimonial_photo_button').click(function()
			{
				//LAUNCH TESTIMONIAL PHOTO THICKBOX
				tb_show('Testimonial Photo', 'media-upload.php?type=image&amp;TB_iframe=true');
				
				return false;
			});
		
			//SEND URL TO WORDPRESS FORM FIELD
			window.send_to_editor = function(html)
			{
				//INITIALISE IMAGE URL
				mp_image_url = jQuery('img', html).attr('src');
				
				//LOGO IMAGE IMAGE EXISTS
				if(jQuery('#mp_logo_image').length)
				{
					//ADD LOGO IMAGE URL TO LOGO IMAGE FIELD
					jQuery('#mp_logo_image').val(mp_image_url);
					
					//CLOSE LOGO IMAGE THICKBOX
					tb_remove();
				}
				
				//SLIDE IMAGE EXISTS
				if(jQuery('#slide_image').length)
				{
					//ADD SLIDE IMAGE URL TO SLIDE IMAGE FIELD
					jQuery('#slide_image').val(mp_image_url);
					
					//CLOSE SLIDE IMAGE THICKBOX
					tb_remove();
				}
				
				//TESTIMONIAL PHOTO EXISTS
				if(jQuery('#testimonial_photo').length)
				{
					//ADD TESTIMONIAL PHOTO URL TO TESTIMONIAL PHOTO FIELD
					jQuery('#testimonial_photo').val(mp_image_url);
					
					//CLOSE TESTIMONIAL PHOTO THICKBOX
					tb_remove();
				}
			}
		}
	},

	//IMAGES - PRELOAD, GALLERIFIC & COLORBOX
	images = 
	{
		//THIS METHOD LAUNCHES THE preload.init() & colorbox_init() METHODS
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
	},
	
	//BIOGRAPHY IN USER PROFILE
	biography =
	{
		//THIS METHOD REMOVES THE DEFAULT BIOGRAPHY FIELD
		init : function()
		{
			//DEFAULT BIOGRAPHY FIELD EXISTS
			if(jQuery('#profile-page').find('#description').length > 0)
			{
				console.log('Description Found');
				
				jQuery('#description').parents('tr').remove();
			}
		}
	}
	
	//PUBLIC METHODS
	return{
		
		//LAUNCH ALL THE FOLLOWING METHODS AT PAGE LOAD
		run_at_load : function()
		{
			images.init();
		},
		
		//LAUNCH WORDPRESS 3.5 + UPLOADER METHOD
		run_uploader_new : function()
		{
			uploaders.uploader_new();
		},
		
		//LAUNCH WORDPRESS < 3.5 UPLOADER METHOD
		run_uploader_old : function()
		{
			uploaders.uploader_old();
		},
		
		//LAUNCH BIOGRAPHY METHOD
		run_biography : function()
		{
			biography.init();
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