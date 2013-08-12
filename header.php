<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" class="no-js">
<head>
<title><?php the_title(); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=yes" />
<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
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
<meta property="og:title" content="<?php bloginfo('name'); ?>" />
<meta property="og:url" content="<?php bloginfo('siteurl'); ?>" />
<meta property="og:type" content="website" />
<?php
}
?>
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); ?>/css/superfish.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); ?>/css/colorbox.php" />
<!--[if gte IE 7]><link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); ?>/css/ie.css" /><![endif]-->
<?php mp_options::mp_display_rss_feeds_header(); ?>
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/modernizr-custom2.6.2.js"></script>
<?php
#LOAD ANYTHING SLIDER
if(is_front_page())
{
?>
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); ?>/css/anythingslider.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); ?>/css/animate.css" />
<?php
}
?>
</head>

<?php flush(); ?>

<body>

<?php
#INITIALISE SOCIAL BUTTON SIZE
$mp_social_button_size = get_option('mp_social_button_size');
?>

<!-- HEADER - ROW 1 - START -->
<div id="header_row1_<?php echo $mp_social_button_size; ?>">

	<!-- HEADER - ROW 1 - WRAPPER - START -->
	<div class="header_wrapper">
	
		<!-- TITLE & DESCRIPTION - START -->
		<div id="header_title_description"><?php mp_options::mp_display_logo(); ?></div>
		<!-- TITLE & DESCRIPTION - END -->
	
		<!-- SOCIAL - START -->
		<div id="header_social">
		
			<?php			
			#DISPLAY SMALL SOCIAL BUTTONS
			if($mp_social_button_size == 'small')
			{
			?>
			<!-- ADDTHIS BUTTON - START -->
			<div class="social_addthis_right_small">
				<div class="addthis_toolbox addthis_default_style" addthis:title="<?php bloginfo('name'); ?>" addthis:url="<?php bloginfo('url'); ?>"><a class="addthis_counter addthis_pill_style"></a></div>
				<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=<?php mp_options::mp_display_addthis_profile_id(); ?>"></script>
			</div>
			<!-- ADDTHIS BUTTON - END -->
			
			<!-- GOOGLE + BUTTON - START -->
			<div class="social_google_right_small">
				<div class="g-plusone" data-size="medium" data-href="<?php bloginfo('url'); ?>"></div>
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
			<div class="social_twitter_right_small"><a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-url="<?php bloginfo('url'); ?>">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
			<!-- TWITTER BUTTON - END -->
			
			<!-- FACEBOOK BUTTON - START -->
			<div class="social_facebook_right_small"><iframe src="//www.facebook.com/plugins/like.php?href=<?php mp_options::mp_display_facebook_like_url(); ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe></div>
			<!-- FACEBOOK BUTTON - END -->
			<?php
			}
			#DISPLAY LARGE SOCIAL BUTTONS
			elseif($mp_social_button_size == 'large')
			{
			?>			
			<!-- ADDTHIS BUTTON - START -->
			<div class="social_addthis_right_large">
				<div class="addthis_toolbox addthis_counter_style" addthis:title="<?php bloginfo('name'); ?>" addthis:url="<?php bloginfo('url'); ?>"><a class="addthis_counter"></a></div>
				<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=<?php mp_options::mp_display_addthis_profile_id(); ?>"></script>
			</div>
			<!-- ADDTHIS BUTTON - END -->
			
			<!-- GOOGLE + BUTTON - START -->
			<div class="social_google_right_large">
				<div class="g-plusone" data-size="tall" data-href="<?php bloginfo('url'); ?>"></div>
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
			<div class="social_twitter_right_large"><a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-count="vertical" data-url="<?php bloginfo('url'); ?>">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
			<!-- TWITTER BUTTON - END -->
			
			<!-- FACEBOOK BUTTON - START -->
			<div class="social_facebook_right_large"><iframe src="//www.facebook.com/plugins/like.php?href=<?php mp_options::mp_display_facebook_like_url(); ?>&amp;send=false&amp;layout=box_count&amp;width=50&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=90" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:60px;" allowTransparency="true"></iframe></div>
			<!-- FACEBOOK BUTTON - END -->
			<?php
			}
			?>
			
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
		<div id="menu"><?php wp_nav_menu(array('theme_location' => 'menu_top', 'container' => 'none', 'menu_class' => 'sf-menu',  'fallback_cb' => '')); ?></div>
		<!-- TOP MENU - END -->
		
		<!-- SEARCH - START -->
		<form method="get" id="searchform" action="<?php echo $_SERVER['PHP_SELF']; ?>"><input type="submit" id="search_button" value="" /><input type="text" value="" name="s" id="search_box" placeholder="Search" /></form>
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
	<div id="bread_crumbs"><p><?php if(function_exists('bcn_display')) { bcn_display(); } ?></p></div>
	<!-- BREAD CRUMBS - END -->
	<?php
	}
	?>
	