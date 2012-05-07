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

#SET FILE TYPE AS CSS
header("content-type: text/css");
?>
/* ERROR CONTAINER */
.mp_errors
{
	display: none;
}

/* FORM FIELD ERRORS */
input.mp_error_field
{
	border: 1px solid #FF0000;
	background-color: #FFEBE8;
}

/* SLIDE CUSTOM POST TYPE ICONS */
#adminmenu #menu-posts-slide .wp-menu-image
{
	background-image: url("<?php bloginfo("template_url") ?>/images/menu-cp-slide-off.png");
	background-repeat: no-repeat;
	background-position: center center;
}

#adminmenu #menu-posts-slide:hover .wp-menu-image,
#adminmenu #menu-posts-slide.wp-has-current-submenu .wp-menu-image
{
	background-image: url("<?php bloginfo("template_url") ?>/images/menu-cp-slide-on.png");
}

#icon-edit.icon32-posts-slide
{
	background-image: url("<?php bloginfo("template_url") ?>/images/icon-cp-slide.png");
	background-repeat: no-repeat;
	background-position: top left;
}

/* PROJECT CUSTOM POST TYPE ICONS */
#adminmenu #menu-posts-project .wp-menu-image
{
	background-image: url("<?php bloginfo("template_url") ?>/images/menu-cp-portfolio-off.png");
	background-repeat: no-repeat;
	background-position: center center;
}

#adminmenu #menu-posts-project:hover .wp-menu-image,
#adminmenu #menu-posts-project.wp-has-current-submenu .wp-menu-image
{
	background-image: url("<?php bloginfo("template_url") ?>/images/menu-cp-portfolio-on.png");
}

#icon-edit.icon32-posts-project
{
	background-image: url("<?php bloginfo("template_url") ?>/images/icon-cp-portfolio.png");
	background-repeat: no-repeat;
	background-position: top left;
}

/* TESTIMONIAL CUSTOM POST TYPE ICONS */
#adminmenu #menu-posts-testimonial .wp-menu-image
{
	background-image: url("<?php bloginfo("template_url") ?>/images/menu-cp-testimonial-off.png");
	background-repeat: no-repeat;
	background-position: center center;
}

#adminmenu #menu-posts-testimonial:hover .wp-menu-image,
#adminmenu #menu-posts-testimonial.wp-has-current-submenu .wp-menu-image
{
	background-image: url("<?php bloginfo("template_url") ?>/images/menu-cp-testimonial-on.png");
}

#icon-edit.icon32-posts-testimonial
{
	background-image: url("<?php bloginfo("template_url") ?>/images/icon-cp-testimonial.png");
	background-repeat: no-repeat;
	background-position: top left;
}
<?php
#SEND THE OUTPUT BUFFER AND TURN OFF OUTPUT BUFFERING 
ob_end_flush();
?>