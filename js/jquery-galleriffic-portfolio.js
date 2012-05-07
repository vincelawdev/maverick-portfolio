jQuery(document).ready(function($)
{
	//INITIALISE OPACITY LEVELS
	opacity_level = 0.67;
	
	//INITIALISE OPACITY LEVELS OF THUMBNAILS, PREVIOUS BUTTON & NEXT BUTTON
	$("#project_gallery_thumbnails ul.thumbs li, #project_gallery_thumbnails a.previous, #project_gallery_thumbnails a.next").opacityrollover(
	{
		mouseOutOpacity:			opacity_level,
		mouseOverOpacity:  			1.0,
		fadeSpeed:         			"fast",
		exemptionSelector: 			".selected"
	});
	
	//INITIALISE GALLERIFFIC
	gallery = $("#project_gallery_thumbnails").galleriffic(
	{
		numThumbs:                 4,							
		preloadAhead:              10,
		maxPagesToShow:			   100,							
		delay:                     500,
		defaultTransitionDuration: 500,
		syncTransitions:           true,
		autoStart:                 false,
		enableTopPager:            false,
		enableBottomPager:         false,
		renderSSControls:          false,
		renderNavControls:         false,
		enableKeyboardNavigation:  true,
		enableHistory:             true,
		imageContainerSel:         "#project_gallery",
		controlsContainerSel:      "",
		captionContainerSel:       "#project_gallery_caption",
		loadingContainerSel:       "", 
		onSlideChange:             function(prevIndex, nextIndex)
		{
			this.find("ul.thumbs").children().eq(prevIndex).fadeTo("fast", opacity_level).end().eq(nextIndex).fadeTo("fast", 1.0);
		},
		onPageTransitionOut: function(callback)
		{
			this.fadeTo("fast", 0.0, callback);
		},
		onPageTransitionIn: function()
		{
			//INITIALISE PREVIOUS & NEXT BUTTONS
			previous_button = this.find("a.previous").css("visibility", "hidden");
			previous_blank_button = this.find("a.previous_blank").css("visibility", "hidden");
			next_button = this.find("a.next").css("visibility", "hidden");
			
			//INITIALISE LAST PAGE
			last_page = this.getNumPages() - 1;
			
			//DISPLAY PREVIOUS BUTTON
			if(this.displayedPage > 0)
			{
				previous_button.css("visibility", "visible");
			}
			
			//DISPLAY NEXT BUTTON
			if(this.displayedPage < last_page)
			{
				next_button.css("visibility", "visible");
			}
			
			//FADE PAGE
			this.fadeTo("fast", 1.0);
		},
		onTransitionIn: function()
		{
			$("#project_gallery").fadeTo("fast", 1.0);
			$("#project_gallery span.image-wrapper").fadeTo("fast", 1.0);
			$("#project_gallery_caption").fadeTo("fast", 1.0);
			$("#project_gallery_caption span.image-caption").fadeTo("fast", 1.0);
			$("#project_gallery_caption").fadeIn("fast", function()
			{
				$("#project_gallery a.project_gallery").colorbox();
			});
		}
    });

	//INITIALISE PREVIOUS BUTTON
	gallery.find("a.previous").click(function(e)
	{
		gallery.previousPage();
		e.preventDefault();
	});

	//INITIALISE NEXT BUTTON
	gallery.find("a.next").click(function(e)
	{
		gallery.nextPage();
		e.preventDefault();
	});

	//THIS FUNCTION SELECTS THE IMAGE OF THE GALLERY
	function pageload(hash)
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
	}

	//INITIALISE HISTORY PLUGIN
	$.historyInit(pageload, "advanced.html");

	//INITIALISE BACK BUTTON
	$("a[rel='history']").live('click', function(e)
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
});