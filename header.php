<?php  
#TURN ON OUTPUT BUFFERING
if(!ob_start("ob_gzhandler"))
{
	ob_start();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php the_title(); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta property="og:site_name" content="<?php bloginfo("name"); ?>" />
<?php
#FACEBOOK SETTINGS FOR BLOG POSTS
if(is_single())
{
?>
<meta property="og:title" content="<?php the_title(); ?>" />
<meta property="og:url" content="<?php the_permalink(); ?>" />
<meta property="og:type" content="article" />
<?php
}
#FACEBOOK SETTINGS FOR HOME PAGE
if(is_front_page())
{
?>
<meta property="og:title" content="<?php bloginfo("name"); ?>" />
<meta property="og:url" content="<?php bloginfo("siteurl"); ?>" />
<meta property="og:type" content="website" />
<?php
}
?>
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo("stylesheet_url"); ?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo("template_directory"); ?>/css/superfish.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo("template_directory"); ?>/css/colorbox.php" />
<!--[if gte IE 7]><link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo("template_directory"); ?>/css/ie.css" /><![endif]-->
<?php mp_options::mp_display_rss_feeds_header(); ?>
<link rel="pingback" href="<?php bloginfo("pingback_url"); ?>" />
<?php
#LOAD THREADED COMMENTS
if(is_single())
{
	wp_enqueue_script("comment-reply");
}
?>
<?php wp_head(); ?>
<?php
#LOAD MENU FIX FOR BLOG & ORGANIC TABS
if((!is_page() || is_page("blog")) && !is_tax() && !is_singular(array("project", "testimonial", "article")))
{
?>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-fix-menu-blog.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-organic-tabs.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-organic-tabs-initialise.js"></script>
<?php
}
#LOAD MENU FIX FOR PORTFOLIO
if(is_page_template("portfolio.php") || is_tax("portfolio-categories") || is_singular("project"))
{
?>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-fix-menu-portfolio.js"></script>
<?php	
}
#LOAD GALLERIFIC
if(is_singular("project"))
{
?>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-galleriffic.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-galleriffic-portfolio.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-history.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-opacityrollover.js"></script>
<?php
}
?>
<?php
#LOAD HEIGHT FIX
if(!is_front_page())
{
?>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-fix-height.js"></script>
<?php
}
?>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-preload.php"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-hover-intent.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-superfish.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-supersubs.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-superfish-initialise.php"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-colorbox-min.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-colorbox-initialise.js"></script>
<?php
#LOAD ANYTHING SLIDER
if(is_front_page())
{
?>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-anythingslider-min.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-anythingslider-fx-min.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-anythingslider-video-min.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-easing1.2.js"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-anythingslider-home.php"></script>
<script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-jcarousel-lite-min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo("template_directory"); ?>/css/anythingslider.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo("template_directory"); ?>/css/animate.css" />
<?php
}
?>
<!--[if IE]><script type="text/javascript" src="<?php bloginfo("template_directory"); ?>/js/jquery-fix-iframes.js"></script><![endif]-->
<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>

<?php flush(); ?>

<body>

<!-- HEADER - ROW 1 - START -->
<div id="header_row1">

	<!-- HEADER - ROW 1 - WRAPPER - START -->
	<div class="header_wrapper">
	
		<!-- TITLE & DESCRIPTION - START -->
		<div id="header_title_description">
			<p class="logo"><a href="<?php bloginfo("url"); ?>"><?php bloginfo("name"); ?></a></p>
			<p class="description"><?php bloginfo("description"); ?><img src="<?php bloginfo("template_directory"); ?>/images/icon-australia.png" alt="" width="25" height="17" class="country" /></p>
		</div>
		<!-- TITLE & DESCRIPTION - END -->
	
		<!-- SOCIAL - START -->
		<div id="header_social">
		
			<!-- ADDTHIS BUTTON - START -->
			<div class="social_addthis_right">
				<div class="addthis_toolbox addthis_default_style" addthis:title="<?php bloginfo("name"); ?>" addthis:url="<?php bloginfo("url"); ?>"><a class="addthis_counter addthis_pill_style"></a></div>
				<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4dbe7917029ad5b1"></script>
			</div>
			<!-- ADDTHIS BUTTON - END -->
			
			<!-- PINTEREST BUTTON - START -->
			<div class="social_pinterest_right">
				<a href="http://pinterest.com/pin/create/button/?url=<?php bloginfo("url"); ?>" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" alt="Pin It" title="Pin It" /></a><script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
			</div>
			<!-- PINTEREST BUTTON - END -->	
			
			<!-- GOOGLE + BUTTON - START -->
			<div class="social_google_right">				
				<div class="g-plusone" data-size="medium" data-href="<?php bloginfo("url"); ?>"></div>
				<script type="text/javascript">
				  (function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				  })();
				</script>
			</div>
			<!-- GOOGLE + BUTTON - END -->
			
			<!-- TWITTER BUTTON - START -->
			<div class="social_twitter_right"><a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-url="<?php bloginfo("url"); ?>">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
			<!-- TWITTER BUTTON - END -->
			
			<!-- FACEBOOK BUTTON - START -->
			<div class="social_facebook_right"><iframe src="//www.facebook.com/plugins/like.php?href=<?php bloginfo("url"); ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=355386687829880" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe></div>
			<!-- FACEBOOK BUTTON - END -->
	
		</div>
		<!-- SOCIAL - END -->
	
	</div>
	<!-- HEADER - ROW 1 - WRAPPER - END -->

</div>
<!-- HEADER - ROW 1 - END -->

<!-- HEADER - ROW 2 - START -->
<div id="header_row2">

	<!-- HEADER - ROW 2 - WRAPPER - START -->
	<div class="header_wrapper">
	
		<!-- TOP MENU - START -->
		<div id="menu"><?php wp_nav_menu(array("theme_location" => "menu_top", "container" => "none", "menu_class" => "sf-menu",  "fallback_cb" => "")); ?></div>
		<!-- TOP MENU - END -->
		
		<!-- SEARCH - START -->
		<form method="get" id="searchform" action="<?php echo $_SERVER["PHP_SELF"]; ?>"><input type="submit" id="search_button" value="" /><input type="text" value="Search" name="s" id="search_box" onfocus="if(this.value != '') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Search'; }" /></form>
		<!-- SEARCH - END -->
	
	</div>
	<!-- HEADER - ROW 2 - WRAPPER - END -->

</div>
<!-- HEADER - ROW 2 - END -->

<!-- CONTENT WRAPPER - START -->
<div id="content_wrapper">

	<?php get_sidebar(); ?>

	<!-- CONTENT - START -->
	<div id="content">
	
	<?php
	#DISPLAY BREAD CRUMBS
	if(!is_front_page())
	{
	?>
	<!-- BREAD CRUMBS - START -->
	<div id="bread_crumbs"><p><?php if(function_exists("bcn_display")) { bcn_display(); } ?></p></div>
	<!-- BREAD CRUMBS - END -->
	<?php
	}
	?>
	