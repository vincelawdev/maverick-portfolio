//MAVERICK PORTFOLIO MODULE
var mp_module = function()
{
	//PAGE - WINDOW WIDTH, SIDEBAR HEIGHT, CONTENT HEIGHT, SIDEBAR HEIGHT & MAXIMUM HEIGHT
	var page =
	{
		//INITIALISE WINDOW WIDTH, WINDOW HEIGHT, CONTENT HEIGHT, SIDEBAR HEIGHT & MAXIMUM HEIGHT
		window_width   : $(window).width(),
		window_height  : $(window).height(),
		content_height : 0,
		sidebar_height : 0,
		max_height 	   : 0,
		header_row2_top: $('#header-row2').offset().top,

		//THIS METHOD UPDATES THE CONTENT HEIGHT & WINDOW WIDTH
		init: function()
		{
			//INITIALISE PAGE MEASUREMENTS
			page.page_measurements();
			
			//UPDATE WINDOW WIDTH & HEIGHT ON PAGE RESIZE
			$(window).resize(function()
			{
				//UPDATE WINDOW WIDTH
				page.window_width = $(window).width();
				
				//UPDATE WINDOW HEIGHT
				page.window_height = $(window).height();
				
				//UPDATE PAGE MEASUREMENTS
				//page.page_measurements();
			});
		},
		
		//THIS METHOD RETURNS WHETHER THE SCREEN IS MOBILE
		is_mobile: function()
		{
			//MOBILE SCREENS
			if(page.window_width < 768)
			{
				return true;	
			}
			//NON-MOBILE SCREENS
			else if(page.window_width > 767)
			{
				return false;	
			}
		},
		
		//THIS METHOD UPDATES THE PAGE MEASUREMENTS
		page_measurements: function()
		{
			//INITIALISE CONTENT HEIGHT
			page.content_height = $('#content').outerHeight(true);
			
            //INITIALISE SIDEBAR HEIGHT IF VISIBLE
            if($('#sidebar').is(':visible'))
            {
            	page.sidebar_height = $('#sidebar').height();
           	}
            //INITIALISE SIDEBAR HEIGHT TO ZERO IF INVISIBLE
            else
            {
            	page.sidebar_height = 0;
            }
            
            //INITIALISE MAXIMUM HEIGHT
            page.max_height = Math.max(page.content_height, page.sidebar_height);
			
			//SET THE CONTENT HEIGHT TO THE MAXIMUM HEIGHT
			$('#content').height('auto');
			
			//CONTENT HEIGHT IS SHORTER THAN SIDEBAR HEIGHT
			if(page.content_height < page.sidebar_height)
			{
                console.log('Extend Content Height');

				//SET THE CONTENT HEIGHT TO THE MAXIMUM HEIGHT
				$('#content').height(page.max_height);
			}

            /*
			console.log('Window: ' + page.window_width);
			console.log('Content: ' + page.content_height);
			console.log('Sidebar: ' + page.sidebar_height);
			console.log('Max: ' + page.max_height);
		    */
		}
	},
    
    //SEARCH BOX
    search =
    {
    	//THIS METHOD INITIALISES THE SEARCH BOX
    	init: function()
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
		//THIS METHOD LAUNCHES THE superfish_init(), meanmenu_init(), organic_tabs_init() & sticky_menu_init() METHODS
		init: function()
		{
			this.superfish_init();
            this.meanmenu_init();
			this.organic_tabs_init();
			this.sticky_menu_init();
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
        superfish_arrows: function()
        {
        	//APPEND SUPERFISH LEVEL 1 MENU DOWN ARROWS - THIS BREAKS MEANMENU
        	if(!page.is_mobile())
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
        meanmenu_init: function()
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
		organic_tabs_init: function()
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
		},
		
		//THIS METHOD INITIALISES THE STICKY MENU
		sticky_menu_init: function()
		{
			//DISPLAY STICK MENU ON PAGE SCROLL
			$(window).scroll(function()
			{  
				navigation.sticky_menu_scroll();
			});
			
			//DISPLAY STICK MENU ON PAGE RESIZE
			$(window).resize(function()
			{
				navigation.sticky_menu_resize(); 
			});
		},
		
		//THIS METHOD DISPLAYS THE STICKY MENU ON PAGE SCROLL
		sticky_menu_scroll: function()
		{
			//INITIALISE SCROLL TOP POSITION
			var scroll_top = $(window).scrollTop();

			//USER HAS SCROLLED BELOW HEADER ROW 2 TOP POSITION
			if(scroll_top > page.header_row2_top)
			{
				//MOBILE SCREENS
				if(page.is_mobile())
				{
					//MEANMENU HEIGHT
					var mean_bar_height = $('.mean-bar').height();
					
					//ADD STICKY CLASS TO HEADER ROW 1 & 2
					$('#header-row1').addClass('sticky-mobile');
					$('#header-row2').addClass('sticky-mobile');

                    //ADD STICKY MOBILE MARGIN CLASS TO PAGE WRAPPER
                    $('.page-wrapper').addClass('sticky-mobile-margin');

					//MEANMENU HEIGHT IS TALLER THAN WINDOW HEIGHT
					if(mean_bar_height > page.window_height)
					{
						//DISABLE STICKY MOBILE MENU
						if(!$('#header-row2').hasClass('off'))
						{
							$('#header-row2').addClass('off');
						}
					}
					//MEANMENU HEIGHT IS SHORTER THAN WINDOW HEIGHT
					else if(mean_bar_height <= page.window_height)
					{
						//ENABLE STICKY MOBILE MENU
						if($('#header-row2').hasClass('off'))
						{
							$('#header-row2').removeClass('off');
						}
					}
				}
				//NON-MOBILE SCREENS
				else
				{
					//ADD STICKY CLASS TO HEADER ROW 1 & 2
					$('#header-row1').addClass('sticky-non-mobile');
					$('#header-row2').addClass('sticky-non-mobile');
				}
			}
			//USER HAS SCROLLED ABOVE HEADER ROW 2 TOP POSITION
			else
			{
                //DISABLE STICKY MOBILE MARGIN
                if($('.page-wrapper').hasClass('sticky-mobile-margin'))
                {
                    $('.page-wrapper').removeClass('sticky-mobile-margin');
                }

				//MOBILE SCREENS
				if(page.is_mobile())
				{
					//REMOVE STICKY CLASS FROM HEADER ROW 1 & 2
					$('#header-row1, #header-row2').removeClass('sticky-mobile');
				}
				//NON-MOBILE SCREENS
				else
				{
					//REMOVE STICKY CLASS FROM HEADER ROW 1 & 2
					$('#header-row1, #header-row2').removeClass('sticky-non-mobile');
				}
			}
		},
		
		//THIS METHOD CHANGES THE STICKY MENU ON PAGE RESIZE
		sticky_menu_resize: function()
		{
			//MOBILE SCREENS
			if(page.is_mobile())
			{
				//STICKY MENU EXISTS
				if($('.sticky-non-mobile').length)
				{
					//CHANGE STICKY MENU CLASS TO MOBILE
					$('#header-row1, #header-row2').removeClass('sticky-non-mobile').addClass('sticky-mobile');
				}
			}
			//NON-MOBILE SCREENS
			else
			{
				//STICKY MENU EXISTS
				if($('.sticky-mobile').length)
				{
					//CHANGE STICKY MENU CLASS TO NON-MOBILE
					$('#header-row1, #header-row2').removeClass('sticky-mobile').addClass('sticky-non-mobile');
				}
			}
		}
	},
	
	//SLIDERS - FLEXSLIDER
	sliders =
	{
		//THIS METHOD LAUNCHES THE flexslider.init() METHOD
		init: function()
		{
			this.flexslider.init();
		},
		
		//THIS OBJECT CONTAINS THE FLEXSLIDER METHODS & PROPERTIES
		flexslider :
		{
			//THIS METHOD INITIALISES THE FLEXSLIDER
			init: function()
			{
				//HOME SLIDES OPTIONS
				var home_slides_options =
				{
					animation: 'slide',
					easing: 'swing',					
					animationLoop: true,
					smoothHeight: true,
					slideshow: true,
					slideshowSpeed: 5000,
					animationSpeed: 300,
					initDelay: 0,
					
					//USABILITY FEATURES
					useCSS: true,
					
					//PRIMARY CONTROLS
					controlNav: true,
					directionNav: true,
										
					//SECONDARY NAVIGATION
					keyboard: true,
					multipleKeyboard: false,
					mousewheel: true,
					
					//CALLBACK API
					after: function()
					{
						page.page_measurements();
					}
				},
				//HOME PROJECTS SLIDES OPTIONS
				home_slides_projects_options =
				{
					animation: 'slide',
					easing: 'swing',					
					animationLoop: true,
					smoothHeight: false,
					slideshow: false,
					slideshowSpeed: 5000,
					animationSpeed: 300,
					initDelay: 0,
					
					//CAROUSEL OPTIONS
					itemWidth: 320,
					itemMargin: 0,
					minItems: sliders.flexslider.home_slider_projects_items(),
					maxItems: sliders.flexslider.home_slider_projects_items(),
					move: 0,
									
					//USABILITY FEATURES
					useCSS: true,
					
					//PRIMARY CONTROLS
					controlNav: false,
					directionNav: true,
										
					//SECONDARY NAVIGATION
					keyboard: true,
					multipleKeyboard: false,
					mousewheel: true,
					
					//CALLBACK API
					after: function()
					{
						page.page_measurements();
					}	
				},
				//PORTFOLIO PROJECT SLIDES OPTIONS
				portfolio_slides_projects_options =
				{
					animation: 'slide',
					easing: 'swing',					
					animationLoop: true,
					smoothHeight: true,
					slideshow: false,
					slideshowSpeed: 5000,
					animationSpeed: 300,
					initDelay: 0,
					sync: ".project-slides-carousel",
					
					//USABILITY FEATURES
					useCSS: true,
					
					//PRIMARY CONTROLS
					controlNav: false,
					directionNav: true,
										
					//SECONDARY NAVIGATION
					keyboard: true,
					multipleKeyboard: false,
					mousewheel: true,
					
					//CALLBACK API
					after: function()
					{
						page.page_measurements();
					}
				},
				//PORTFOLIO PROJECT SLIDES CAROUSEL OPTIONS
				portfolio_slides_projects_carousel_options =
				{
					animation: 'slide',
					easing: 'swing',					
					animationLoop: true,
					smoothHeight: false,
					slideshow: false,
					slideshowSpeed: 5000,
					animationSpeed: 300,
					initDelay: 0,
					asNavFor: '.project-slides',
					
					//CAROUSEL OPTIONS
					itemWidth: 320,
					itemMargin: 0,
					minItems: 4,
					maxItems: 4,
					move: 0,
									
					//USABILITY FEATURES
					useCSS: true,
					
					//PRIMARY CONTROLS
					controlNav: false,
					directionNav: true,
										
					//SECONDARY NAVIGATION
					keyboard: true,
					multipleKeyboard: false,
					mousewheel: true,
					
					//CALLBACK API
					after: function()
					{
						page.page_measurements();
					}
				}
				
				//HOME SLIDES EXIST
				if($('.home-slides').length > 0)
				{
					//INITIALISE FLEXSLIDER FOR HOME SLIDES
					$('.home-slides').fitVids().flexslider(home_slides_options);
					
					//PAUSE HOME SLIDES WHEN VIDEO SLIDE HAS MOUSE OVER EVENT
					$('.home-slides .slides .video').mouseover(function()
					{
						$('.home-slides').flexslider('pause');
					});
				}
							
				//HOME PROJECTS SLIDES EXIST
				if($('.home-slides-projects').length > 0)
				{
					//INITIALISE FLEXSLIDER FOR HOME PROJECTS SLIDES
					$('.home-slides-projects').flexslider(home_slides_projects_options);
				}
				
				//PORTFOLIO PROJECT SLIDES CAROUSEL EXIST
				if($('.project-slides-carousel').length > 0)
				{
					//INITIALISE PROJECT SLIDES CAROUSEL
					$('.project-slides-carousel').flexslider(portfolio_slides_projects_carousel_options);
				}

				//PORTFOLIO PROJECT SLIDES EXIST
				if($('.project-slides').length > 0)
				{
					//INITIALISE PROJECT SLIDES
					$('.project-slides').flexslider(portfolio_slides_projects_options);
				}

                //DETERMINE NUMBER OF HOME PROJECT SLIDES ON PAGE RESIZE
                this.home_slider_projects_items_resize();
			},
			
			//THIS METHOD INITIALISES THE HOME PROJECT SLIDER ITEMS
			home_slider_projects_items: function()
			{
				//MOBILE SCREENS
				if(page.is_mobile())
				{
					return 1;
				}
				//NON-MOBILE SCREENS
				else
				{
                    //TABLET - PORTRAIT - 768 TO 1023
                    if(page.window_width >= 767 && page.window_width <= 1023)
                    {
                        return 2;
                    }
                    //TABLET - LANDSCAPE & DESKTOPS - 1024 TO 1399
                    else if(page.window_width >= 1024 && page.window_width <= 1399)
                    {
                        return 3;
                    }
                    //DESKTOPS - 1400 & GREATER
                    else if(page.window_width >= 1400)
                    {
                        return 4;
                    }
				}
			},

            //THIS METHOD INITIALISES THE HOME PROJECT SLIDER ITEMS ON PAGE RESIZE
            home_slider_projects_items_resize: function()
            {
                //HOME PROJECTS SLIDES EXIST
                if($('.home-slides-projects').length > 0)
                {
                    $(window).resize(function()
                    {
                        //SET MINIMUM & MAXIMUM NUMBER OF ITEMS TO HOME PROJECTS SLIDES
                        $('.home-slides-projects').data('flexslider').vars.minItems = sliders.flexslider.home_slider_projects_items();
                        $('.home-slides-projects').data('flexslider').vars.maxItems = sliders.flexslider.home_slider_projects_items();

                        //SET MAXIMUM HEIGHT TO HOME PROJECTS SLIDES ON PAGE RESIZE
                        sliders.flexslider.home_slider_projects_items_equal_height();
                    });
                }
            },

            //THIS METHOD SETS THE MAXIMUM HEIGHT OF THE HOME PROJECT SLIDES
            home_slider_projects_items_equal_height: function()
            {
                //HOME PROJECTS SLIDES EXIST
                if($('.home-slides-projects').length > 0)
                {
                    //INITIALISE MAXIMUM HEIGHT OF SLIDES
                    var max_height = -1;

                    $('.home-slides-projects .slides li').each(function()
                    {
                        var height = $(this).height();

                        if(height > max_height)
                        {
                            max_height = height;
                        }
                    });

                    console.log(max_height);

                    max_height = max_height - 20;

                    //SET MAXIMUM HEIGHT TO SLIDES
                    $('.home-slides-projects .slides li .home-slides-projects-item-wrapper').each(function()
                    {
                        $(this).height(max_height);
                    });
                }
            }
		}
	},
	
	//PORTFOLIO - TILES
	portfolio =
	{
		init: function()
		{
            //PORTFOLIO TILES EXIST
            if($('#projects').length > 0)
            {
                //SET MAXIMUM HEIGHT TO PORTFOLIO TILES ON PAGE READY
                portfolio.tiles_equal_height();

                //SET MAXIMUM HEIGHT TO PORTFOLIO TILES ON PAGE RESIZE
                $(window).resize(function()
                {
                    portfolio.tiles_equal_height();
                });
            }
		},
		
		//THIS METHOD SETS THE MAXIMUM HEIGHT OF THE TILES
		tiles_equal_height: function()
		{
			//INITIALISE MAXIMUM HEIGHT OF TILES
			var max_height = -1;
			
			//SCREEN WIDTH IS NOT MOBILE
			if(!page.is_mobile())
			{
				$('#projects li').each(function()
				{
					var height = $(this).height();
					
					if(height > max_height)
					{
						max_height = height;
					}
				});
			}
			//SCREEN WIDTH IS MOBILE
			else
			{
				max_height = 'auto';
			}
			
			//SET MAXIMUM HEIGHT TO TILES
			$('#projects li').each(function()
			{
				$(this).height(max_height); 
			});
		}
	},
	
	//IMAGES - COLORBOX
	images = 
	{
		//THIS METHOD LAUNCHES THE colorbox_init() METHODS
		init: function()
		{
			this.colorbox_init();
		},
		
		//THIS METHOD INITIALISES THE COLORBOX
		colorbox_init: function()
		{			
			//MOBILE SCREENS
			if(page.is_mobile())
			{
				//CAPTION IMAGES
				$('a.colorbox').colorbox({ width:'80%', height:'80%', title: '&nbsp;', current: '', rel: 'colorbox' });
					
				//PROJECT IMAGE
				$('.project-image').colorbox({ width:'80%', height:'80%', title: '&nbsp;', current: '', rel: 'project-image' });
				
				//INSTAGRAM IFRAMES
				$('.instagram-iframe').colorbox({ iframe:true, width:'80%', height:'80%', title: '&nbsp;', current: '', rel: 'instagram_iframe' });
				
				//DRIBBBLE IFRAMES
				$('.dribbble-iframe').colorbox({ iframe:true, width:'80%', height:'80%', title: '&nbsp;', current: '', rel:  'dribbble-iframe' });
			}
			//NON-MOBILE SCREENS
			else
			{				
				//CAPTION IMAGES
				$('a.colorbox').colorbox({ width:'80%', height:'80%', current: 'Image {current} of {total}', rel:'colorbox' });
				
				//PROJECT IMAGE
				$('.project-image').colorbox({ width:'80%', height:'80%', current: 'Project Image {current} of {total}', rel: 'project-image' });
				
				//INSTAGRAM IFRAMES
				$('.instagram-iframe').colorbox({ iframe:true, width:'80%', height:'80%', current: 'Instagram Image {current} of {total}', rel: 'instagram_iframe' });
				
				//DRIBBBLE IFRAMES
				$('.dribbble-iframe').colorbox({ iframe:true, width:'80%', height:'80%', current: 'Dribbble Thumbnail {current} of {total}', rel:  'dribbble-iframe' });
			}
		}
	}
	
	//PUBLIC METHODS
	return{
		
		//LAUNCH ALL THE FOLLOWING METHODS AT PAGE LOAD
		run_at_load: function()
		{
        	search.init();
			navigation.init();
			sliders.init();
			images.init();
			portfolio.init();
		},
		
		//LAUNCH ALL THE FOLLOWING METHODS AFTER PAGE LOAD
		run_after_load: function()
		{
			page.init();
            sliders.flexslider.home_slider_projects_items_equal_height();
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