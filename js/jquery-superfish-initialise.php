<?php
#TURN ON OUTPUT BUFFERING
if(!ob_start("ob_gzhandler"))
{
	ob_start();
}

#INITIALISE WP-LOAD.PHP FILE PATH
$wp_include_path = "../wp-load.php";

#SEARCH FOR WP-LOAD.PHP FILE PATH
for($counter = 0; $counter < 10; $counter ++)
{
	#WP-LOAD.PHP FILE DOES NOT EXIST AT THIS PATH
	if(!file_exists($wp_include_path))
	{
		$wp_include_path = "../$wp_include_path";
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
header("content-type: application/x-javascript");
?>
//WAIT FOR PAGE TO LOAD
$(document).ready(function()
{
	//INITIALISE SUPERFISH LEVEL 1 HOME MENU ITEM
	home_button_on = '<img src="<?php bloginfo("template_directory"); ?>/images/menu-home-on.png" alt="" />';
	home_button_off = '<img src="<?php bloginfo("template_directory"); ?>/images/menu-home-off.png" alt="" />';
	$("ul.sf-menu > li.home a").html(home_button_off);
	$("ul.sf-menu > li.home.current-menu-item a").html(home_button_on);
	
	//HOVER SUPERFISH LEVEL 1 HOME MENU ITEM
	$("ul.sf-menu > li.home").not("ul.sf-menu > li.home.current-menu-item").hover(
	function()
	{
		//DISPLAY HOVER HOME ICON
		$("ul.sf-menu > li.home a").html(home_button_on);
	},
	function()
	{
		//DISPLAY NORMAL HOME ICON
		$("ul.sf-menu > li.home a").html(home_button_off);
	});
	
	//DISPLAY SUPERFISH LEVEL 1 MENU DOWN ARROWS
	$("ul.sf-menu > li:has(ul.sub-menu)").append('<span class="arrow_down_off"></span>');
	$("ul.sf-menu > li.current-menu-item:has(ul.sub-menu)").find("span.arrow_down_off").replaceWith('<span class="arrow_down_on"></span>');
	$("ul.sf-menu > li.current-page-ancestor").find("span.arrow_down_off").replaceWith('<span class="arrow_down_on"></span>');
	
	//HOVER SUPERFISH LEVEL 1 MENU DOWN ARROWS
	$("ul.sf-menu > li:has(ul.sub-menu)").not("ul.sf-menu > li.current-page-ancestor").not("ul.sf-menu > li.current-menu-item").hover(
	function()
	{
		//DISPLAY HOVER ARROW
		$(this).find("span.arrow_down_off").replaceWith('<span class="arrow_down_on"></span>');
	},
	function()
	{
		//DISPLAY NORMAL ARROW
		$(this).find("span.arrow_down_on").replaceWith('<span class="arrow_down_off"></span>');
	});
	
	//DISPLAY SUPERFISH LEVEL 2 MENU RIGHT ARROWS
	$("ul.sub-menu > li:has(ul.sub-menu)").find("a:first").append('<span class="arrow_right_off"></span>');
	
	//HOVER SUPERFISH LEVEL 2 MENU RIGHT ARROWS
	$("ul.sub-menu > li:has(ul.sub-menu)").hover(
	function()
	{
		//DISPLAY HOVER ARROW
		$(this).find("span.arrow_right_off").replaceWith('<span class="arrow_right_on"></span>');
	},
	function()
	{
		//DISPLAY NORMAL ARROW
		$(this).find("span.arrow_right_on").replaceWith('<span class="arrow_right_off"></span>');
	});
		
	//INITIALISE SUPERFISH MENUS
	$("ul.sf-menu").supersubs(
	{ 
		minWidth: 8,
		maxWidth: 100,
		extraWidth: 2
	}).superfish();
});
<?php
#SEND THE OUTPUT BUFFER AND TURN OFF OUTPUT BUFFERING 
ob_end_flush();
?>