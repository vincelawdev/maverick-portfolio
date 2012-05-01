//WAIT FOR PAGE TO LOAD
$(document).ready(function()
{
	//CAPTION IMAGES
	$("a.colorbox").colorbox({current: "Image {current} of {total}", rel:"colorbox"});
	
	//INSTAGRAM IFRAMES
	$(".instagram_iframe").colorbox({iframe:true, width:"80%", height:"80%", current: "Instagram Image {current} of {total}", rel:"instagram_iframe"});
	
	//DRIBBBLE IFRAMES
	$(".dribbble_iframe").colorbox({iframe:true, width:"80%", height:"80%", current: "Dribbble Thumbnail {current} of {total}", rel:"dribbble_iframe"});	
});