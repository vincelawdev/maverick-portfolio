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
//MAVERICK PORTFOLIO MODULE
var mp_module = function()
{
	//PAGE - WINDOW WIDTH, SIDEBAR HEIGHT, CONTENT HEIGHT, SIDEBAR HEIGHT & MAXIMUM HEIGHT
	var page =
	{
		//INITIALISE WINDOW WIDTH, CONTENT HEIGHT, SIDEBAR HEIGHT & MAXIMUM HEIGHT
		window_width   : $(window).width(),
		content_height : 0,
		sidebar_height : 0,
		max_height 	   : 0,

		//THIS METHOD UPDATES THE CONTENT HEIGHT & WINDOW WIDTH
		init : function()
		{
			//INITIALISE PAGE MEASUREMENTS
			page.page_measurements();
			
			//UPDATE WINDOW WIDTH ON PAGE RESIZE
			$(window).resize(function()
			{
				//UPDATE WINDOW WIDTH
				page.window_width = $(window).width();
				
				//UPDATE PAGE MEASUREMENTS
				page.page_measurements();
			});
		},
		
		//THIS METHOD UPDATES THE PAGE MEASUREMENTS
		page_measurements : function()
		{
			//INITIALISE CONTENT HEIGHT, SIDEBAR HEIGHT & MAXIMUM HEIGHT
			page.content_height = $('#content').outerHeight(true),
			page.sidebar_height = $('#sidebar').height(),
			page.max_height = Math.max(page.content_height, page.sidebar_height);
			
			//CONTENT HEIGHT IS SHORTER THAN SIDEBAR HEIGHT
			if(page.content_height < page.sidebar_height)
			{
				//SET THE CONTENT HEIGHT TO THE MAXIMUM HEIGHT
				$('#content').height(page.max_height);
			}
			
			//console.log('Window: ' + page.window_width);
			//console.log('Content: ' + page.content_height);
			//console.log('Sidebar: ' + page.sidebar_height);
			//console.log('Max: ' + page.max_height);
		}
	},
	
	//NAVIGATION - SUPERFISH, TINYNAV & ORGANIC TABS
	navigation =
	{
		//INITIALISE SUPERFISH LEVEL 1 HOME MENU ITEM
		home_button_on  : '<img src="<?php bloginfo('template_directory'); ?>/images/menu-home-on.png" alt="" />',
		home_button_off : '<img src="<?php bloginfo('template_directory'); ?>/images/menu-home-off.png" alt="" />',
		
		//THIS METHOD LAUNCHES THE superfish_init(), tinynav_init() & organic_tabs_init() METHODS
		init : function()
		{
			this.superfish_init();
			this.tinynav_init();
			this.organic_tabs_init();
		},
		
		//THIS METHOD INITIALISES THE SUPERFISH MENU
		superfish_init: function()
		{
			//DISPLAY SUPERFISH LEVEL 1 HOME MENU ITEM
			$('ul.sf-menu > li.home a').html(navigation.home_button_off);
			$('ul.sf-menu > li.home.current-menu-item a').html(navigation.home_button_on);
			
			//HOVER SUPERFISH LEVEL 1 HOME MENU ITEM
			$('ul.sf-menu > li.home').not('ul.sf-menu > li.home.current-menu-item').hover(
			function()
			{
				//DISPLAY HOVER HOME ICON
				$('ul.sf-menu > li.home a').html(navigation.home_button_on);
			},
			function()
			{
				//DISPLAY NORMAL HOME ICON
				$('ul.sf-menu > li.home a').html(navigation.home_button_off);
			});
			
			//DISPLAY SUPERFISH LEVEL 1 MENU DOWN ARROWS
			$('ul.sf-menu > li:has(ul.sub-menu)').append('<span class="arrow_down_off"></span>');
			$('ul.sf-menu > li.current-menu-item:has(ul.sub-menu)').find('span.arrow_down_off').replaceWith('<span class="arrow_down_on"></span>');
			$('ul.sf-menu > li.current-page-ancestor, ul.sf-menu > li.current-menu-ancestor, ul.sf-menu > li.current-menu-parent').find('span.arrow_down_off').replaceWith('<span class="arrow_down_on"></span>');
			
			//HOVER SUPERFISH LEVEL 1 MENU DOWN ARROWS
			$('ul.sf-menu > li:has(ul.sub-menu)').not('ul.sf-menu > li.current-page-ancestor, ul.sf-menu > li.current-menu-ancestor, ul.sf-menu > li.current-menu-parent, ul.sf-menu > li.current-menu-item').hover(
			function()
			{
				//DISPLAY HOVER ARROW
				$(this).find('span.arrow_down_off').replaceWith('<span class="arrow_down_on"></span>');
			},
			function()
			{
				//DISPLAY NORMAL ARROW
				$(this).find('span.arrow_down_on').replaceWith('<span class="arrow_down_off"></span>');
			});
			
			//DISPLAY SUPERFISH LEVEL 2 MENU RIGHT ARROWS
			$('ul.sub-menu > li:has(ul.sub-menu)').find('a:first').append('<span class="arrow_right_off"></span>');
			
			//HOVER SUPERFISH LEVEL 2 MENU RIGHT ARROWS
			$('ul.sub-menu > li:has(ul.sub-menu)').hover(
			function()
			{
				//DISPLAY HOVER ARROW
				$(this).find('span.arrow_right_off').replaceWith('<span class="arrow_right_on"></span>');
			},
			function()
			{
				//DISPLAY NORMAL ARROW
				$(this).find('span.arrow_right_on').replaceWith('<span class="arrow_right_off"></span>');
			});
				
			//INITIALISE SUPERFISH MENUS
			$('ul.sf-menu').supersubs(
			{ 
				minWidth: 15,
				maxWidth: 100,
				extraWidth: 1
			}).superfish();	
		},
		
		//THIS METHOD INITIALISES THE TINYNAV MENU
		tinynav_init : function()
		{
			//INTIALISE TINYNAV MENU
			$('ul.sf-menu').tinyNav(
			{
				header: 'Menu'
			});
			
			//INITILIASE HOME OPTION TEXT
			$('select.tinynav option[value="/"]').text('Home');
		},
		
		//THIS METHOD INITIALISES THE ORGANIC TABS
		organic_tabs_init : function()
		{
			//POST TABS EXISTS
			if($('#sidebar').find('#post_tabs').length > 0)
			{			
				$('#post_tabs').organicTabs({'speed': 200});
			}
			
			//COMMENT TABS EXISTS
			if($('#sidebar').find('#comment_tabs').length > 0)
			{			
				$('#comment_tabs').organicTabs({'speed': 200});
			}
		}
	},
	
	//SLIDERS - ANYTHING SLIDER & CAROUSEL LITE
	sliders =
	{
		//THIS METHOD LAUNCHES THE anything_slider.init() & carousel_lite.init() METHODS
		init : function()
		{
			this.anything_slider.init();
			this.carousel_lite.init();
		},
		
		//THIS OBJECT CONTAINS THE ANYTHING SLIDER METHODS & PROPERTIES
		anything_slider :
		{
			//INITIALISE ANYTHING SLIDER OPTIONS
			anything_slider_options :
			{
				//APPEARANCE 
				theme               : 'default', 		// Theme name 
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
				buildNavigation     : true,				// If true, builds a list of anchor links to link to each panel 
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
				addWmodeToObject    : 'opaque',  		// If your slider has an embedded object, the script will automatically add a wmode parameter with this setting 
				isVideoPlaying      : function(base){ return false; } // return true if video is playing or false if not - used by video extension	
			},
			
			//THIS METHOD INITIALISES THE ANYTHING SLIDER
			init : function()
			{
				//console.log('Anything Slider Options:');
				//console.dir(sliders.anything_slider.anything_slider_options);
			
				//ANYTHING SLIDER WRAPPER EXISTS
				if($('#content').find('#anythingslider_wrapper').length > 0)
				{
					//INITIALISE ANYTHING SLIDER NAVIGATION
					sliders.anything_slider.anything_slider_navigation();
					
					//INITIALISE ANYTHING SLIDER
					$('#anythingslider').anythingSlider(sliders.anything_slider.anything_slider_options).anythingSliderFx(
					{},
					{
						dataAnimate: 'data-animate'
					});
							
					//UPDATE ANYTHING SLIDER OPTIONS ON PAGE RESIZE
					$(window).resize(function()
					{
						//INITIALISE ANYTHING SLIDER NAVIGATION
						sliders.anything_slider.anything_slider_navigation();
						
						//INITIALISE ANYTHING SLIDER
						$('#anythingslider').anythingSlider(sliders.anything_slider.anything_slider_options).anythingSliderFx(
						{},
						{
							dataAnimate: 'data-animate'
						});
					});
				}
			},
			
			//THIS METHOD SETS THE NAVIGATION OF THE ANYTHING SLIDER ACCORDING TO THE WINDOW WIDTH
			anything_slider_navigation : function()
			{
				//TABLETS - PORTRAIT & MOBILE DEVICES BELOW 1024 PIXEL WIDTH
				if(page.window_width < 1024)
				{
					sliders.anything_slider.anything_slider_options.buildNavigation = false;
				}
				//TABLETS - LANDSCAPE & DESKTOPS FROM 1024 PIXEL WIDTH
				else
				{
					sliders.anything_slider.anything_slider_options.buildNavigation = true;
				}
			}
		},
		
		//THIS OBJECT CONTAINS THE CAROUSEL LITE METHODS & PROPERTIES
		carousel_lite :
		{
			//INITIALISE CAROUSEL LITE OPTIONS
			carousel_lite_options :
			{
				btnPrev	 : '.home_previous',
				btnNext	 : '.home_next',
				auto	 : null,
				speed	 : 500,
				easing	 : 'swing',
				vertical : false,
				circular : true,
				visible	 : 1,
				start	 : 0,
				scroll	 : 1	
			},
			
			//THIS METHOD INITIALISES THE CAROUSEL LITE
			init : function()
			{
				//console.log('Courousel Lite Options:');
				//console.dir(sliders.carousel_lite.carousel_lite_options);
			
				//FEATURED WORK WRAPPER EXISTS
				if($('#content').find('#featured_work_wrapper').length > 0)
				{				
					//INITIALISE NUMBER OF CAROUSEL LITE SLIDES
					sliders.carousel_lite.carousel_lite_number_of_slides();
					
					//INITIALISE CAROUSEL LITE
					$('#featured_work_wrapper').jCarouselLite(sliders.carousel_lite.carousel_lite_options);
					
					//UPDATE CAROUSEL LITE OPTIONS ON PAGE RESIZE
					$(window).resize(function()
					{
						//INITIALISE NUMBER OF CAROUSEL SLIDES
						sliders.carousel_lite.carousel_lite_number_of_slides();
						
						//INITIALISE CAROUSEL LITE
						$('#featured_work_wrapper').jCarouselLite(sliders.carousel_lite.carousel_lite_options);
					});
				}
			},
			
			//THIS METHOD SETS THE NUMBER OF THE CAROUSEL LITE SLIDES ACCORDING TO THE WINDOW WIDTH
			carousel_lite_number_of_slides : function()
			{
				//TABLETS - PORTRAIT & MOBILE DEVICES BELOW 1024 PIXEL WIDTH
				if(page.window_width < 1024)
				{
					sliders.carousel_lite.carousel_lite_options.visible = 1;
				}
				//TABLETS - LANDSCAPE & DESKTOPS FROM 1024 PIXEL WIDTH
				else
				{
					sliders.carousel_lite.carousel_lite_options.visible = 2;
				}
			}
		}
	},
	
	//IMAGES - PRELOAD, GALLERIFIC & COLORBOX
	images = 
	{
		//THIS METHOD LAUNCHES THE preload.init(), gallerific.init() & colorbox_init() METHODS
		init : function()
		{
			this.preload.init();
			this.gallerific.init();
			this.colorbox_init();
		},
		
		//THIS OBJECT CONTAINS THE PRELOADING METHODS & PROPERTIES
		preload :
		{
			//INITIALISE LIST OF IMAGES TO PRELOAD
			preload_images :
			[
				'<?php bloginfo('template_directory'); ?>/images/arrow-menu-down-on.png',
				'<?php bloginfo('template_directory'); ?>/images/arrow-menu-right-on.png',
				'<?php bloginfo('template_directory'); ?>/images/button-search-on.png',
				'<?php bloginfo('template_directory'); ?>/images/menu-home-on.png',
				'<?php bloginfo('template_directory'); ?>/images/menu-home-off.png',
				'<?php bloginfo('template_directory'); ?>/images/arrow-sidebar-on.png',
				'<?php bloginfo('template_directory'); ?>/images/arrow-sidebar-off.png',
				'<?php bloginfo('template_directory'); ?>/images/arrow-slider-left-off.png',
				'<?php bloginfo('template_directory'); ?>/images/arrow-slider-left-on.png',
				'<?php bloginfo('template_directory'); ?>/images/arrow-slider-right-off.png',
				'<?php bloginfo('template_directory'); ?>/images/arrow-slider-right-on.png'
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
		
		//THIS OBJECT CONTAINS THE GALLERIFIC METHODS & PROPERTIES
		gallerific : 
		{
			//INITIALISE OPACITY LEVEL
			opacity_level : 0.67,
			
			//INITIALISE GALLERIFIC OPTIONS
			gallerific_options :
			{				
				numThumbs				  : 2,
				preloadAhead			  : 10,
				maxPagesToShow			  : 100,							
				delay					  : 500,
				defaultTransitionDuration : 500,
				syncTransitions			  : true,
				autoStart				  : false,
				enableTopPager			  : false,
				enableBottomPager		  : false,
				renderSSControls		  : false,
				renderNavControls		  : false,
				enableKeyboardNavigation  : true,
				enableHistory			  : true,
				imageContainerSel		  : '#project_gallery',
				controlsContainerSel	  : '',
				captionContainerSel		  : '#project_gallery_caption',
				loadingContainerSel		  : '', 
				onSlideChange			  : function(prevIndex, nextIndex)
				{
					this.find('ul.thumbs').children().eq(prevIndex).fadeTo('fast', images.gallerific.opacity_level).end().eq(nextIndex).fadeTo('fast', 1.0);
				},
				onPageTransitionOut: function(callback)
				{
					this.fadeTo('fast', 0.0, callback);
				},
				onPageTransitionIn: function()
				{
					//INITIALISE PREVIOUS & NEXT BUTTONS
					previous_button = this.find('a.previous').css('visibility', 'hidden');
					previous_blank_button = this.find('a.previous_blank').css('visibility', 'hidden');
					next_button = this.find('a.next').css('visibility', 'hidden');
					
					//INITIALISE LAST PAGE
					last_page = this.getNumPages() - 1;
					
					//DISPLAY PREVIOUS BUTTON
					if(this.displayedPage > 0)
					{
						previous_button.css('visibility', 'visible');
					}
					
					//DISPLAY NEXT BUTTON
					if(this.displayedPage < last_page)
					{
						next_button.css('visibility', 'visible');
					}
					
					//FADE PAGE
					this.fadeTo('fast', 1.0);
				},
				onTransitionIn: function()
				{
					$('#project_gallery').fadeTo('fast', 1.0);
					$('#project_gallery span.image-wrapper').fadeTo('fast', 1.0);
					$('#project_gallery_caption').fadeTo('fast', 1.0);
					$('#project_gallery_caption span.image-caption').fadeTo('fast', 1.0);
					$('#project_gallery_caption').fadeIn('fast', function()
					{
						$('#project_gallery a.project_gallery').colorbox();
					});
				}
			},
			
			//THIS METHOD INITIALISES THE GALLERIFIC GALLERY
			init : function()
			{
				//console.log('Gallerific Options:');
				//console.dir(images.gallerific.gallerific_options);
			
				//PROJECT GALLERY EXISTS
				if($('#content').find('#project_gallery').length > 0)
				{
					//INITIALISE NUMBER OF GALLERIFIC THUMBNAILS
					images.gallerific.gallerific_number_of_thumbnails();
					
					//INITIALISE GALLERIFFIC
					gallery = $('#project_gallery_thumbnails').galleriffic(images.gallerific.gallerific_options);
					
					//UPDATE GALLERIFFIC OPTIONS ON PAGE RESIZE
					$(window).resize(function()
					{
						//INITIALISE NUMBER OF GALLERIFIC THUMBNAILS
						images.gallerific.gallerific_number_of_thumbnails();
						
						//INITIALISE GALLERIFFIC
						gallery = $('#project_gallery_thumbnails').galleriffic(images.gallerific.gallerific_options);
					});
					
					//INITIALISE OPACITY LEVELS OF THUMBNAILS, PREVIOUS BUTTON & NEXT BUTTON
					$('#project_gallery_thumbnails ul.thumbs li, #project_gallery_thumbnails a.previous, #project_gallery_thumbnails a.next').opacityrollover(
					{
						mouseOutOpacity:			images.gallerific.opacity_level,
						mouseOverOpacity:  			1.0,
						fadeSpeed:         			'fast',
						exemptionSelector: 			'.selected'
					});
					
					//INITIALISE PREVIOUS BUTTON
					gallery.find('a.previous').click(function(e)
					{
						gallery.previousPage();
						e.preventDefault();
					});
				
					//INITIALISE NEXT BUTTON
					gallery.find('a.next').click(function(e)
					{
						gallery.nextPage();
						e.preventDefault();
					});
					
					//INITIALISE HISTORY PLUGIN
					$.historyInit(images.gallerific.gallerific_page_load, 'advanced.html');
				
					//INITIALISE BACK BUTTON
					$('a[rel="history"]').live('click', function(e)
					{
						//BUTTON NOT CLICKED
						if(e.button != 0)
						{
							return true;
						}
						
						//INITIALISE HASH
						hash = this.href;
						hash = hash.replace(/^.*#/, '');
				
						//MOVE TO NEW PAGE
						$.historyLoad(hash);
				
						return false;
					});
				}
			},
			
			//THIS METHOD SELECTS THE IMAGE OF THE GALLERY
			gallerific_page_load: function(hash)
			{
				//NAVIGATE TO SELECTED IMAGE
				if(hash)
				{
					$.galleriffic.gotoImage(hash);
				}
				//NAVIGATE TO FIRST IMAGE
				else
				{
					gallery.gotoIndex(0);
				}
			},
			
			//THIS METHOD SETS THE NUMBER OF GALLERIFIC THUMBNAILS ACCORDING TO THE WINDOW WIDTH
			gallerific_number_of_thumbnails : function()
			{
				/* MOBILE - PORTRAIT */
				if(page.window_width >= 320 && page.window_width < 480)
				{
					images.gallerific.gallerific_options.numThumbs = 2;
				}
				
				/* MOBILE - LANDSCAPE */
				else if(page.window_width >= 480 && page.window_width < 768)
				{
					images.gallerific.gallerific_options.numThumbs = 3;
				}
				
				/* TABLETS - PORTRAIT */
				else if(page.window_width >= 768 && page.window_width < 1024)
				{
					images.gallerific.gallerific_options.numThumbs = 3;
				}
				
				/* TABLETS - LANDSCAPE & DESKTOPS FROM 1024 PIXEL WIDTH */
				else if(page.window_width >= 1024)
				{
					images.gallerific.gallerific_options.numThumbs = 4;
				}
			}
		},
		
		//THIS METHOD INITIALISES THE COLORBOX
		colorbox_init : function()
		{
			//CAPTION IMAGES
			$('a.colorbox').colorbox({current: 'Image {current} of {total}', rel:'colorbox'});
			
			//INSTAGRAM IFRAMES
			$('.instagram_iframe').colorbox({iframe:true, width:'80%', height:'80%', current: 'Instagram Image {current} of {total}', rel:'instagram_iframe'});
			
			//DRIBBBLE IFRAMES
			$('.dribbble_iframe').colorbox({iframe:true, width:'80%', height:'80%', current: 'Dribbble Thumbnail {current} of {total}', rel:'dribbble_iframe'});
		}
	}
	
	//PUBLIC METHODS
	return{
		
		//LAUNCH ALL THE FOLLOWING METHODS AT PAGE LOAD
		run_at_load : function()
		{
			navigation.init();
			sliders.init();
			images.init();
		},
		
		//LAUNCH ALL THE FOLLOWING METHODS AFTER PAGE LOAD
		run_after_load : function()
		{
			page.init();
		}
	};
}();

//RUN MAVERICK PORTFOLIO MODULE AT PAGE LOAD
$(document).ready(function()
{
	mp_module.run_at_load();
});

//RUN MAVERICK PORTFOLIO MODULE AFTER PAGE LOAD
$(window).load(function()
{  
	mp_module.run_after_load();
});
<?php
#SEND THE OUTPUT BUFFER AND TURN OFF OUTPUT BUFFERING 
ob_end_flush();
?>