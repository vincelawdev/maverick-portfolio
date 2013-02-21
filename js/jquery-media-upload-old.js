jQuery(document).ready(function()
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
});