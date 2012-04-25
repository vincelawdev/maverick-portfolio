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
	//PRELOAD IMAGES
	preload1 = new Image();
	preload2 = new Image();
	preload3 = new Image();
	preload4 = new Image();
	preload5 = new Image();
	preload1.src = "<?php bloginfo("template_directory"); ?>/images/arrow-menu-down-on.png";
	preload2.src = "<?php bloginfo("template_directory"); ?>/images/arrow-menu-right-on.png";
	preload3.src = "<?php bloginfo("template_directory"); ?>/images/button-search-on.png";
	preload4.src = "<?php bloginfo("template_directory"); ?>/images/menu-home-on.png";
	preload5.src = "<?php bloginfo("template_directory"); ?>/images/menu-home-off.png";
});
<?php
#SEND THE OUTPUT BUFFER AND TURN OFF OUTPUT BUFFERING 
ob_end_flush();
?>