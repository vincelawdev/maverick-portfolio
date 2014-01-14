//MAVERICK PORTFOLIO ADMIN MODULE
var mp_module_admin = function()
{
	//VALIDATORS - ARTICLES, SLIDES, LOGO IMAGE & TESTIMONIAL PHOTOS
	var validators =
	{
		//THIS METHOD LAUNCHES THE VALIDATION METHODS OF THE THEME OPTIONS PAGE
		options : function()
		{
			this.header();
			this.footer();
			this.rss();
		},
		
		//THIS METHOD VALIDATES THE HEADER OPTIONS FORM
		header : function()
		{
			//VALIDATE HEADER OPTIONS FORM
			jQuery('#mp_header').validate(
			{
				errorLabelContainer: jQuery('#mp_header_errors'),
				errorElement: 'p',
				errorClass: 'mp_error_field',
				rules:
				{
					mp_logo_image:
					{
						url2: true
					},
					mp_facebook_like_url:
					{
						url2: true
					}
				},
				messages:
				{
					mp_logo_image:
					{
						url2: 'Please enter a valid Logo Image.'
					},
					mp_facebook_like_url:
					{
						url2: 'Please enter a valid Like URL.'
					}
				}
			});
		},
		
		//THIS METHOD VALIDATES THE FOOTER OPTIONS FORM
		footer : function()
		{
			//TWITTER OPTION SELECTED
			jQuery('#mp_footer_twitter').change(function()
			{
				//TWITTER ENABLED
				if(jQuery("#mp_footer_twitter option[value='1']").attr('selected'))
				{
					//DISABLE DRIBBBLE
					jQuery('#mp_footer_dribbble').val('0');
				}
			});
			
			//DRIBBBLE OPTION SELECTED
			jQuery('#mp_footer_dribbble').change(function()
			{
				//DRIBBBLE ENABLED
				if(jQuery("#mp_footer_dribbble option[value='1']").attr('selected'))
				{
					//DISABLE TWITTER
					jQuery('#mp_footer_twitter').val('0');
				}
			});
		},
		
		//THIS METHOD VALIDATES THE RSS OPTIONS FORM
		rss : function()
		{
			//VALIDATE RSS OPTIONS FORM
			jQuery('#mp_rss').validate(
			{
				errorLabelContainer: jQuery('#mp_rss_errors'),
				errorElement: 'p',
				errorClass: 'mp_error_field',
				rules:
				{
					mp_feedburner_rss:
					{
						url2: true
					},
					mp_feedburner_email:
					{
						url2: true
					}
				},
				messages:
				{
					mp_feedburner_rss:
					{
						url2: 'Please enter a valid FeedBurner Feed Address.'
					},
					mp_feedburner_email:
					{
						url2: 'Please enter a valid FeedBurner Subscription Address.'
					}
				}
			});
		},
		
		//THIS METHOD VALIDATES THE PUBLISH BUTTON FOR ARTICLES, SLIDES, PROJECTS & TESTIMONIALS
		publish_button : function()
		{
			//PUBLISH BUTTON CLICKED
			jQuery('#publish').click(function(event)
			{
				//INITIALISE FORM CHECK
				var form_check = jQuery('#post').valid();
				
				//FORM INPUT INVALID
				if(!form_check)
				{
					//STOP PUBLISH BUTTON SEQUENCE				
					event.stopImmediatePropagation();
					
					return false;
				}
			});
		},
		
		//THIS METHOD VALIDATES THE ARTICLE POST TYPE FORM
		articles: function()
		{
			//APPEND ARTICLE ERROR BOX TO ARTICLE POST TYPE FORM
			jQuery('div.wrap').after('<div id="article_error_box" class="mp_errors error"></div>');
			
			//VALIDATE ARTICLE POST TYPE FORM
			jQuery('form#post').validate(
			{
				//VALIDATION CONTAINER & ERROR MESSAGES
				errorLabelContainer: jQuery('#article_error_box'),
				errorElement: 'p',
				errorClass: 'mp_error_field',
				
				//VALIDATION RULES
				rules:
				{
					article_url:
					{
						required: true,
						url2: true
					}
				},
				//VALIDATION MESSAGES
				messages:
				{
					article_url:
					{
						required: 'Please enter an Article URL.',
						url2: 'Please enter a valid Article URL.'
					}
				}
			});
			
			//PUBLISH BUTTON CLICKED
			validators.publish_button();
		},
		
		//THIS METHOD VALIDATES THE SLIDE POST TYPE FORM
		slides : function()
		{		
			//APPEND SLIDE ERROR BOX TO SLIDE POST TYPE FORM
			jQuery('div.wrap').after('<div id="slide_error_box" class="mp_errors error"></div>');
			
			//VALIDATE SLIDE POST TYPE FORM
			jQuery('form#post').validate(
			{
				//VALIDATION CONTAINER & ERROR MESSAGES
				errorLabelContainer: jQuery('#slide_error_box'),
				errorElement: 'p',
				errorClass: 'mp_error_field',
				
				//VALIDATION RULES
				rules:
				{
					content:
					{
						required: function(element)
						{
        					return jQuery('input[name=slide_type]:checked').val() == 'text_image';
      					}
					},
					slide_image:
					{
						required: function(element)
						{
        					return jQuery('input[name=slide_type]:checked').val() == 'image';
      					},
						url2: true
					},
					slide_video_url:
					{
						required: function(element)
						{
        					return jQuery('input[name=slide_type]:checked').val() == 'video';
      					},
						url2: true
					},
					slide_url:
					{
						required: function(element)
						{
        					if(jQuery('input[name=slide_type]:checked').val() == 'image' || 'text_image')
							{
								return;	
							}
      					},
						url2: true
					}
				},
				//VALIDATION MESSAGES
				messages:
				{
					content:
					{
						required: 'Please enter a Caption.',
					},
					slide_image:
					{
						required: 'Please enter a Slide Image.',
						url2: 'Please enter a valid Slide Image.'
					},
					slide_video_url:
					{
						required: 'Please enter a Slide Video URL.',
						url2: 'Please enter a valid Slide Video URL.'
					},
					slide_url:
					{
						required: 'Please enter a Slide URL.',
						url2: 'Please enter a valid Slide URL.'
					}
				}
			});
			
			//PUBLISH BUTTON CLICKED
			validators.publish_button();
		},
		
		//THIS METHOD VALIDATES THE PROJECT POST TYPE FORM
		projects : function()
		{
			//APPEND PROJECT ERROR BOX TO PROJECT POST TYPE FORM
			jQuery('div.wrap').after('<div id="project_error_box" class="mp_errors error"></div>');
			
			//VALIDATE PROJECT POST TYPE FORM
			jQuery('form#post').validate(
			{
				//VALIDATION CONTAINER & ERROR MESSAGES
				errorLabelContainer: jQuery('#project_error_box'),
				errorElement: 'p',
				errorClass: 'mp_error_field',
				
				//VALIDATION RULES
				rules:
				{
					portfolio_project_url:
					{
						url2: true
					}
				},
				//VALIDATION MESSAGES
				messages:
				{
					portfolio_project_url:
					{
						url2: 'Please enter a valid URL.'
					}
				}
			});
		
			//PUBLISH BUTTON CLICKED
			validators.publish_button();
		},
		
		//THIS METHOD VALIDATES THE TESTIMONIAL POST TYPE FORM
		testimonials : function()
		{
			//APPEND TESTIMONIAL ERROR BOX TO PROJECT POST TYPE FORM
			jQuery('div.wrap').after('<div id="testimonial_error_box" class="mp_errors error"></div>');
			
			//VALIDATE TESTIMONIAL POST TYPE FORM
			jQuery('form#post').validate(
			{
				//VALIDATION CONTAINER & ERROR MESSAGES
				errorLabelContainer: jQuery('#testimonial_error_box'),
				errorElement: 'p',
				errorClass: 'mp_error_field',
				
				//VALIDATION RULES
				rules:
				{
					testimonial_name:
					{
						required: true
					},
					testimonial_location:
					{
						required: true
					},
					testimonial_photo:
					{
						url2: true
					},
					testimonial_url:
					{
						url2: true
					}
				},
				//VALIDATION MESSAGES
				messages:
				{
					testimonial_name:
					{
						required: 'Please enter a Name.'
					},
					testimonial_location:
					{
						required: 'Please enter a Location.'
					},
					testimonial_photo:
					{
						url2: 'Please enter a valid Photo URL.'
					},
					testimonial_url:
					{
						url2: 'Please enter a valid URL.'
					}
				}
			});
		
			//PUBLISH BUTTON CLICKED
			validators.publish_button();
		}
	},

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
				var mp_image_url = jQuery('img', html).attr('src');
				
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
	
	//BIOGRAPHY IN USER PROFILE
	biography =
	{
		//THIS METHOD REMOVES THE DEFAULT BIOGRAPHY FIELD
		init : function()
		{
			//DEFAULT BIOGRAPHY FIELD EXISTS
			if(jQuery('#profile-page').find('#description').length > 0)
			{				
				jQuery('#description').parents('tr').remove();
			}
		}
	}
	
	//PUBLIC METHODS
	return{
		
		//LAUNCH ALL THE FOLLOWING METHODS AT PAGE LOAD
		run_at_load : function()
		{
			
		},
		
		//LAUNCH VALIDATION METHODS OF THE THEME OPTIONS PAGE
		run_validator_options : function()
		{
			validators.options();
		},
		
		//LAUNCH VALIDATOR OF ARTICLE POST TYPE FORM
		run_validator_articles : function()
		{
			validators.articles();
		},
		
		//LAUNCH VALIDATOR OF SLIDE POST TYPE FORM
		run_validator_slides : function()
		{
			validators.slides();
		},
		
		//LAUNCH VALIDATOR OF PROJECT POST TYPE FORM
		run_validator_projects : function()
		{
			validators.projects();
		},
		
		//LAUNCH VALIDATOR OF TESTIMONIAL POST TYPE FORM
		run_validator_testimonials : function()
		{
			validators.testimonials();
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

//RUN MAVERICK PORTFOLIO ADMIN MODULE AT PAGE LOAD
jQuery(document).ready(function() 
{
	mp_module_admin.run_at_load();
});