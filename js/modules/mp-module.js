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
			//INITIALISE CONTENT HEIGHT
			page.content_height = $('#content').outerHeight(true);		
			
            //INITIALISE SIDEBAR HEIGHT IF VISIBLE
            if($('#sidebar').is(':visible'))
            {
            	page.sidebar_height = $('#sidebar').height();
           	}
            //INITIALISE SIDEBAR HEIGHT IF INVISIBLE
            else
            {
            	page.sidebar_height = 0;
            }
            
            //INITIALISE MAXIMUM HEIGHT
            page.max_height = Math.max(page.content_height, page.sidebar_height);
			
			//CONTENT HEIGHT IS SHORTER THAN SIDEBAR HEIGHT
			if(page.content_height < page.sidebar_height)
			{
				//SET THE CONTENT HEIGHT TO THE MAXIMUM HEIGHT
				$('#content').height(page.max_height);
			}
			
			console.log('Window: ' + page.window_width);
			console.log('Content: ' + page.content_height);
			console.log('Sidebar: ' + page.sidebar_height);
			console.log('Max: ' + page.max_height);
		}
	},
    
    //SEARCH BOX
    search =
    {
    	//THIS METHOD INITIALISES THE SEARCH BOX
    	init : function()
        {        
        	//SEARCH BUTTON CLICKED
        	$('.search-menu-button').click(function(event)
            {
            	event.preventDefault();
                
                //INITIALISE SEARCH BUTTON & BOX VARIABLES
                var $search_button = $(this),
                $search_box = $('.search-box');
                
                //SEARCH BUTTON IS INACTIVE
                if(!$search_button.hasClass('active'))
                {
                	//ADD ACTIVE CLASS
                	$search_button.addClass('active');
                    
                    //SLIDE DOWN SEARCH BOX
                    $search_box.slideDown('fast');
                    
                    //FOCUS ON SEARCH BOX FIELD
                    $('.search-box-field').focus();
                }
                //SEARCH BUTTON IS ACTIVE
                else
                {
                	//REMOVE ACTIVE CLASS
                	$search_button.removeClass('active');
                    
                    //SLIDE UP SEARCH BOX
                    $search_box.slideUp('fast');
                }
            });
        }
    },
	
	//NAVIGATION - SUPERFISH, MEANMENU & ORGANIC TABS
	navigation =
	{
		//THIS METHOD LAUNCHES THE superfish_init(), meanmenu_init() & organic_tabs_init() METHODS
		init : function()
		{
			this.superfish_init();
            this.meanmenu_init();
			this.organic_tabs_init();
		},
		
		//THIS METHOD INITIALISES THE SUPERFISH MENU
		superfish_init: function()
		{
        	//APPEND SUPERFISH LEVEL 1 MENU DOWN ARROWS BY DEFAULT
        	navigation.superfish_arrows();
            
            //APPEND OR REMOVE SUPERFISH LEVEL 1 MENU DOWN ARROWS ON PAGE RESIZE
			$(window).resize(function()
			{
				navigation.superfish_arrows();
			});
        
            //HIGHLIGHT SUPERFISH LEVEL 1 MENU DOWN ARROWS
            $('.sf-menu > li.current-menu-item:has(.sub-menu)').find('.arrow-down').addClass('on');
            $('.sf-menu > li.current-page-ancestor, .sf-menu > li.current-menu-ancestor, .sf-menu > li.current-menu-parent').find('.arrow-down').addClass('on');
            
            //HOVER SUPERFISH LEVEL 1 MENU DOWN ARROWS
            $('.sf-menu > li:has(.sub-menu)').not('.sf-menu > li.current-page-ancestor, .sf-menu > li.current-menu-ancestor, .sf-menu > li.current-menu-parent, .sf-menu > li.current-menu-item').hover(function()
            {
                //DISPLAY HOVER ARROW
                $(this).find('.arrow-down').toggleClass('on');
            });
            
            //DISPLAY SUPERFISH LEVEL 2 MENU RIGHT ARROWS
            $('.sub-menu > li:has(.sub-menu)').find('a:first').append('<span class="arrow-right"></span>');
            
            //HOVER SUPERFISH LEVEL 2 MENU RIGHT ARROWS
            $('.sub-menu > li:has(.sub-menu)').hover(function()
            {
                //DISPLAY HOVER ARROW
                $(this).find('.arrow-right').toggleClass('on');
                
                //DISPLAY HOVER LINK COLOUR
                $(this).find('.sf-with-ul').toggleClass('on');
            });
                
            //INITIALISE SUPERFISH MENUS
            $('.sf-menu').supersubs(
            { 
                minWidth: 15,
                maxWidth: 100,
                extraWidth: 1
            }).superfish();
		},
        
        //THIS METHOD APPENDS & REMOVES THE SUPERFISH DOWN ARROWS
        superfish_arrows : function()
        {
        	//APPEND SUPERFISH LEVEL 1 MENU DOWN ARROWS - THIS BREAKS MEANMENU
        	if(page.window_width > 767)
            {
            	//SUPERFISH LEVEL 1 MENU DOWN ARROWS DO NOT EXIST
            	if($('.arrow-down').length == 0)
                {
                	$('.sf-menu > li:has(.sub-menu)').append('<span class="arrow-down"></span>');
                }
            }
            //REMOVE SUPERFISH LEVEL 1 MENU DOWN ARROWS
            else
            {
            	//SUPERFISH LEVEL 1 MENU DOWN ARROWS EXIST
            	if($('.arrow-down').length)
                {
                	$('.arrow-down').remove();
                }
            }
        },
        
        //THIS METHOD INITIALISES THE MEANMENU
        meanmenu_init : function()
        {
        	//INITIALISE MEAN MENU
        	$('#header-row2 #menu').meanmenu(
            {
            	meanScreenWidth: '767',
                meanMenuContainer: '#header-row2 .header-wrapper',
                meanRevealPosition: 'left'
            });
        },
		
		//THIS METHOD INITIALISES THE ORGANIC TABS
		organic_tabs_init : function()
		{
			//POST TABS EXISTS
			if($('#sidebar').find('#post-tabs').length > 0)
			{			
				$('#post-tabs').organicTabs({ 'speed' : 200 });
			}
			
			//COMMENT TABS EXISTS
			if($('#sidebar').find('#comment-tabs').length > 0)
			{			
				$('#comment-tabs').organicTabs({ 'speed' : 200 });
			}
		}
	},
	
	//SLIDERS - FLEXSLIDER
	sliders =
	{
		//THIS METHOD LAUNCHES THE flexslider.init() METHOD
		init : function()
		{
			this.flexslider.init();
		},
		
		//THIS OBJECT CONTAINS THE FLEXSLIDER METHODS & PROPERTIES
		flexslider :
		{
			//THIS METHOD INITIALISES THE FLEXSLIDER
			init : function()
			{						
				/* HOME SLIDES */	
				$('.home-slides')
				.fitVids()
				.flexslider(
				{
					animation: 'slide',
					easing: 'swing',					
					animationLoop: true,
					smoothHeight: true,
					slideshow: true,
					slideshowSpeed: 5000,
					animationSpeed: 300,
					initDelay: 0,
					
					// Usability features
					useCSS: true,
					
					// Primary Controls
					controlNav: true,
					directionNav: true,
										
					// Secondary Navigation
					keyboard: true,
					multipleKeyboard: false,
					mousewheel: true
  				});
				
				/* PAUSE HOME SLIDES WHEN VIDEO SLIDE HAS MOUSE OVER EVENT */
				$('.home-slides .slides .video').mouseover(function()
				{
					$('.flexslider').flexslider("pause");
				});
			},
		}
	},
	
	//IMAGES - GALLERIFIC & COLORBOX
	images = 
	{
		//THIS METHOD LAUNCHES THE gallerific.init() & colorbox_init() METHODS
		init : function()
		{
			this.gallerific.init();
			this.colorbox_init();
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
					//INITIALISE PREVIOUS, NEXT BUTTONS & LAST PAGE
					var previous_button = this.find('a.previous').css('visibility', 'hidden'),
					previous_blank_button = this.find('a.previous_blank').css('visibility', 'hidden'),
					next_button = this.find('a.next').css('visibility', 'hidden'),
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
					var gallery = $('#project_gallery_thumbnails').galleriffic(images.gallerific.gallerific_options);
					
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
						var hash = this.href;
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
        	search.init();
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