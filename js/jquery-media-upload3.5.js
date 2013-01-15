//WAIT FOR PAGE TO LOAD
jQuery(document).ready(function()
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
});