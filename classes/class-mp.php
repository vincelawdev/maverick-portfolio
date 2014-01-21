<?php
/**************************************************************************

TABLE OF CONTENTS

1. CONSTRUCTOR FUNCTION
2. THEME OPTION FUNCTIONS
3. LOGO FUNCTIONS
4. ARTICLE FUNCTIONS
5. SLIDE FUNCTIONS
6. PROJECT FUNCTIONS
7. TESTIMONIAL FUNCTIONS
8. AUTHOR FUNCTIONS
9. SOCIAL FUNCTIONS
10. BLOG & COMMENT FUNCTIONS
11. RSS FUNCTIONS
12. AD FUNCTIONS

**************************************************************************/

#THIS CLASS INITIALISES THE THEME OPTIONS
class mp_options
{
	/**************************************************************************
	1. CONSTRUCTOR FUNCTION
	**************************************************************************/
	
	#THIS FUNCTION INITIALISES THE THEME OPTIONS
	function __construct()
	{
		#REMOVE UNNECESSARY META DATA FROM WORDPRESS HEAD
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'start_post_rel_link');
		remove_action('wp_head', 'index_rel_link');
		remove_action('wp_head', 'adjacent_posts_rel_link');
		remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
		remove_action('wp_head', 'parent_post_rel_link');
		remove_action('wp_head', 'parent_post_rel_link_wp_head');
		
		#ENABLE POST THUMBNAILS
		add_theme_support('post-thumbnails');
		
		#INITIALISE MENUS
		register_nav_menu('menu_top', 'Top Menu');
		register_nav_menu('menu_footer', 'Footer Menu');
		add_filter('nav_menu_css_class', array('mp_options', 'mp_menu_fix'), 10, 2);
		
		#ENABLE SIDEBAR WIDGETS
		register_sidebar(array('before_widget' => '<div class="sidebar-box">','after_widget' => '</div>', 'before_title' => '<h4>', 'after_title' => '</h4>',));
		
		#INITIALISE JQUERY LIBRARY
		add_action('init', array('mp_options', 'mp_jquery'));
		
		#INITIALISE THEME OPTIONS
		add_action('admin_menu', array('mp_options', 'mp_admin_menu'));
		add_action('admin_init', array('mp_options', 'mp_theme_settings'));
		
		#INITIALISE THEME ADMIN JAVASCRIPT & CSS
		add_action('admin_head', array('mp_options', 'mp_admin_head'));
		
		#INITIALISE ARTICLE CUSTOM POST TYPE
		add_action('init', array('mp_options', 'mp_custom_posts_articles'));
		add_action('init', array('mp_options', 'mp_custom_taxonomies_article_directories'));
		add_filter('manage_edit-article_columns', array('mp_options', 'mp_article_edit_columns'));
		add_filter('manage_edit-article_sortable_columns', array('mp_options', 'mp_article_sortable_columns'));
		add_action('manage_article_posts_custom_column',  array('mp_options', 'mp_article_custom_columns'));
		
		#INITIALISE ARTICLE META BOX
		add_action('admin_init', array('mp_options', 'mp_meta_boxes_article'));
		
		#INITIALISE SLIDE CUSTOM POST TYPE
		add_action('init', array('mp_options', 'mp_custom_posts_slides'));
		add_filter('manage_edit-slide_columns', array('mp_options', 'mp_slide_edit_columns'));
		add_action('manage_slide_posts_custom_column',  array('mp_options', 'mp_slide_custom_columns'));
		
		#INITIALISE SLIDE META BOX
		add_action('admin_init', array('mp_options', 'mp_meta_boxes_slide'));
		
		#INITIALISE PROJECT CUSTOM POST TYPE & TAXONOMIES
		add_action('init', array('mp_options', 'mp_custom_posts_project'));
		add_action('init', array('mp_options', 'mp_custom_taxonomies_project_categories'));
		add_action('init', array('mp_options', 'mp_custom_taxonomies_project_scope'));
		add_action('init', array('mp_options', 'mp_custom_taxonomies_project_skills'));
		add_filter('manage_edit-project_columns', array('mp_options', 'mp_project_edit_columns'));
		add_filter('manage_edit-project_sortable_columns', array('mp_options', 'mp_project_sortable_columns'));
		add_action('manage_project_posts_custom_column',  array('mp_options', 'mp_project_custom_columns'));
		
		#INITIALISE PROJECT META BOX
		add_action('admin_init', array('mp_options', 'mp_meta_boxes_project'));
		
		#INITIALISE TESTIMONIAL CUSTOM POST TYPE
		add_action('init', array('mp_options', 'mp_custom_posts_testimonials'));
		add_filter('manage_edit-testimonial_columns', array('mp_options', 'mp_testimonial_edit_columns'));
		add_filter('manage_edit-testimonial_sortable_columns', array('mp_options', 'mp_testimonial_sortable_columns'));
		add_action('manage_testimonial_posts_custom_column',  array('mp_options', 'mp_testimonial_custom_columns'));
		
		#INITIALISE TESTIMONIAL META BOX
		add_action('admin_init', array('mp_options', 'mp_meta_boxes_testimonial'));
		
		#INITIALISE TINYMCE EDITOR FOR USER BIOGRAPHY IN WORDPRESS 3.3 +
		if(function_exists('wp_editor') && current_user_can('edit_posts'))
		{
			#REPLACE BIOGRAPHY FIELD WITH TINYMCE EDITOR
			add_action('show_user_profile', array('mp_options', 'mp_tinymce_biography'));
			add_action('edit_user_profile', array('mp_options', 'mp_tinymce_biography'));
			
			#REMOVE TEXTAREA FILTERS FROM BIOGRAPHY FIELD
			remove_all_filters('pre_user_description');
			
			#ADD CONTENT FILTERS TO THE BIOGRAPHY FIELD
			add_filter('get_the_author_description', 'wptexturize');
			add_filter('get_the_author_description', 'convert_chars');
			add_filter('get_the_author_description', 'wpautop');
		}
		
		#INITIALISE USER CONTACT INFO FIELDS
		add_filter('user_contactmethods', array('mp_options', 'mp_contact_info'));
		
		#INITIALISE SHORTCODES
		add_shortcode('testimonial', array('mp_options', 'mp_testimonial_shortcode'));
		add_shortcode('rss', array('mp_options', 'mp_rss_shortcode'));
		
		#INITIALISE TRACKING CODE IN FOOTER
		add_action('wp_footer', array('mp_options', 'mp_tracking'));
		
		#INITIALISE POST EXCERPT READ THE REST LINKS
		if(!function_exists('the_advanced_excerpt'))
		{
			add_filter('excerpt_more', array('mp_options', 'mp_excerpt_more'));
		}
	}
	
	/**************************************************************************
	2. THEME OPTION FUNCTIONS
	**************************************************************************/
	
	#THIS FUNCTION ADDS THE THEME OPTIONS MENU ITEM TO THE APPEARANCE MENU
	public function mp_admin_menu()
	{
		add_theme_page('Maverick Portfolio Options', 'Options', 'administrator', 'mp_options', array('mp_options', 'mp_options_page'));
	}
	
	#THIS FUNCTION ADDS A CURRENT MENU ITEM CLASS TO THE CURRENT MENU ITEM
	public function mp_menu_fix($classes, $item)
	{
		#RETRIEVE THE POST
		global $post;
		
		#BLOG PAGE OR CHILD
		if((!is_page($post->ID) || is_page('blog')) && !is_tax() && !is_singular(array('project', 'testimonial', 'article')))
		{
			#ADD CURRENT MENU ITEM CLASS TO BLOG MENU ITEM
			if($classes[0] == 'blog')
			{
				$classes[] = 'current-menu-item';
			}
		}
		
		#ARTICLES OR CHILD
		if(is_page_template('articles.php') || is_tax('article-directories') || is_singular('article'))
		{
			#ADD CURRENT MENU ITEM CLASS TO ARTICLES MENU ITEM
			if($classes[0] == 'articles')
			{
				$classes[] = 'current-menu-item';
			}
		}
		
		#PORTFOLIO OR CHILD
		if(is_page_template('portfolio.php') || is_tax('portfolio-categories') || is_singular('project'))
		{
			#ADD CURRENT MENU ITEM CLASS TO PORTFOLIO MENU ITEM
			if($classes[0] == 'portfolio')
			{
				$classes[] = 'current-menu-item';
			}
		}
		
		#RETURN MENU ITEM CLASSES
		return $classes;
	}
	
	#THIS FUNCTION REGISTERS THE THEME OPTION SETTINGS
	public function mp_theme_settings()
	{
		register_setting('mp_settings_author', 'mp_author');
		register_setting('mp_settings_header', 'mp_logo');
		register_setting('mp_settings_header', 'mp_logo_image');
		register_setting('mp_settings_header', 'mp_logo_image_mobile');
		register_setting('mp_settings_header', 'mp_social_button_size');
		register_setting('mp_settings_header', 'mp_facebook_like_url');
		register_setting('mp_settings_header', 'mp_addthis_profile_id');		
		register_setting('mp_settings_footer', 'mp_footer_dribbble');
		register_setting('mp_settings_footer', 'mp_footer_twitter');
		register_setting('mp_settings_rss', 'mp_feedburner_rss');
		register_setting('mp_settings_rss', 'mp_feedburner_email');
		register_setting('mp_settings_rss', 'mp_rss_articles');
		register_setting('mp_settings_rss', 'mp_rss_slides');
		register_setting('mp_settings_rss', 'mp_rss_projects');
		register_setting('mp_settings_rss', 'mp_rss_testimonials');
		register_setting('mp_settings_rss', 'mp_rss_external');
		register_setting('mp_settings_rss', 'mp_rss_comments');
		register_setting('mp_settings_sidebar', 'mp_sidebar_ads');
		register_setting('mp_settings_sidebar', 'mp_sidebar_ads_square1');
		register_setting('mp_settings_sidebar', 'mp_sidebar_ads_square2');
		register_setting('mp_settings_sidebar', 'mp_sidebar_ads_square3');
		register_setting('mp_settings_sidebar', 'mp_sidebar_ads_square4');
		register_setting('mp_settings_sidebar', 'mp_sidebar_ads_square5');
		register_setting('mp_settings_sidebar', 'mp_sidebar_ads_square6');
		register_setting('mp_settings_sidebar', 'mp_facebook_like_box');
		register_setting('mp_settings_sidebar', 'mp_posts_recent_number');
		register_setting('mp_settings_sidebar', 'mp_posts_popular_number');
		register_setting('mp_settings_sidebar', 'mp_posts_comments_number');
		register_setting('mp_settings_sidebar', 'mp_comments_recent_number');
		register_setting('mp_settings_sidebar', 'mp_comments_commenters_number');
		register_setting('mp_settings_tracking', 'mp_tracking');
	}
	
	#THIS FUNCTION RESETS THE THEME OPTION SETTINGS
	public function mp_reset_options($option)
	{
		#RESET OPTIONS
		switch($option)
		{
			#AUTHOR
			case 'author':
			
				update_option('mp_author', 1);
				
				break;
				
			#HEADER
			case 'header':
			
				update_option('mp_logo', 0);
				update_option('mp_logo_image', '');
				update_option('mp_logo_image_mobile', '');
				update_option('mp_social_button_size', 'small');
				update_option('mp_facebook_like_url', '');
				update_option('mp_addthis_profile_id', '');
				
			#FOOTER
			case 'footer':
			
				update_option('mp_footer_dribbble', 0);
				update_option('mp_footer_twitter', 1);
				
				break;
				
			#RSS
			case 'rss':
				
				update_option('mp_feedburner_rss', '');
				update_option('mp_feedburner_email', '');
				update_option('mp_rss_articles', 1);
				update_option('mp_rss_slides', 1);
				update_option('mp_rss_projects', 1);
				update_option('mp_rss_testimonials', 1);
				update_option('mp_rss_external', '');
				update_option('mp_rss_comments', 1);
				
				break;
				
			#SIDEBAR
			case 'sidebar':
				
				update_option('mp_sidebar_ads', 0);
				update_option('mp_sidebar_ads_square1', '');
				update_option('mp_sidebar_ads_square2', '');
				update_option('mp_sidebar_ads_square3', '');
				update_option('mp_sidebar_ads_square4', '');
				update_option('mp_sidebar_ads_square5', '');
				update_option('mp_sidebar_ads_square6', '');				
				update_option('mp_facebook_like_box', '');
				update_option('mp_posts_recent_number', 5);
				update_option('mp_posts_popular_number', 5);
				update_option('mp_posts_comments_number', 5);
				update_option('mp_comments_recent_number', 5);
				update_option('mp_comments_commenters_number', 10);
				
				break;
				
			#TRACKING
			case 'tracking':
			
				update_option('mp_tracking', '');
				
				break;
		}
	}
	
	#THIS FUNCTION DISPLAYS THE THEME'S OPTIONS PAGE
	public function mp_options_page()
	{
		#INITIALISE SUB PAGE
		$sub_page = $_REQUEST['sub_page'];
		
		#SET DEFAULT SUB PAGE TO HEADER
		if(empty($sub_page))
		{
			$sub_page = 'header';
		}
		
		#LAUNCH VALIDATOR
		echo '<script>jQuery(document).ready(function() { mp_module_admin.run_validator_options(); });</script>';		
		?>
		<div id="mp-options" class="wrap">
			
			<div class="icon32" id="icon-tools"><br></div>
			
			<h2>Maverick Portfolio Options</h2>
			
			<ul style="display: block">
				<li style="display: inline"><?php if($sub_page == 'header') { echo '<strong>Header</strong>'; } else { ?><a href="<?php bloginfo('wpurl'); ?>/wp-admin/themes.php?page=mp_options&sub_page=header">Header</a><?php } ?></li>
				<li style="display: inline"><?php if($sub_page == 'footer') { echo '<strong>Footer</strong>'; } else { ?><a href="<?php bloginfo('wpurl'); ?>/wp-admin/themes.php?page=mp_options&sub_page=footer">Footer</a><?php } ?></li>
				<li style="display: inline"><?php if($sub_page == 'sidebar') { echo '<strong>Sidebar</strong>'; } else { ?><a href="<?php bloginfo('wpurl'); ?>/wp-admin/themes.php?page=mp_options&sub_page=sidebar">Sidebar</a><?php } ?></li>
				<li style="display: inline"><?php if($sub_page == 'author' || empty($sub_page)) { echo '<strong>Author</strong>'; } else { ?><a href="<?php bloginfo('wpurl'); ?>/wp-admin/themes.php?page=mp_options&sub_page=author">Author</a><?php } ?></li>
				<li style="display: inline"><?php if($sub_page == 'rss') { echo '<strong>RSS</strong>'; } else { ?><a href="<?php bloginfo('wpurl'); ?>/wp-admin/themes.php?page=mp_options&sub_page=rss">RSS</a><?php } ?></li>
				<li style="display: inline"><?php if($sub_page == 'tracking') { echo '<strong>Tracking</strong>'; } else { ?><a href="<?php bloginfo('wpurl'); ?>/wp-admin/themes.php?page=mp_options&sub_page=tracking">Tracking</a><?php } ?></li>
				<li style="display: inline"><?php if($sub_page == 'reset') { echo '<strong>Reset</strong>'; } else { ?><a href="<?php bloginfo('wpurl'); ?>/wp-admin/themes.php?page=mp_options&sub_page=reset">Reset</a><?php } ?></li>
				<li style="display: inline"><a href="http://www.vincelaw.com.au/contact/" target="_blank">Support</a></li>			
			</ul>
			
		</div>
		<?php
		#DISPLAY SUB PAGES
		switch($sub_page)
		{
			#AUTHOR
			case 'author':
				
				#DISPLAY UPDATE MESSAGE
				if(isset($_GET['settings-updated']) && ($_GET['settings-updated'] == true))
				{
				?>
				<div class="updated fade"><p><strong><?php _e('Your Author options have been saved.'); ?></strong></p></div>
				<?php
				}
				?>
				
				<form method="post" action="options.php">
				<?php
				settings_fields('mp_settings_author');
				
				#DISPLAY AUTHORS
				mp_options::mp_option_field('Author', '', true, true, 'Author', 'author', 'mp_author', 'mp_author', 'Select the author you wish to display in the sidebar and footer', '', true);
				?>
			
				</form>
			
				<?php
				break;
				
			#HEADER
				case 'header':
				
				#DISPLAY UPDATE MESSAGE
				if(isset($_GET['settings-updated']) && ($_GET['settings-updated'] == true))
				{
				?>
				<div class="updated fade"><p><strong><?php _e('Your Header options have been saved.'); ?></strong></p></div>
				<?php
				}
				?>
				
				<div id="mp_header_errors" class="mp_errors error"></div>
				
				<form id="mp_header" method="post" action="options.php">
				<?php
				settings_fields('mp_settings_header');

				#INITIALISE LOGO DESCRIPTION
				$logo_description = '<p>For best results, please use a logo image with a transparent background. If the logo is disabled, your <a href="' . get_bloginfo('siteurl') . '/wp-admin/options-general.php" target="_blank">Site Title and Tagline</a> will be displayed instead.</p>';

				#INITIALISE MOBILE LOGO IMAGE
				$mp_logo_image_mobile = get_option('mp_logo_image_mobile');
				
				#PREPEND LOGO DESCRIPTION WITH MOBILE LOGO IMAGE
				if(!empty($mp_logo_image_mobile))
				{
					$logo_description = '<p><img src="' . $mp_logo_image_mobile . '" alt=""></p>' . $logo_description;
				}

				#INITIALISE LOGO IMAGE
				$mp_logo_image = get_option('mp_logo_image');
				
				#PREPEND LOGO DESCRIPTION WITH LOGO IMAGE
				if(!empty($mp_logo_image))
				{
					$logo_description = '<p><img src="' . $mp_logo_image . '" alt=""></p>' . $logo_description;
				}				
				
				#DISPLAY LOGO STATUS
				mp_options::mp_option_field('Logo', $logo_description, true, false, 'Enable', 'yes_no', 'mp_logo', 'mp_logo', 'Select whether you wish to enable the logo', 'No', false);

				#DISPLAY LOGO IMAGE
				mp_options::mp_option_field('', '', true, true, 'Logo Image', 'media_upload', 'mp_logo_image', 'mp_logo_image', 'Enter the logo image URL', '', false);
				
				#DISPLAY MOBILE LOGO IMAGE
				mp_options::mp_option_field('', '', true, true, 'Mobile Logo Image', 'media_upload', 'mp_logo_image_mobile', 'mp_logo_image_mobile', 'Enter the mobile logo image URL', '', true);			
				
				#INITIALISE SOCIAL BUTTON SIZE DESCRIPTION
				$social_button_description = '<p><img src="' . get_bloginfo('template_directory') . '/images/theme-options-social-buttons.jpg" width="335" height="92" alt=""></p>';
				
				#DISPLAY SOCIAL BUTTON SIZE
				mp_options::mp_option_field('Social Buttons', $social_button_description, true, true, 'Size', 'small_large', 'mp_social_button_size', 'mp_social_button_size', 'Select the size of the social buttons', 'small', true);
				
				#INITIALISE FACEBOOK LIKE BUTTON DESCRIPTION
				$facebook_description = '<p>The Facebook Like Button will use your Site Address (URL): <a href="' . get_bloginfo('siteurl') . '" target="_blank">' . get_bloginfo('siteurl') . '</a> by default unless you specify a different Like URL.</p>';
				
				#DISPLAY FACEBOOK LIKE BUTTON
				mp_options::mp_option_field('Facebook Like Button', $facebook_description, true, true, 'Like URL', 'text', 'mp_facebook_like_url', 'mp_facebook_like_url', 'Enter the Like URL of the Facebook Like button', '', true);
				
				#INITIALISE ADDTHIS PROFILE ID DESCRIPTION
				$addthis_description = '<p>Register an <a href="http://www.addthis.com/" target="_blank">AddThis account</a> and login to your <a href="https://www.addthis.com/settings/publisher" target="_blank">AddThis Profiles</a> to get your Profile ID.</p>';
				
				#DISPLAY ADDTHIS PROFILE ID
				mp_options::mp_option_field('AddThis Share Button', $addthis_description, true, false, 'Profile ID', 'text', 'mp_addthis_profile_id', 'mp_addthis_profile_id', 'Enter the Profile ID of your AddThis account', '', true);
				?>
				
				</form>
			
				<?php
				break;
				
			#FOOTER
			case 'footer':
				
				#DISPLAY UPDATE MESSAGE
				if(isset($_GET['settings-updated']) && ($_GET['settings-updated'] == true))
				{
				?>
				<div class="updated fade"><p><strong><?php _e('Your Footer options have been saved.'); ?></strong></p></div>
				<?php
				}
				?>
				
				<form id="mp_footer" method="post" action="options.php">
				<?php
				settings_fields('mp_settings_footer');
				
				#INITIALISE TWITTER DESCRIPTION
				$twitter_description = '<p>Enter your Twitter RSS feed on your <a href="' . get_bloginfo('siteurl') . '/wp-admin/profile.php" target="_blank">Profle</a> to display the Twitter tweets. Please note that you may only display either your Dribbble thumbnails or Twitter tweets in the footer.</p>';
				
				#DISPLAY TWITTER
				mp_options::mp_option_field('Twitter', $twitter_description, true, true, 'Twitter', 'yes_no', 'mp_footer_twitter', 'mp_footer_twitter', 'Select whether you wish to enable the Twitter tweets', 'Yes', true);
				
				#INITIALISE DRIBBBLE DESCRIPTION
				$dribbble_description = '<p>Enter your Dribbble RSS feed on your <a href="' . get_bloginfo('siteurl') . '/wp-admin/profile.php" target="_blank">Profle</a> to display the Dribbble thumbnails. Please note that you may only display either your Dribbble thumbnails or Twitter tweets in the footer.</p>';
				
				#DISPLAY DRIBBBLE
				mp_options::mp_option_field('Dribbble', $dribbble_description, true, true, 'Dribbble', 'yes_no', 'mp_footer_dribbble', 'mp_footer_dribbble', 'Select whether you wish to enable the Dribbble thumbnails', 'No', true);
				?>
			
				</form>
			
				<?php
				break;
				
			#RSS
			case 'rss':
				
				#DISPLAY UPDATE MESSAGE
				if(isset($_GET['settings-updated']) && ($_GET['settings-updated'] == true))
				{
				?>
				<div class="updated fade"><p><strong><?php _e('Your RSS options have been saved.'); ?></strong></p></div>
				<?php
				}
				?>
				
				<div id="mp_rss_errors" class="mp_errors error"></div>
				
				<form id="mp_rss" method="post" action="options.php">
				<?php
				settings_fields('mp_settings_rss');
				
				#INITIALISE FEEDBURNER DESCRIPTION
				$feedburner_description = '<p>Register your RSS feed at <a href="http://feedburner.google.com/" target="_blank">FeedBurner</a> to get a FeedBurner feed address to replace your default RSS feed address: <a href="' . get_bloginfo('rss2_url') . '" target="_blank">' . get_bloginfo('rss2_url') . '</a>.</p>';
				
				#DISPLAY FEEDBURNER FEED ADDRESS
				mp_options::mp_option_field('FeedBurner', $feedburner_description, true, false, 'FeedBurner Feed Address', 'text', 'mp_feedburner_rss', 'mp_feedburner_rss', 'Enter the FeedBurner Feed Address of your RSS feed', '', false);
				
				#DISPLAY FEEDBURNER EMAIL SUBSCRIPTION ADDRESS
				mp_options::mp_option_field('', '', false, true, 'FeedBurner Subscription Address', 'text', 'mp_feedburner_email', 'mp_feedburner_email', 'Enter the subscription address of your FeedBurner Feed', '', true);
				
				#DISPLAY ARTICLES RSS
				mp_options::mp_option_field('Custom Posts', '', true, false, 'Articles', 'yes_no', 'mp_rss_articles', 'mp_rss_articles', 'Select whether you wish to enable the Articles RSS feed', 'Yes', false);
				
				#DISPLAY SLIDES RSS
				mp_options::mp_option_field('', '', false, false, 'Slides', 'yes_no', 'mp_rss_slides', 'mp_rss_slides', 'Select whether you wish to enable the Slides RSS feed', 'Yes', false);
				
				#DISPLAY PROJECTS RSS
				mp_options::mp_option_field('', '', false, false, 'Projects', 'yes_no', 'mp_rss_projects', 'mp_rss_projects', 'Select whether you wish to enable the Projects RSS feed', 'Yes', false);
				
				#DISPLAY TESTIMONIALS RSS
				mp_options::mp_option_field('', '', false, true, 'Testimonials', 'yes_no', 'mp_rss_testimonials', 'mp_rss_testimonials', 'Select whether you wish to enable the Testimonials RSS feed', 'Yes', true);
				
				#DISPLAY COMMENTS RSS
				mp_options::mp_option_field('Comments', '', true, true, 'Comments', 'yes_no', 'mp_rss_comments', 'mp_rss_comments', 'Select whether you wish to enable the Comments RSS feed', 'Yes', true);
				
				#INITIALISE EXTERNAL FEEDS DESCRIPTION
				$external_description = '<p>Enter any external RSS feeds you wish to display on your RSS page with the [rss] shortcode.</p>';
				
				#DISPLAY EXTERNAL FEEDS
				mp_options::mp_option_field('External RSS Feeds', $external_description, true, true, 'External RSS Feeds', 'textarea', 'mp_rss_external', 'mp_rss_external', 'Enter the name of your RSS feed and feed address on each line, separated by a comma. For example: Jame\'s Feed,http://www.james.com/feed/', '', true);
				?>
			
				</form>
			
				<?php
				break;
				
			#SIDEBAR
			case 'sidebar':
				
				#DISPLAY UPDATE MESSAGE
				if(isset($_GET['settings-updated']) && ($_GET['settings-updated'] == true))
				{
				?>
				<div class="updated fade"><p><strong><?php _e('Your Sidebar options have been saved.'); ?></strong></p></div>
				<?php
				}
				?>
				
				<form method="post" action="options.php">
				<?php
				settings_fields('mp_settings_sidebar');
				
				#INITIALISE ADS DESCRIPTION
				$ads_description = '<p>There are 6 allocated square buttons (125 x 125) in the sidebar. You may use a WordPress plugin that generates HTML code or WordPress shortcode like the <a href="http://www.adrotateplugin.com/" target="_blank">AdRotate Plugin</a> for the square button HTML codes below.</p>';
				
				#DISPLAY ADS STATUS
				mp_options::mp_option_field('Ads', $ads_description, true, false, 'Enable', 'yes_no', 'mp_sidebar_ads', 'mp_sidebar_ads', 'Select whether you wish to enable the sidebar ads', 'No', false);
				
				#DISPLAY SQUARE BUTTON 1
				mp_options::mp_option_field('', '', false, false, 'Square Button 1 Code', 'textarea', 'mp_sidebar_ads_square1', 'mp_sidebar_ads_square1', 'Enter the HTML code for Square button 1', '', false);
				
				#DISPLAY SQUARE BUTTON 2
				mp_options::mp_option_field('', '', false, false, 'Square Button 2 Code', 'textarea', 'mp_sidebar_ads_square2', 'mp_sidebar_ads_square2', 'Enter the HTML code for Square button 2', '', false);
				
				#DISPLAY SQUARE BUTTON 3
				mp_options::mp_option_field('', '', false, false, 'Square Button 3 Code', 'textarea', 'mp_sidebar_ads_square3', 'mp_sidebar_ads_square3', 'Enter the HTML code for Square button 3', '', false);
				
				#DISPLAY SQUARE BUTTON 4
				mp_options::mp_option_field('', '', false, false, 'Square Button 4 Code', 'textarea', 'mp_sidebar_ads_square4', 'mp_sidebar_ads_square4', 'Enter the HTML code for Square button 4', '', false);
				
				#DISPLAY SQUARE BUTTON 5
				mp_options::mp_option_field('', '', false, false, 'Square Button 5 Code', 'textarea', 'mp_sidebar_ads_square5', 'mp_sidebar_ads_square5', 'Enter the HTML code for Square button 5', '', false);
				
				#DISPLAY SQUARE BUTTON 6
				mp_options::mp_option_field('', '', false, true, 'Square Button 6 Code', 'textarea', 'mp_sidebar_ads_square6', 'mp_sidebar_ads_square6', 'Enter the HTML code for Square button 6', '', true);
				
				#INITIALISE FACEBOOK LIKE BOX DESCRIPTION
				$facebook_description = '<p>Generate a <a href="https://developers.facebook.com/docs/reference/plugins/like-box/" target="_blank">Facebook Like Box</a> Iframe social plugin code from Facebook. For best results, please enter 260 for the Width, select the Dark colour scheme, uncheck "Show header" and enter #333333 for the Border Color.</p>';
				
				#DISPLAY FACEBOOK LIKE BOX
				mp_options::mp_option_field('Facebook Like Box', $facebook_description, true, true, 'Facebook Like Box Code', 'textarea', 'mp_facebook_like_box', 'mp_facebook_like_box', 'Enter the Facebook Like Box Iframe social plugin code of your Facebook Page', '', true);
				
				#DISPLAY RECENT POSTS SELECT LIST
				mp_options::mp_option_field('Posts', '', true, false, 'Recent Posts', 'sidebar_lists', 'mp_posts_recent_number', 'mp_posts_recent_number', 'Select the number of posts to display in the Recent Posts list', 5, false, 20);
				
				#DISPLAY POPULAR POSTS SELECT LIST
				mp_options::mp_option_field('', '', false, false, 'Popular Posts', 'sidebar_lists', 'mp_posts_popular_number', 'mp_posts_popular_number', 'Select the number of posts to display in the Popular Posts list', 5, false, 20);
				
				#DISPLAY MOST COMMENTS SELECT LIST
				mp_options::mp_option_field('', '', false, true, 'Most Comments', 'sidebar_lists', 'mp_posts_comments_number', 'mp_posts_comments_number', 'Select the number of posts to display in the Most Comments list', 5, true, 20);
				
				#DISPLAY RECENT COMMENTS SELECT LIST
				mp_options::mp_option_field('Comments', '', true, false, 'Recent Comments', 'sidebar_lists', 'mp_comments_recent_number', 'mp_comments_recent_number', 'Select the number of comments to display in the Recent Comments list', 5, false, 20);
				
				#DISPLAY TOP COMMENTERS SELECT LIST
				mp_options::mp_option_field('', '', false, true, 'Top Commenters', 'sidebar_lists', 'mp_comments_commenters_number', 'mp_comments_commenters_number', 'Select the number of commenters to display in the Top Commenters list', 10, true, 20);
				?>
			
				</form>
			
				<?php
				break;
		
			#TRACKING
			case 'tracking':
				
				#DISPLAY UPDATE MESSAGE
				if(isset($_GET['settings-updated']) && ($_GET['settings-updated'] == true))
				{
				?>
				<div class="updated fade"><p><strong><?php _e('Your Tracking options have been saved.'); ?></strong></p></div>
				<?php
				}
				?>
				
				<form method="post" action="options.php">
				<?php
				settings_fields('mp_settings_tracking');
				
				#INITIALISE TRACKING DESCRIPTION
				$tracking_description = '<p>To use <a href="http://www.google.com/analytics/" target="_blank">Google Analytics</a>, your web site must be registered with your <a href="http://www.google.com/analytics/" target="_blank">Google Analytics</a> account.</p>';
				
				#DISPLAY TRACKING
				mp_options::mp_option_field('Google Analytics Or Other Tracking Services', $tracking_description, true, true, 'Tracking Code', 'textarea', 'mp_tracking', 'mp_tracking', 'Enter the tracking code of your tracking service. The tracking code will appear just before the &lt;/body&gt; tag of your web site', '', true);
				?>
			
				</form>
			
				<?php
				break;
				
			#RESET
				case 'reset':
					
					#AUTHOR RESET SECURITY CHECK PASSED
					if(!empty($_POST['author_reset']) && check_admin_referer('author_reset_check'))
					{
						#RESET AUTHOR OPTIONS
						mp_options::mp_reset_options('author');
						
						#DISPLAY RESET MESSAGE
						?>
						<div class="updated fade"><p><strong><?php _e('Your Author options have been reset.'); ?></strong></p></div>
						<?php
					}
					#HEADER RESET SECURITY CHECK PASSED
					if(!empty($_POST['header_reset']) && check_admin_referer('header_reset_check'))
					{
						#RESET HEADER OPTIONS
						mp_options::mp_reset_options('header');
						
						#DISPLAY RESET MESSAGE
						?>
						<div class="updated fade"><p><strong><?php _e('Your Header options have been reset.'); ?></strong></p></div>
						<?php
					}
					#FOOTER RESET SECURITY CHECK PASSED
					if(!empty($_POST['footer_reset']) && check_admin_referer('footer_reset_check'))
					{
						#RESET FOOTER OPTIONS
						mp_options::mp_reset_options('footer');
						
						#DISPLAY RESET MESSAGE
						?>
						<div class="updated fade"><p><strong><?php _e('Your Footer options have been reset.'); ?></strong></p></div>
						<?php
					}
					#SIDEBAR RESET SECURITY CHECK PASSED
					if(!empty($_POST['sidebar_reset']) && check_admin_referer('sidebar_reset_check'))
					{
						#RESET SIDEBAR OPTIONS
						mp_options::mp_reset_options('sidebar');
						
						#DISPLAY RESET MESSAGE
						?>
						<div class="updated fade"><p><strong><?php _e('Your Sidebar options have been reset.'); ?></strong></p></div>
						<?php
					}
					#RSS RESET SECURITY CHECK PASSED
					if(!empty($_POST['rss_reset']) && check_admin_referer('rss_reset_check'))
					{
						#RESET RSS OPTIONS
						mp_options::mp_reset_options('rss');
						
						#DISPLAY RESET MESSAGE
						?>
						<div class="updated fade"><p><strong><?php _e('Your RSS options have been reset.'); ?></strong></p></div>
						<?php
					}
					#TRACKING RESET SECURITY CHECK PASSED
					if(!empty($_POST['tracking_reset']) && check_admin_referer('tracking_reset_check'))
					{
						#RESET TRACKING OPTIONS
						mp_options::mp_reset_options('tracking');
						
						#DISPLAY RESET MESSAGE
						?>
						<div class="updated fade"><p><strong><?php _e('Your Tracking options have been reset.'); ?></strong></p></div>
						<?php
					}
					?>
					
					<h3 class="title">Header</h3>
					
					<form name="header_reset_form" method="post">
					<?php wp_nonce_field('header_reset_check'); ?>
					
					<input type="submit" name="header_reset" class="button-primary" value="<?php _e('Reset Options') ?>" onclick="javascript:check = confirm('<?php _e('Reset all Header options to default settings?', 'header_reset'); ?>'); if(check == false) { return false; }">
					
					</form>
					
					<h3 class="title">Footer</h3>
					
					<form name="footer_reset_form" method="post">
					<?php wp_nonce_field('footer_reset_check'); ?>
					
					<input type="submit" name="footer_reset" class="button-primary" value="<?php _e('Reset Options') ?>" onclick="javascript:check = confirm('<?php _e('Reset all Footer options to default settings?', 'footer_reset'); ?>'); if(check == false) { return false; }">
					
					</form>
					
					<h3 class="title">Sidebar</h3>
					
					<form name="sidebar_reset_form" method="post">
					<?php wp_nonce_field('sidebar_reset_check'); ?>
					
					<input type="submit" name="sidebar_reset" class="button-primary" value="<?php _e('Reset Options') ?>" onclick="javascript:check = confirm('<?php _e('Reset all Sidebar options to default settings?', 'sidebar_reset'); ?>'); if(check == false) { return false; }">
					
					</form>
					
					<h3 class="title">Author</h3>
					
					<form name="author_reset_form" method="post">
					<?php wp_nonce_field('author_reset_check'); ?>
					
					<input type="submit" name="author_reset" class="button-primary" value="<?php _e('Reset Options') ?>" onclick="javascript:check = confirm('<?php _e('Reset all Author options to default settings?', 'author_reset'); ?>'); if(check == false) { return false; }">
					
					</form>
					
					<h3 class="title">RSS</h3>
					
					<form name="rss_reset_form" method="post">
					<?php wp_nonce_field('rss_reset_check'); ?>
					
					<input type="submit" name="rss_reset" class="button-primary" value="<?php _e('Reset Options') ?>" onclick="javascript:check = confirm('<?php _e('Reset all RSS options to default settings?', 'rss_reset'); ?>'); if(check == false) { return false; }">
					
					</form>
					
					<h3 class="title">Tracking</h3>
					
					<form name="tracking_reset_form" method="post">
					<?php wp_nonce_field('tracking_reset_check'); ?>
					
					<input type="submit" name="tracking_reset" class="button-primary" value="<?php _e('Reset Options') ?>" onclick="javascript:check = confirm('<?php _e('Reset all Tracking options to default settings?', 'tracking_reset'); ?>'); if(check == false) { return false; }">
					
					</form>					
					
					<?php	
					break;
		}
	}
	
	#THIS FUNCTION DISPLAYS THE THEME'S OPTIONS PAGE FIELDS
	public function mp_option_field($h3_title = '', $below_h3_title = '', $open_table = false, $close_table = false, $column_name, $input_type, $input_id, $input_option, $input_description, $input_default, $save_button = false, $min_width = '', $max_width = '')
	{
		#INITIALISE OPTION
		$mp_option = get_option($input_option);
		
		#DISPLAY HEADER
		if(!empty($h3_title))
		{
			echo '<h3 class="title">' . $h3_title . '</h3>' . "\n";
		}
		
		#DISPLAY CODE AFTER HEADER
		if(!empty($below_h3_title))
		{
			echo $below_h3_title . "\n";
		}
		
		#OPEN TABLE
		if($open_table)
		{
			echo '<table class="form-table">' . "\n";
		}
		
		#OPEN TABLE ROW
		echo '<tr valign="top">' . "\n";
		
		#DISPLAY 1ST COLUMN WITH COLUMN NAME
		echo "\t" . '<th scope="row">' . $column_name . '</th>' . "\n";
		
		#OPEN 2ND COLUMN
		echo "\t" . '<td>';
		
		#DISPLAY INPUT TYPE
		switch($input_type)
		{
			#MEDIA UPLOAD
			case 'media_upload':

				mp_options::mp_display_media_upload($input_id, $mp_option);
				break;
			
			#TEXT BOX
			case 'text':
			
				mp_options::mp_display_text($input_id, $mp_option);
				break;
				
			#TEXTAREA:
			case 'textarea':
				
				mp_options::mp_display_textarea($input_id, $mp_option);
				break;
				
			#YES/NO:
			case 'yes_no':
				
				mp_options::mp_display_yes_no_list($input_id, $mp_option);
				break;
				
			#SMALL/LARGE:
			case 'small_large':
				
				mp_options::mp_display_small_large_list($input_id, $mp_option);
				break;
				
			#AUTHOR SELECT LIST:
			case 'author':
				
				mp_options::mp_display_author_list($input_id, $mp_option, $input_default);
				break;
			
			#SIDEBAR LIST ITEMS
			case 'sidebar_lists':
			
				mp_options::mp_display_sidebar_list($input_id, $mp_option, $input_default, $min_width);
				break;
		}
		
		#CLOSE 2ND COLUMN WITH INPUT DESCRIPTION & DEFAULT VALUES OF MINIMUM & MAXIMUM WIDTH
		if($input_type == 'width' && !empty($min_width) && !empty($max_width))
		{
			echo '<br><span class="description">' . $input_description . '. Default: ' . $input_default . ', Minimum: ' . $min_width . 'px, Maximum: ' . $max_width . 'px.</span></td>' . "\n";
		}
		#CLOSE 2ND COLUMN WITH INPUT DESCRIPTION & DEFAULT VALUE
		else
		{
			#DEFAULT VALUE EXISTS
			if(!empty($input_default))
			{
				echo '<br><span class="description">' . $input_description . '. Default: ' . $input_default . '.</span></td>' . "\n";
			}
			#DEFAULT VALUE DOES NOT EXIST
			else
			{
				echo '<br><span class="description">' . $input_description . '.</span></td>' . "\n";
			}
		}
		
		#CLOSE TABLE ROW
		echo '</tr>' . "\n";
		
		#CLOSE TABLE
		if($close_table)
		{
			echo '</table>' . "\n";
		}
		
		#DISPLAY SAVE CHANGES BUTTON
		if($save_button)
		{
			echo '<p class="submit"><input type="submit" class="button-primary" value="Save Changes"></p>' . "\n";
		}
	}
	
	#THIS FUNCTION DISPLAYS A MEDIA UPLOAD FIELD
	protected function mp_display_media_upload($media_upload_id, $media_upload_url)
	{
		#INITIALISE MEDIA UPLOAD FIELD HTML
		$media_upload_box = '<input name="' . $media_upload_id . '" id="' . $media_upload_id . '" type="text" value="' . $media_upload_url . '" class="regular-text"> <input id="' . $media_upload_id . '_button" class="button" type="button" value="Upload">';
			
		#DISPLAY MEDIA UPLOAD FIELD
		echo $media_upload_box;
	}
	
	#THIS FUNCTION DISPLAYS A TEXT FIELD
	protected function mp_display_text($text_id, $entered_text)
	{
		#INITIALISE TEXT FIELD HTML
		$text_box = '<input name="' . $text_id . '" id="' . $text_id . '" type="text" value="' . $entered_text . '" class="regular-text">';
		
		#DISPLAY TEXT FIELD
		echo $text_box;
	}
	
	#THIS FUNCTION DISPLAYS A TEXTAREA
	protected function mp_display_textarea($text_id, $entered_text)
	{
		#INITIALISE TEXTAREA
		$textarea = '<textarea name="' . $text_id . '" id="' . $text_id . '" rows="10" cols="50" class="large-text code">'. $entered_text . '</textarea>';
		
		#DISPLAY TEXTAREA
		echo $textarea;
	}
	
	#THIS FUNCTION DISPLAYS THE LIST OF YES & NO OPTIONS
	protected function mp_display_yes_no_list($select_id, $selected_option)
	{		
		#INITIALISE SELECT LIST HTML
		$select_list = '<select name="' . $select_id . '" id="' . $select_id . '" class="postform">' . "\n";
		
		#INITIALISE OPTIONS
		$options = array
		(
			'Yes' => 1,
			'No' => 0
		);
		
		#DISPLAY OPTIONS
		foreach($options as $option_key => $option_value)
		{
			#SELECTED OPTION
			if($selected_option == $option_value)
			{
				$select_list .= '<option class="level-0" selected="selected" value="' . $option_value . '">' . $option_key . '</option>' . "\n";
			}
			#UNSELECTED OPTION
			else
			{
				$select_list .= '<option class="level-0" value="' . $option_value . '">' . $option_key . '</option>' . "\n";
			}
		}
		
		#CLOSE SELECT LIST HTML
		$select_list .= '</select>';
		
		#DISPLAY SELECT LIST
		echo $select_list;
	}
	
	#THIS FUNCTION DISPLAYS THE LIST OF SMALL & LARGE OPTIONS
	protected function mp_display_small_large_list($select_id, $selected_option)
	{		
		#INITIALISE SELECT LIST HTML
		$select_list = '<select name="' . $select_id . '" id="' . $select_id . '" class="postform">' . "\n";
		
		#INITIALISE OPTIONS
		$options = array
		(
			'Small' => 'small',
			'Large' => 'large'
		);
		
		#DISPLAY OPTIONS
		foreach($options as $option_key => $option_value)
		{
			#SELECTED OPTION
			if($selected_option == $option_value)
			{
				$select_list .= '<option class="level-0" selected="selected" value="' . $option_value . '">' . $option_key . '</option>' . "\n";
			}
			#UNSELECTED OPTION
			else
			{
				$select_list .= '<option class="level-0" value="' . $option_value . '">' . $option_key . '</option>' . "\n";
			}
		}
		
		#CLOSE SELECT LIST HTML
		$select_list .= '</select>';
		
		#DISPLAY SELECT LIST
		echo $select_list;
	}
	
	#THIS FUNCTION DISPLAYS THE NUMBER OF LIST ITEMS OF SIDEBAR LISTS
	public function mp_display_sidebar_list($select_id, $selected_number_of_items, $default_number_of_items = 5, $number_of_items)
	{
		#SELECT DEFAULT NUMBER OF ITEMS IF NO NUMBER OF ITEMS WAS SELECTED
		if(empty($selected_number_of_items) && !empty($default_number_of_items))
		{
			$selected_number_of_items = $default_number_of_items;
		}
	
		#INITIALISE SELECT LIST HTML
		$select_list = '<select name="' . $select_id . '" id="' . $select_id . '" class="postform">' . "\n";
		
		#APPEND LIST ITEMS
		for($item_counter = 1; $item_counter <= $number_of_items; $item_counter ++)
		{
			#SELECTED LIST ITEM
			if($selected_number_of_items == $item_counter)
			{
				$select_list .= '<option class="level-0" selected="selected" value="' . $item_counter . '">' . $item_counter . '</option>' . "\n";
			}
			#UNSELECTED LIST ITEM
			else
			{
				$select_list .= '<option class="level-0" value="' . $item_counter . '">' . $item_counter . '</option>' . "\n";
			}
		}
		
		#CLOSE SELECT LIST HTML
		$select_list .= '</select>';
		
		#DISPLAY SELECT LIST
		echo $select_list;
	}
	
	#THIS FUNCTION DISPLAYS THE TRACKING CODE JUST BEFORE THE </BODY> TAG
	public function mp_tracking()
	{
		#INITIALISE TRACKING SETTINGS
		$mp_tracking = get_option('mp_tracking');
		
		#TRACKING SETTINGS EXIST
		if(!empty($mp_tracking))
		{
			echo $mp_tracking . "\n\n";
		}
	}
	
	#THIS FUNCTION INCLUDES THE JQUERY LIBRARY INTO NON-ADMIN WORDPRESS PAGES
	public function mp_jquery()
	{
		#PAGE IS NON-ADMIN
		if(!is_admin())
		{
			#DEREGISTER DEFAULT JQUERY INCLUDE
			wp_deregister_script('jquery');	
	
			#LOAD THE GOOGLE API JQUERY INCLUDE
			wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js', false, '2.0.3', false);
	
			#REGISTER GOOGLE API JQUERY INCLUDE
			wp_enqueue_script('jquery');
		}
	}
	
	#THIS FUNCTION INCLUDES THE JAVASCRIPT & CSS FILES INTO ADMIN WORDPRESS PAGES
	public function mp_admin_head()
	{
		echo '<link rel="stylesheet" media="all" href="' . get_bloginfo('template_url') . '/css/admin.php" type="text/css">' . "\n";
		echo '<script src="' . get_bloginfo('template_url') . '/js/vendor/min/jquery-validate.min.js"></script>' . "\n";
		echo '<script src="' . get_bloginfo('template_url') . '/js/vendor/min/jquery-validate-additional-methods.min.js"></script>' . "\n";
		echo '<script src="' . get_bloginfo('template_url') . '/js/modules/min/mp-module-admin.min.js"></script>' . "\n";
		
		#LOAD JAVASCRIPT FOR TINYMCE EDITOR FOR USER BIOGRAPHY IN WORDPRESS 3.3 +
		if(function_exists('wp_editor'))
		{
			echo '<script>jQuery(document).ready(function() { mp_module_admin.run_biography(); });</script>' . "\n";
		}
		
		#LOAD JAVASCRIPT FOR MEDIA UPLOADER FOR WORDPRESS 3.5 +
		if(function_exists('wp_enqueue_media'))
		{
			echo '<script>jQuery(document).ready(function() { mp_module_admin.run_uploader_new(); });</script>' . "\n";
			wp_enqueue_media();
		}
		#LOAD JAVASCRIPT FOR MEDIA UPLOADER FOR WORDPRESS 3.4 & BELOW
		else
		{
			echo '<script>jQuery(document).ready(function() { mp_module_admin.run_uploader_old(); });</script>' . "\n";
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
		}	
	}
	
	#THIS FUNCTION SAVES THE META BOX FORM CONTENTS
	protected function mp_meta_boxes_save($post_id, $nonce, $field_name, $type, $url_encode = false)
	{		
		#FORMATTING FORM DID NOT SUBMIT FROM THE RIGHT PLACE
		if(!wp_verify_nonce($_POST[$nonce], __FILE__))
		{
			return $post_id;
		}
		
		#DETERMINE USER'S PERMISSIONS TO EDIT PAGE/POST
		switch($type)
		{
			#PAGES
			case 'page':
			
				#USER HAS NO PERMISSION TO EDIT PAGE
				if($_POST['post_type'] == 'page' && !current_user_can('edit_pages', $post_id)) 
				{
					return $post_id;
				}
				
			#POSTS
			case 'post':
			
				#USER HAS NO PERMISSION TO EDIT POST
				if($_POST['post_type'] == 'post' && !current_user_can('edit_posts', $post_id)) 
				{
					return $post_id;
				}
		}
		
		#INITIALISE CURRENT FIELD
		$current_field = get_post_meta($post_id, $field_name, true);
		
		#INITIALISE NEW FIELD
		$new_field = $_POST[$field_name];
		
		#URL ENCODE NEW FIELD
		if($url_encode)
		{
			$new_field = urlencode($new_field);
		}
		
		#CURRENT FIELD EXISTS
		if($current_field) 
		{
			#DELETE EXISTING FORMATTING
			if(empty($new_field))
			{
				delete_post_meta($post_id, $field_name);
			}
			#UPDATE EXISTING FORMATTING
			else
			{
				update_post_meta($post_id, $field_name, $new_field);
			}
		}
		#NEW FIELD EXISTS
		elseif(!empty($new_field))
		{
			add_post_meta($post_id, $field_name, $new_field, true);
		}
	}
	
	/**************************************************************************
	3. LOGO FUNCTIONS
	**************************************************************************/
	
	#THIS FUNCTION DISPLAYS THE LOGO IN EITHER IMAGE OR TEXT FORM
	public function mp_display_logo()
	{		
		#INITIALISE LOGO
		$mp_logo = get_option('mp_logo');
		$mp_logo_text = false;
		
		#LOGO ENABLED
		if(!empty($mp_logo))
		{
			#INITIALISE LOGO IMAGE
			$mp_logo_image = get_option('mp_logo_image');
		
			#DISPLAY LOGO
			if(!empty($mp_logo_image))
			{
				echo '<a href="' . get_bloginfo('url') . '"><img src="' . $mp_logo_image . '" alt="' . get_bloginfo('name') . ' - ' . get_bloginfo('description') . '" title="' . get_bloginfo('name') . ' - ' . get_bloginfo('description') . '" class="logo-image"></a>';
				
			}
			#DISPLAY TEXT LOGO
			else
			{
				$mp_logo_text = true;
			}
		}
		#DISPLAY TEXT LOGO
		elseif(empty($mp_logo) || !empty($mp_logo_text))
		{
			echo '<p class="title"><a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a></p><p class="description">' . get_bloginfo('description') . '</p>';
		}
	}
	
	#THIS FUNCTION DISPLAYS THE MOBILE LOGO IN EITHER IMAGE OR TEXT FORM
	public function mp_display_logo_mobile()
	{		
		#INITIALISE LOGO
		$mp_logo = get_option('mp_logo');
		$mp_logo_text = false;
		
		#LOGO ENABLED
		if(!empty($mp_logo))
		{
			#INITIALISE MOBILE LOGO IMAGE
			$mp_logo_image_mobile = get_option('mp_logo_image_mobile');
		
			#DISPLAY MOBILE LOGO
			if(!empty($mp_logo_image_mobile))
			{
				echo '<a href="' . get_bloginfo('url') . '"><img src="' . $mp_logo_image_mobile . '" alt="' . get_bloginfo('name') . ' - ' . get_bloginfo('description') . '" title="' . get_bloginfo('name') . ' - ' . get_bloginfo('description') . '" class="logo-mobile-image"></a>';
				
			}
			#DISPLAY TEXT LOGO
			else
			{
				$mp_logo_text = true;
			}
		}
		#DISPLAY TEXT LOGO
		elseif(empty($mp_logo) || !empty($mp_logo_text))
		{
			echo '<p class="title-mobile"><a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a></p>';
		}
	}
	
	/**************************************************************************
	4. ARTICLE FUNCTIONS
	**************************************************************************/
	
	#THIS FUNCTION CREATES THE ARTICLE CUSTOM POST TYPE
	public function mp_custom_posts_articles()
	{
		#INITIALISE ARTICLE CUSTOM POST TYPE LABELS
		$labels = array
		(
			'name' => _x('Articles', 'post type general name'),
			'singular_name' => _x('Article', 'post type singular name'),
			'add_new' => _x('Add New', 'article'),
			'add_new_item' => __('Add New Article'),
			'edit_item' => __('Edit Article'),
			'new_item' => __('New Article'),
			'all_items' => __('All Articles'),
			'view_item' => __('View Article'),
			'search_items' => __('Search Articles'),
			'not_found' =>  __('No Articles found'),
			'not_found_in_trash' => __('No Articles found in Trash'), 
			'parent_item_colon' => '',
			'menu_name' => 'Articles'
		);
		
		#INITIALISE ARTICLE CUSTOM POST TYPE ARGUMENTS
		$args = array
		(
			'labels' => $labels,
			'description' => 'Article',
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true, 
			'show_in_menu' => true,
			'menu_position' => 20,
			'menu_icon' => null,
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array('title', 'editor', 'revisions', 'thumbnail'),
			'has_archive' => false,
			'rewrite' => array('slug' => 'article', 'with_front' => false),
			'query_var' => true,
			'can_export' => true,
			'show_in_nav_menus' => true
		);
		
		#REGISTER ARTICLE CUSTOM POST TYPE
		register_post_type('article', $args);
	}
	
	#THIS FUNCTION CREATES THE ARTICLE DIRECTORIES CUSTOM TAXONOMY
	public function mp_custom_taxonomies_article_directories()
	{
		#INITIALISE ARTICLE DIRECTORIES CUSTOM TAXONOMY LABELS
		$labels = array
		(
			'name' => _x('Article Directories', 'taxonomy general name'),
			'singular_name' => _x('Article Directory', 'taxonomy singular name'),
			'search_items' =>  __('Search Article Directories'),
			'all_items' => __('All Article Directories'),
			'parent_item' => __('Parent Article Directory'),
			'parent_item_colon' => __('Parent Article Directory:'),
			'edit_item' => __('Edit Article Directory'), 
			'update_item' => __('Update Article Directory'),
			'add_new_item' => __('Add New Article Directory'),
			'new_item_name' => __('New Article Directory'),
			'menu_name' => __('Article Directories'),
			'choose_from_most_used' => __('Choose from the most used Article Directories')
		);
		
		#INITIALISE ARTICLE DIRECTORIES CUSTOM TAXONOMY ARGUMENTS
		$args = array
		(
			'labels' => $labels,	
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => false,
			'hierarchical' => true,
			'rewrite' => array('slug' => 'article-directories', 'with_front' => false),
			'query_var' => true
		);
		
		#REGISTER ARTICLE DIRECTORIES CUSTOM TAXONOMY
		register_taxonomy('article-directories', 'article', $args);
	}
	
	#THIS FUNCTION DISPLAYS THE ARTICLE COLUMNS
	public function mp_article_edit_columns($columns)
	{
		#INITIALISE ARTICLE COLUMNS
		$columns = 
		array
		(
			'cb' => '<input type="checkbox">',
			'title' => 'Title',
			'article_url' => 'URL',
			'article_directory' => 'Directory',
			'date' => 'Date'
		);
		
		#RETURN ARTICLE COLUMNS
		return $columns;
	}
	
	#THIS FUNCTION DISPLAYS THE SORTABLE ARTICLE COLUMNS
	public function mp_article_sortable_columns()
	{
		#INITIALISE SORTABLE ARTICLE COLUMNS
		$columns = 
		array
		(
			'title' => 'Title',
			'article_directory' => 'Directory',
			'date' => 'Date'
		);
		
		#RETURN SORTABLE ARTICLE COLUMNS
		return $columns;
	}
	
	#THIS FUNCTION DISPLAYS THE ARTICLE COLUMN VALUES
	public function mp_article_custom_columns($column)
	{
		#RETRIEVE THE ARTICLE
		global $post;
		
		#DISPLAY ARTICLE VALUES
		switch($column)
		{
			#ARTICLE DIRECTORY
			case 'article_directory':
			
				echo get_the_term_list($post->ID, 'article-directories', '', ', ');
				break;
			
			#ARTICLE URL
			case 'article_url':
			
				#INITIALISE ARTICLE URL
				$article_url = get_post_meta($post->ID, 'article_url', true);
				
				#DISPLAY ARTICLE URL ICON
				if(!empty($article_url))
				{
					echo '<a href="' . $article_url . '" target="_blank"><img src="' . get_bloginfo('template_url') . '/images/icon-url.png" alt=""></a>';
				}
				
				break;
		}
	}
	
	#THIS FUNCTION CREATES THE ARTICLE BOX
	public function mp_meta_boxes_article()
	{
		#ADD ARTICLE BOX TO ARTICLE CUSTOM POSTS
		add_meta_box('article_box', 'Article Information', array('mp_options', 'mp_meta_boxes_article_form'), 'article', 'normal', 'high');
	 
		#SAVE ARTICLE BOX FORM CONTENTS
		add_action('save_post', array('mp_options', 'mp_meta_boxes_article_form_save'));
	}
	
	#THIS FUNCTION CREATES THE ARTICLE BOX FORM
	public function mp_meta_boxes_article_form()
	{
		#RETRIEVE THE POST
		global $post;
	
		#INITIALISE ARTICLE OPTIONS
		$article_url = get_post_meta($post->ID, 'article_url', true);
		
		#DISPLAY ARTICLE NONCE FIELD
		echo '<input name="article_nonce" id="article_nonce" type="hidden" value="' . wp_create_nonce(__FILE__) . '">';
		
		#DISPLAY ARTICLE URL FIELD
		echo '<p><strong>Article URL:</strong><br><input name="article_url" id="article_url" type="text" size="80" value="' . urldecode($article_url) . '"></p><p>Enter the article URL.</p>';
		
		#LAUNCH VALIDATOR
		echo '<script>jQuery(document).ready(function() { mp_module_admin.run_validator_articles(); });</script>';
	}
	
	#THIS FUNCTION SAVES THE ARTICLE BOX FORM CONTENTS
	public function mp_meta_boxes_article_form_save($post_id) 
	{
		#SAVE ARTICLE BOX FORM CONTENTS
		mp_options::mp_meta_boxes_save($post_id, 'article_nonce', 'article_url', 'post');
		
		#RETURN POST ID
		return $post_id;
	}
	
	#THIS FUNCTION DISPLAYS THE ARTICLES
	public function mp_display_articles($category, $page, $pagination = true, $max_words = 20)
	{		
		#RETRIEVE THE POST
		global $post;
		
		#INITIALISE ARTICLE ARGUMENTS OF ARTICLES PAGE
		if(empty($category))
		{
			#INITIALISE ARTICLE ARGUMENTS
			$args = array
			(
				'post_type' => 'article',
				'post_status' => 'publish',
				'posts_per_page' => get_option('posts_per_page'),
				'paged' => $page,
				'order' => 'DESC',
				'orderby' => 'date'
			);
		}
		#INITIALISE ARTICLE ARGUMENTS OF ARTICLE DIRECTORIES
		else
		{
			#INITIALISE ARTICLE ARGUMENTS
			$args = array
			(
				'post_type' => 'article',
				'post_status' => 'publish',
				'posts_per_page' => get_option('posts_per_page'),
				'paged' => $page,
				'order' => 'DESC',
				'orderby' => 'date',
				'tax_query' =>
				array
				(
					array
					(
						'taxonomy' => 'article-directories',
						'field' => 'slug',
						'terms' => $category
					)
				)
			);
		}
		
		#INITIALISE ARTICLE
		$articles = new WP_Query($args);
		
		#ARTICLES EXISTS
		if($articles->have_posts())
		{			
			#DISPLAY ARTICLES
			while($articles->have_posts())
			{
				#RETRIEVE THE ARTICLE CONTENT
				$articles->the_post();
				
				#INITIALISE ARTICLE URL
				$article_url = get_post_meta($post->ID, 'article_url', true);
				
				echo '<h3 class="post-title"><a href="' . $article_url . '" title="' . get_the_title(). '" rel="nofollow">' . get_the_title(). '</a></h3>';
				echo '<p class="post-info">Published on ' . get_the_time(get_option('date_format')) . ' in ' . get_the_term_list($post->ID, "article-directories", "", ", ") . ' by ' . get_the_author() . '</p>';
				echo '<div class="post-line">';

				#DISPLAY ARTICLE THUMBNAIL
				if(has_post_thumbnail())
				{
					echo '<a href="' . $article_url . '" title="' . get_the_title() . '" rel="nofollow">' . get_the_post_thumbnail($post->ID, "thumbnail") . '</a>';
				}
				
				#DISPLAY ARTICLE EXCERPT
				the_content();
				
				echo '</div>';				
			}
			
			#PAGING NAVIGATION IS ENABLED
			if($pagination)
			{
				#DISPLAY WP-PAGENAVI PAGING NAVIGATION LINKS
				if(function_exists('wp_pagenavi'))
				{
					wp_pagenavi(array('query' => $articles));
				}
				#DISPLAY DEFAULT WORDPRESS PAGING NAVIGATION LINKS
				else
				{
				?>
					<p class="left"><?php next_posts_link('&laquo; Previous Articles'); ?></p>
					<p class="right"><?php previous_posts_link('Next Articles &raquo;'); ?></p>
				<?php
				}
			}
		}
	}
	
	#THIS FUNCTION DISPLAYS THE RECENT ARTICLES ON THE HOME PAGE
	public function mp_display_recent_articles_home()
	{
		#RETRIEVE THE DATABASE
		global $wpdb;
		
		#RETREIVE ARTICLES
		$articles = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'article' ORDER BY post_date DESC LIMIT 5");
		
		#ARTICLES EXIST
		if(!empty($articles))
		{
			#OPEN UNORDERED LIST
			echo '<ul>';
			
			#DISPLAY ARTICLES
			foreach($articles as $article)
			{
				#INITIALISE ARTICLE URL
				$article_url = get_post_meta($article->ID, 'article_url', true);
				
				#INITIALISE ARTICLE DIRECTORY
				$article_directories = get_the_terms($article->ID, 'article-directories');
				
				#DISPLAY ARTICLE TITLE & LINK
				echo '<li><a href="' . $article_url . '" title="' . $article->post_title . '" rel="nofollow">' . $article->post_title . '</a><br><span class="info">' . get_the_time(get_option('date_format'), $article->ID);
				
				#ARTICLE DIRECTORY EXISTS
				if($article_directories && ! is_wp_error($article_directories))
				{
					echo ' | ' . $article_directories[0]->name . '</span></li>';
				}
				#ARTICLE DIRECTORY DOES NOT EXIST
				else
				{
					echo '</span></li>';
				}
			}
			
			#CLOSE UNORDERED LIST
			echo '</ul>' . "\n";
		}
	}
	
	#THIS FUNCTION DISPLAYS THE ARTICLE DIRECTORIES IN THE SIDEBAR
	public function mp_display_article_directories($current_directory)
	{
		#CUSTOM TAXONOMY ORDER NE PLUGIN ACTIVATED
		if(function_exists('customtaxorder'))
		{
			#INITIALISE DIRECTORIES WITH CUSTOM SORT ORDER
			$directories = get_terms('article-directories', 'orderby=term_order&order=ASC&hide_empty=1');
		}
		#CUSTOM TAXONOMY SORT PLUGIN DEACTIVATED
		else
		{
			#INITIALISE DIRECTORIES WITH NAME SORT ORDER
			$directories = get_terms('article-directories', 'orderby=name&hide_empty=1');
		}
		
		#DIRECTORIES EXIST
		if(count($directories) > 0)
		{			
			#DISPLAY DIRECTORY LINKS
			foreach($directories as $directory)
			{				
				#DISPLAY SELECTED SUB DIRECTORY LINKS
				if($directory->slug == $current_directory)
				{
					echo '<li class="current"><a href="' . get_term_link($directory->slug, "article-directories") . '">' . $directory->name . ' (' . $directory->count . ')</a></li>';
				}
				#DISPLAY UNSELECTED SUB DIRECTORY LINKS
				else
				{
					echo '<li><a href="' . get_term_link($directory->slug, "article-directories") . '">' . $directory->name . ' (' . $directory->count . ')</a></li>';
				}
			}
		}
	}
	
	/**************************************************************************
	5. SLIDE FUNCTIONS
	**************************************************************************/
	
	#THIS FUNCTION CREATES THE SLIDE CUSTOM POST TYPE
	public function mp_custom_posts_slides()
	{
		#INITIALISE SLIDE CUSTOM POST TYPE LABELS
		$labels = array
		(
			'name' => _x('Slides', 'post type general name'),
			'singular_name' => _x('Slide', 'post type singular name'),
			'add_new' => _x('Add New', 'slide'),
			'add_new_item' => __('Add New Slide'),
			'edit_item' => __('Edit Slide'),
			'new_item' => __('New Slide'),
			'all_items' => __('All Slides'),
			'view_item' => __('View Slide'),
			'search_items' => __('Search Slides'),
			'not_found' =>  __('No Slides found'),
			'not_found_in_trash' => __('No Slides found in Trash'), 
			'parent_item_colon' => '',
			'menu_name' => 'Slides'
		);
		
		#INITIALISE SLIDE CUSTOM POST TYPE ARGUMENTS
		$args = array
		(
			'labels' => $labels,
			'description' => 'Slide',
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true, 
			'show_in_menu' => true,
			'menu_position' => 20,
			'menu_icon' => null,
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array('title', 'editor', 'revisions'),
			'has_archive' => false,
			'rewrite' => array('slug' => 'slide', 'with_front' => false),
			'query_var' => true,
			'can_export' => true,
			'show_in_nav_menus' => true
		);
		
		#REGISTER SLIDE CUSTOM POST TYPE
		register_post_type('slide', $args);
	}
	
	#THIS FUNCTION DISPLAYS THE SLIDE COLUMNS
	public function mp_slide_edit_columns($columns)
	{
		#INITIALISE SLIDE COLUMNS
		$columns = 
		array
		(
			'cb' => '<input type="checkbox">',
			'title' => 'Title',
			'image' => 'Image',
			'url' => 'URL',
			'date' => 'Date'
		);
		
		#RETURN SLIDE COLUMNS
		return $columns;
	}
	
	#THIS FUNCTION DISPLAYS THE SLIDE COLUMN VALUES
	public function mp_slide_custom_columns($column)
	{
		#RETRIEVE THE POST
		global $post;
		
		#DISPLAY SLIDE COLUMN VALUES
		switch($column)
		{			
			#SLIDE IMAGE
			case 'image':
			
				#INITIALISE SLIDE IMAGE
				$slide_image = get_post_meta($post->ID, 'slide_image', true);
				
				#DISPLAY SLIDE IMAGE ICON
				if(!empty($slide_image))
				{
					echo '<a href="' . $slide_image . '" title=""><img src="' . get_bloginfo('template_url') . '/images/icon-picture.png" alt=""></a>';
				}
				
				break;
				
			#SLIDE URL
			case 'url':
				
				#INITIALISE SLIDE URL
				$slide_url = get_post_meta($post->ID, 'slide_url', true);
				
				#DISPLAY SLIDE URL ICON
				if(!empty($slide_url))
				{
					echo '<a href="' . $slide_url . '" target="_blank"><img src="' . get_bloginfo('template_url') . '/images/icon-url.png" alt=""></a>';
				}
				
				break;
		}
	}
	
	#THIS FUNCTION CREATES THE SLIDE BOX
	public function mp_meta_boxes_slide()
	{
		#ADD SLIDE BOX TO SLIDE CUSTOM POSTS
		add_meta_box('slide_box', 'Slide Information', array('mp_options', 'mp_meta_boxes_slide_form'), 'slide', 'normal', 'high');
	 
		#SAVE SLIDE BOX FORM CONTENTS
		add_action('save_post', array('mp_options', 'mp_meta_boxes_slide_form_save'));
	}
	
	#THIS FUNCTION CREATES THE SLIDE BOX FORM
	public function mp_meta_boxes_slide_form()
	{
		#RETRIEVE THE POST
		global $post;
	
		#INITIALISE SLIDE OPTIONS
		$slide_image = get_post_meta($post->ID, 'slide_image', true);
		$slide_video_url = get_post_meta($post->ID, 'slide_video_url', true);
		$slide_url = get_post_meta($post->ID, 'slide_url', true);
		$slide_animation_in = get_post_meta($post->ID, 'slide_animation_in', true);
		$slide_animation_out = get_post_meta($post->ID, 'slide_animation_out', true);
		$slide_type = get_post_meta($post->ID, 'slide_type', true);
		
		#DISPLAY SLIDE NONCE FIELD
		echo '<input name="slide_nonce" id="slide_nonce" type="hidden" value="' . wp_create_nonce(__FILE__) . '">';
		
		#DISPLAY SLIDE TYPE FIELD
		echo '<p><strong>Slide Type:</strong><br>'; mp_options::mp_display_slide_type_radio_button($slide_type); echo '</p><p>Select the type of slide you wish to create.</p>';
				
		#DISPLAY SLIDE IMAGE FIELD
		echo '<p><strong>Slide Image:</strong><br>'; mp_options::mp_display_media_upload('slide_image', urldecode($slide_image)); echo '</p><p>Enter the slide image URL.</p>';
		
		#DISPLAY SLIDE VIDEO URL FIELD
		echo '<p><strong>Slide Video URL:</strong><br><input name="slide_video_url" id="slide_video_url" type="text" size="80" value="' . urldecode($slide_video_url) . '"></p><p>Enter the slide video URL from Youtube. E.g. http://www.youtube.com/watch?v=fuXWcrh1Fsw</p>';
		
		#DISPLAY SLIDE URL FIELD
		echo '<p><strong>Slide URL:</strong><br><input name="slide_url" id="slide_url" type="text" size="80" value="' . urldecode($slide_url) . '"></p><p>Enter the slide URL.</p>';
		
		#DISPLAY SLIDE IN ANIMATION FIELD
		echo '<p><strong>Slide In Animation:</strong><br>'; mp_options::mp_display_slide_animation_in_list($slide_animation_in); echo '</p><p>Select the animation of the slide when it enters the slider. Please refer to the <a href="http://daneden.me/animate/" target="_blank">Animate.css</a> page to preview the animations.</p>';
		
		#DISPLAY SLIDE OUT ANIMATION FIELD
		echo '<p><strong>Slide Out Animation:</strong><br>'; mp_options::mp_display_slide_animation_out_list($slide_animation_out); echo '</p><p>Select the animation of the slide when it exits the slider. Please refer to the <a href="http://daneden.me/animate/" target="_blank">Animate.css</a> page to preview the animations.</p>';
		
		#LAUNCH VALIDATOR
		echo '<script>jQuery(document).ready(function() { mp_module_admin.run_validator_slides(); });</script>';
	}
	
	#THIS FUNCTION DISPLAYS THE LIST OF SLIDE IN ANIMATIONS
	private function mp_display_slide_animation_in_list($selected_animation)
	{		
		#INITIALISE DEFAULT ANIMATION
		$default_animation = 'bounceInLeft';
		
		#SELECT DEFAULT ANIMATION IF NO ANIMATION WAS SELECTED
		if(empty($selected_animation))
		{
			$selected_animation = $default_animation;
		}
		
		#INITIALISE SELECT LIST HTML
		$select_list = '<select name="slide_animation_in" id="slide_animation_in" class="postform">' . "\n";
		
		#INITALISE ANIMATIONS
		$animations = array('No Animation', 'flash', 'bounce', 'shake', 'tada', 'swing', 'wobble', 'pulse', 'flip', 'flipInX', 'flipInY', 'fadeIn', 'fadeInUp', 'fadeInDown', 'fadeInLeft', 'fadeInRight', 'fadeInUpBig', 'fadeInDownBig', 'fadeInLeftBig', 'fadeInRightBig', 'bounceIn', 'bounceInDown', 'bounceInUp', 'bounceInLeft', 'bounceInRight', 'rotateIn', 'rotateInDownLeft', 'rotateInDownRight', 'rotateInUpLeft', 'rotateInUpRight', 'hinge', 'rollIn');
		
		#APPEND ANIMATIONS
		foreach($animations as $animation)
		{
			#SELECTED ANIMATION
			if($selected_animation == $animation)
			{
				$select_list .= '<option class="level-0" selected="selected" value="' . $animation . '">' . $animation . '</option>' . "\n";
			}
			#UNSELECTED ANIMATION
			else
			{
				$select_list .= '<option class="level-0" value="' . $animation . '">' . $animation . '</option>' . "\n";
			}
		}
		
		#CLOSE SELECT LIST HTML
		$select_list .= '</select>';
		
		#DISPLAY SELECT LIST
		echo $select_list;
	}
	
	#THIS FUNCTION DISPLAYS THE LIST OF SLIDE OUT ANIMATIONS
	private function mp_display_slide_animation_out_list($selected_animation)
	{
		#INITIALISE DEFAULT ANIMATION
		$default_animation = 'bounceOutRight';
		
		#SELECT DEFAULT ANIMATION IF NO ANIMATION WAS SELECTED
		if(empty($selected_animation))
		{
			$selected_animation = $default_animation;
		}
		
		#INITIALISE SELECT LIST HTML
		$select_list = '<select name="slide_animation_out" id="slide_animation_out" class="postform">' . "\n";
		
		#INITALISE ANIMATIONS
		$animations = array('No Animation', 'flash', 'bounce', 'shake', 'tada', 'swing', 'wobble', 'pulse',	'flip', 'flipOutX', 'flipOutY', 'fadeOut', 'fadeOutUp', 'fadeOutDown', 'fadeOutLeft', 'fadeOutRight', 'fadeOutUpBig', 'fadeOutDownBig', 'fadeOutLeftBig', 'fadeOutRightBig', 'bounceOut', 'bounceOutDown', 'bounceOutUp', 'bounceOutLeft', 'bounceOutRight', 'rotateOut', 'rotateOutDownLeft', 'rotateOutDownRight', 'rotateOutUpLeft', 'rotateOutUpRight', 'hinge', 'rollOut');
		
		#APPEND ANIMATIONS
		foreach($animations as $animation)
		{
			#SELECTED ANIMATION
			if($selected_animation == $animation)
			{
				$select_list .= '<option class="level-0" selected="selected" value="' . $animation . '">' . $animation . '</option>' . "\n";
			}
			#UNSELECTED ANIMATION
			else
			{
				$select_list .= '<option class="level-0" value="' . $animation . '">' . $animation . '</option>' . "\n";
			}
		}
		
		#CLOSE SELECT LIST HTML
		$select_list .= '</select>';
		
		#DISPLAY SELECT LIST
		echo $select_list;
	}
	
	#THIS FUNCTION DISPLAYS THE SLIDE TYPE RADIO BUTTONS
	private function mp_display_slide_type_radio_button($selected_slide_type)
	{
		#INITIALISE DEFAULT SLIDE TYPE
		$default_slide_type = 'text_image';
		
		#SELECT DEFAULT SLIDE TYPE IF NO SLIDE TYPE WAS SELECTED
		if(empty($selected_slide_type))
		{
			$selected_slide_type = $default_slide_type;
		}
		
		#INITIALISE SLIDE TYPES
		$slide_types = array
		(
			'Image + Caption' => 'text_image',
			'Image Only' => 'image',
			'Video' => 'video'
		);
		
		#DISPLAY SLIDE TYPES
		foreach($slide_types as $slide_type_key => $slide_type_value)
		{
			#SELECTED SLIDE TYPE
			if($selected_slide_type == $slide_type_value)
			{
				echo '<input type="radio" name="slide_type" id="' . $slide_type_value . '" value="' . $slide_type_value . '" checked="checked"><label for="' . $slide_type_value . '">' . $slide_type_key . '</label>&nbsp;&nbsp;&nbsp;';
			}
			#UNSELECTED SLIDE TYPE
			else
			{
				echo '<input type="radio" name="slide_type" id="' . $slide_type_value . '" value="' . $slide_type_value . '"><label for="' . $slide_type_value . '">' . $slide_type_key . '</label>&nbsp;&nbsp;&nbsp;';
			}
		}
	}
	
	#THIS FUNCTION SAVES THE SLIDE BOX FORM CONTENTS
	public function mp_meta_boxes_slide_form_save($post_id) 
	{
		#SAVE SLIDE BOX FORM CONTENTS
		mp_options::mp_meta_boxes_save($post_id, 'slide_nonce', 'slide_image', 'post');
		mp_options::mp_meta_boxes_save($post_id, 'slide_nonce', 'slide_video_url', 'post');
		mp_options::mp_meta_boxes_save($post_id, 'slide_nonce', 'slide_url', 'post');		
		mp_options::mp_meta_boxes_save($post_id, 'slide_nonce', 'slide_animation_in', 'post');
		mp_options::mp_meta_boxes_save($post_id, 'slide_nonce', 'slide_animation_out', 'post');
		mp_options::mp_meta_boxes_save($post_id, 'slide_nonce', 'slide_type', 'post');
		
		#RETURN POST ID
		return $post_id;
	}
	
	#THIS FUNCTION DISPLAYS THE SLIDES
	public function mp_display_slides()
	{
		#RETRIEVE THE POST
		global $post;
	
		#INITIALISE SLIDE ARGUMENTS
		$args = array
		(
			'post_type' => 'slide',
			'post_status' => 'publish',
			'posts_per_page' => 5,
			'order' => 'DESC',
			'orderby' => 'date'
		);
		
		#INITIALISE SLIDES
		$slides = new WP_Query($args);
		
		#SLIDES EXISTS
		if($slides->have_posts())
		{
			#OPEN SLIDE LIST
			echo '<ul class="slides">';
			
			#DISPLAY SLIDES
			while($slides->have_posts())
			{
				#RETRIEVE THE SLIDE CONTENT
				$slides->the_post();
			
				#INITIALISE THE SLIDE DETAILS
				$slide_title = get_the_title($post->ID);
				$slide_image = get_post_meta($post->ID, 'slide_image', true);
				$slide_video_url = get_post_meta($post->ID, 'slide_video_url', true);
				$slide_url = get_post_meta($post->ID, 'slide_url', true);
				$slide_animation_in = get_post_meta($post->ID, 'slide_animation_in', true);
				$slide_animation_out = get_post_meta($post->ID, 'slide_animation_out', true);
				$slide_type = get_post_meta($post->ID, 'slide_type', true);
				
				#FORMAT SLIDE IN ANIMATION
				if($slide_animation_in == 'No Animation')
				{
					$slide_animation_in = '';
				}
				#FORMAT SLIDE OUT ANIMATION
				if($slide_animation_out == 'No Animation')
				{
					$slide_animation_out = '';
				}
				
				#OPEN SLIDE LIST ITEM
				//echo '<li data-animate="' . $slide_animation_in . ', ' . $slide_animation_out . '">';
				echo '<li class="' . $slide_type . '">';
								
				#DISPLAY SLIDE ACCORDING TO SLIDE TYPE
				switch($slide_type)
				{
					#DISPLAY SLIDE IMAGE WITH SLIDE URL
					case 'image':
					
						echo '<a href="' . $slide_url . '"><img src="' . $slide_image . '" alt="' . $slide_title . '" title="' . $slide_title . '"></a>';
						break;
						
					#DISPLAY SLIDE TEXT + IMAGE
					case 'text_image':
					
						echo '<a href="' . $slide_url . '"><img src="' . $slide_image . '" alt="' . $slide_title . '" title="' . $slide_title . '"></a>';
						echo '<h6 class="flex-caption">' . get_the_content() . '</h6>';
						break;
					
					#DISPLAY SLIDE VIDEO
					case 'video':
					
						#INITIALISE YOUTUBE VIDEO ID
						$slide_video_url_parts = parse_url($slide_video_url);
						parse_str($slide_video_url_parts['query'], $slide_video_url_query);

						#DISPLAY YOUTUBE VIDEO EMBED CODE
						echo '<iframe width="640" height="360" src="//www.youtube.com/embed/' . $slide_video_url_query['v'] . '" frameborder="0" allowfullscreen></iframe>';
						break;
				}				
								
				#CLOSE SLIDE LIST ITEM
				echo '</li>';
			}
			
			#CLOSE SLIDE LIST
			echo '</ul>';
		}
	}
		
	/**************************************************************************
	6. PROJECT FUNCTIONS
	**************************************************************************/
	
	#THIS FUNCTION CREATES THE PROJECT CUSTOM POST TYPE
	public function mp_custom_posts_project()
	{
		#INITIALISE PROJECT CUSTOM POST TYPE LABELS
		$labels = array
		(
			'name' => _x('Projects', 'post type general name'),
			'singular_name' => _x('Project', 'post type singular name'),
			'add_new' => _x('Add New', 'project'),
			'add_new_item' => __('Add New Project'),
			'edit_item' => __('Edit Project'),
			'new_item' => __('New Project'),
			'all_items' => __('All Projects'),
			'view_item' => __('View Project'),
			'search_items' => __('Search Projects'),
			'not_found' =>  __('No Projects found'),
			'not_found_in_trash' => __('No Projects found in Trash'), 
			'parent_item_colon' => '',
			'menu_name' => 'Portfolio'
		);
		
		#INITIALISE PROJECT CUSTOM POST TYPE ARGUMENTS
		$args = array
		(
			'labels' => $labels,
			'description' => 'Project',
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true, 
			'show_in_menu' => true,
			'menu_position' => 20,
			'menu_icon' => null,
			'capability_type' => 'post',
			'hierarchical' => true,
			'supports' => array('title', 'editor', 'revisions', 'thumbnail', 'page-attributes'),
			'has_archive' => false,
			'rewrite' => array('slug' => 'portfolio/project', 'with_front' => false),
			'query_var' => true,
			'can_export' => true,
			'show_in_nav_menus' => true
		);
		
		#REGISTER PROJECT CUSTOM POST TYPE
		register_post_type('project', $args);
	}
	
	#THIS FUNCTION CREATES THE PROJECT CATEGORIES CUSTOM TAXONOMY
	public function mp_custom_taxonomies_project_categories()
	{
		#INITIALISE PROJECT CATEGORIES CUSTOM TAXONOMY LABELS
		$labels = array
		(
			'name' => _x('Project Categories', 'taxonomy general name'),
			'singular_name' => _x('Project Category', 'taxonomy singular name'),
			'search_items' =>  __('Search Project Categories'),
			'all_items' => __('All Project Categories'),
			'parent_item' => __('Parent Project Category'),
			'parent_item_colon' => __('Parent Project Category:'),
			'edit_item' => __('Edit Project Category'), 
			'update_item' => __('Update Project Category'),
			'add_new_item' => __('Add New Project Category'),
			'new_item_name' => __('New Project Category'),
			'menu_name' => __('Project Categories'),
			'choose_from_most_used' => __('Choose from the most used Project Categories')
		);
		
		#INITIALISE PROJECT CATEGORIES CUSTOM TAXONOMY ARGUMENTS
		$args = array
		(
			'labels' => $labels,	
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => false,
			'hierarchical' => true,
			'rewrite' => array('slug' => 'portfolio-categories', 'with_front' => false),
			'query_var' => true
		);
		
		#REGISTER PROJECT CATEGORIES CUSTOM TAXONOMY
		register_taxonomy('portfolio-categories', 'project', $args);
	}
	
	#THIS FUNCTION CREATES THE PROJECT SCOPE CUSTOM TAXONOMY
	public function mp_custom_taxonomies_project_scope()
	{
		#INITIALISE PROJECT SCOPE CUSTOM TAXONOMY LABELS
		$labels = array
		(
			'name' => _x('Project Scope', 'taxonomy general name'),
			'singular_name' => _x('Project Scope', 'taxonomy singular name'),
			'search_items' =>  __('Search Project Scope'),
			'all_items' => __('All Project Scope'),
			'parent_item' => __('Parent Project Scope'),
			'parent_item_colon' => __('Parent Project Scope:'),
			'edit_item' => __('Edit Project Scope'), 
			'update_item' => __('Update Project Scope'),
			'add_new_item' => __('Add New Project Scope'),
			'new_item_name' => __('New Project Scope'),
			'menu_name' => __('Project Scope'),
			'choose_from_most_used' => __('Choose from the most used Project Scope')
		);
		
		#INITIALISE PROJECT SCOPE CUSTOM TAXONOMY ARGUMENTS
		$args = array
		(
			'labels' => $labels,	
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => false,
			'hierarchical' => true,
			'rewrite' => array('slug' => 'portfolio-scope', 'with_front' => false),
			'query_var' => true
		);
		
		#REGISTER PROJECT SCOPE CUSTOM TAXONOMY
		register_taxonomy('portfolio-scope', 'project', $args);
	}
	
	#THIS FUNCTION CREATES THE PROJECT SKILLS CUSTOM TAXONOMY
	public function mp_custom_taxonomies_project_skills()
	{
		#INITIALISE PROJECT SKILLS CUSTOM TAXONOMY LABELS
		$labels = array
		(
			'name' => _x('Project Skills', 'taxonomy general name'),
			'singular_name' => _x('Project Skill', 'taxonomy singular name'),
			'search_items' =>  __('Search Project Skills'),
			'all_items' => __('All Project Skills'),
			'parent_item' => __('Parent Project Skill'),
			'parent_item_colon' => __('Parent Project Skill:'),
			'edit_item' => __('Edit Project Skill'), 
			'update_item' => __('Update Project Skill'),
			'add_new_item' => __('Add New Project Skill'),
			'new_item_name' => __('New Project Skill'),
			'menu_name' => __('Project Skills'),
			'choose_from_most_used' => __('Choose from the most used Project Skills')
		);
		
		#INITIALISE PROJECT SKILLS CUSTOM TAXONOMY ARGUMENTS
		$args = array
		(
			'labels' => $labels,	
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => false,
			'hierarchical' => true,
			'rewrite' => array('slug' => 'portfolio-skill', 'with_front' => false),
			'query_var' => true
		);
		
		#REGISTER PROJECT SKILLS CUSTOM TAXONOMY
		register_taxonomy('portfolio-skill', 'project', $args);
	}
	
	#THIS FUNCTION DISPLAYS THE PROJECT COLUMNS
	public function mp_project_edit_columns($columns)
	{
		#INITIALISE PROJECT COLUMNS
		$columns = 
		array
		(
			'cb' => '<input type="checkbox">',
			'title' => 'Title',
			'project_category' => 'Category',
			'project_scope' => 'Scope',
			'project_skills' => 'Skills',
			'project_thumbnail' => 'Thumbnail',
			'project_gallery' => 'Gallery',			
			'project_url' => 'URL',
			'client_name' => 'Client Name',
			'client_location' => 'Client Location',			
			'date' => 'Date',
			'order' => 'Order'
		);
		
		#RETURN PROJECT COLUMNS
		return $columns;
	}
	
	#THIS FUNCTION DISPLAYS THE SORTABLE PROJECT COLUMNS
	public function mp_project_sortable_columns()
	{
		#INITIALISE SORTABLE PROJECT COLUMNS
		$columns = 
		array
		(
			'title' => 'Title',
			'project_category' => 'Category',
			'client_name' => 'Client Name',
			'client_location' => 'Client Location',			
			'date' => 'Date',
			'order' => 'Order'
		);
		
		#RETURN SORTABLE PROJECT COLUMNS
		return $columns;
	}
	
	#THIS FUNCTION DISPLAYS THE PROJECT COLUMN VALUES
	public function mp_project_custom_columns($column)
	{
		#RETRIEVE THE POST
		global $post;
		
		#DISPLAY PROJECT VALUES
		switch($column)
		{
			#PROJECT CATEGORY
			case 'project_category':
			
				echo get_the_term_list($post->ID, 'portfolio-categories', '', ', ');
				break;
				
			#PROJECT SCOPE
			case 'project_scope':
			
				echo get_the_term_list($post->ID, 'portfolio-scope', '', ', ');
				break;
				
			#PROJECT SKILLS
			case 'project_skills':
			
				echo get_the_term_list($post->ID, 'portfolio-skill', '', ', ');
				break;
			
			#PROJECT THUMBNAIL
			case 'project_thumbnail':
				
				#PRODUCT THUMBNAIL EXISTS
				if(has_post_thumbnail($post->ID))
				{
					#INITIALISE PROJECT THUMBNAIL ARRAY
					$project_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
					
					#DISPLAY PROJECT THUMBNAIL
					echo '<img src="' . $project_thumbnail[0] . '" width="123" height="78" alt="">';
				}
				
				break;
				
			#PROJECT GALLERY
			case 'project_gallery':
				
				#INITIALISE PROJECT GALLERY
				$portfolio_project_gallery = get_post_meta($post->ID, 'portfolio_project_gallery', true);
				
				#DISPLAY PROJECT GALLERY ICON
				if(!empty($portfolio_project_gallery))
				{
					echo '<a href="admin.php?page=nggallery-manage-gallery&mode=edit&gid=' . $portfolio_project_gallery . '" target="_blank"><img src="' . get_bloginfo('template_url') . '/images/icon-gallery.png" alt=""></a>';
				}
				
				break;
			
			#PROJECT URL
			case 'project_url':
			
				#INITIALISE PROJECT URL
				$portfolio_project_url = get_post_meta($post->ID, 'portfolio_project_url', true);
				
				#DISPLAY PROJECT URL ICON
				if(!empty($portfolio_project_url))
				{
					echo '<a href="' . $portfolio_project_url . '" target="_blank"><img src="' . get_bloginfo('template_url') . '/images/icon-url.png" alt=""></a>';
				}
				
				break;
			
			#CLIENT NAME
			case 'client_name':
				
				#INITIALISE CLIENT NAME
				$portfolio_client_name = get_post_meta($post->ID, 'portfolio_client_name', true);
				
				#DISPLAY CLIENT NAME
				if(!empty($portfolio_client_name))
				{
					echo $portfolio_client_name;
				}
				
				break;
				
			#CLIENT LOCATION
			case 'client_location':
				
				#INITIALISE CLIENT LOCATION
				$portfolio_client_location = get_post_meta($post->ID, 'portfolio_client_location', true);
				
				#DISPLAY CLIENT LOCATION
				if(!empty($portfolio_client_location))
				{
					echo $portfolio_client_location;
				}
				
				break;
				
			#ORDER
			case 'order':
				
				#DISPLAY ORDER
				echo $post->menu_order;
				
				break;
		}
	}
		
	#THIS FUNCTION CREATES THE PROJECT BOX
	public function mp_meta_boxes_project()
	{
		#ADD PROJECT BOX TO PROJECT CUSTOM POSTS
		add_meta_box('portfolio_box', 'Project Information', array('mp_options', 'mp_meta_boxes_portfolio_form'), 'project', 'normal', 'high');
	 
		#SAVE PROJECT BOX FORM CONTENTS
		add_action('save_post', array('mp_options', 'mp_meta_boxes_portfolio_form_save'));
	}
	
	#THIS FUNCTION CREATES THE PROJECT BOX FORM
	public function mp_meta_boxes_portfolio_form()
	{
		#RETRIEVE THE POST
		global $post;
	
		#INITIALISE PROJECT OPTIONS
		$portfolio_client_name = get_post_meta($post->ID, 'portfolio_client_name', true);
		$portfolio_client_location = get_post_meta($post->ID, 'portfolio_client_location', true);
		$portfolio_project_url = get_post_meta($post->ID, 'portfolio_project_url', true);
		$portfolio_project_gallery = get_post_meta($post->ID, 'portfolio_project_gallery', true);
		
		#DISPLAY PROJECT NONCE FIELD
		echo '<input name="portfolio_nonce" id="portfolio_nonce" type="hidden" value="' . wp_create_nonce(__FILE__) . '">';
				
		#DISPLAY PROJECT FIELDS
		echo '<p><strong>Client Name:</strong><br><input name="portfolio_client_name" id="portfolio_client_name" type="text" size="80" value="' . $portfolio_client_name . '"></p><p>Enter the name of the client.</p>';
		
		echo '<p><strong>Client Location:</strong><br><input name="portfolio_client_location" id="portfolio_client_location" type="text" size="80" value="' . $portfolio_client_location . '"></p><p>Enter the location of the client.</p>';
		
		echo '<p><strong>Project URL:</strong><br><input name="portfolio_project_url" id="portfolio_project_url" type="text" size="80" value="' . urldecode($portfolio_project_url) . '"></p><p>Enter the project URL.</p>';
		
		echo '<p><strong>Project Gallery:</strong><br>'; mp_options::mp_display_gallery_list("portfolio_project_gallery", $portfolio_project_gallery); echo '</p><p>Select the gallery of the project.</p>';
		
		#LAUNCH VALIDATOR
		echo '<script>jQuery(document).ready(function() { mp_module_admin.run_validator_projects(); });</script>';		
	}
	
	#THIS FUNCTION DISPLAYS THE LIST OF PROJECTS
	public function mp_display_project_list($select_id, $selected_project)
	{
		#RETRIEVE THE POST
		global $post;
	
		#INITIALISE PROJECT ARGUMENTS
		$args = array
		(
			'post_type' => 'project',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC'
		);
		
		#INITIALISE PROJECTS
		$projects = new WP_Query($args);
		
		#PROJECTS EXISTS
		if($projects->have_posts())
		{			
			#INITIALISE SELECT LIST HTML
			$select_list = '<select name="' . $select_id . '" id="' . $select_id . '" class="postform">' . "\n";
			$select_list .= '<option class="level-0" value="">Select Project</option>' . "\n";
			
			#DISPLAY PROJECTS
			while($projects->have_posts())
			{
				#RETRIEVE THE PROJECT CONTENT
				$projects->the_post();  
				
				#SELECTED PROJECT
				if($selected_project == $post->ID)
				{
					$select_list .= '<option class="level-0" selected="selected" value="' . $post->ID . '">' . $post->post_title . '</option>' . "\n";
				}
				#UNSELECTED PROJECT
				else
				{
					$select_list .= '<option class="level-0" value="' . $post->ID . '">' . $post->post_title . '</option>' . "\n";
				}
			}
			
			#CLOSE SELECT LIST HTML
			$select_list .= '</select>';
			
			#DISPLAY SELECT LIST
			echo $select_list;
		}
	}
	
	#THIS FUNCTION DISPLAYS THE LIST OF GALLERIES
	public function mp_display_gallery_list($select_id, $selected_gallery)
	{
		#NEXTGEN GALLERY PLUGIN IS ACTIVATED
		if(function_exists('nggShowSlideshow'))
		{
			#RETRIEVE THE DATABASE
			global $wpdb;
			
			#RETREIVE ALL NEXTGEN GALLERIES
			$galleries = $wpdb->get_results("SELECT gid, title FROM $wpdb->prefix" . "ngg_gallery ORDER BY title ASC");
		
			#INITIALISE SELECT LIST HTML
			$select_list = '<select name="' . $select_id . '" id="' . $select_id . '" class="postform">' . "\n";
			$select_list .= '<option class="level-0" value="">Select Project Gallery</option>' . "\n";
			
			#DISPLAY OTHER NEXTGEN GALLERY SELECT LIST OPTIONS
			foreach($galleries as $gallery)
			{
				#DISPLAY SELECTED NEXTGEN GALLERY
				if($selected_gallery == $gallery->gid)
				{
					$select_list .= '<option class="level-0" selected="selected" value="' . $gallery->gid . '">' . stripslashes($gallery->title) . '</option>' . "\n";
				}
				#DISPLAY UNSELECTED NEXTGEN GALLERY
				else
				{
					$select_list .= '<option class="level-0" value="' . $gallery->gid . '">' . stripslashes($gallery->title) . '</option>' . "\n";
				}
			}
			
			#CLOSE SELECT LIST HTML
			$select_list .= '</select>';
			
			#DISPLAY SELECT LIST
			echo $select_list;
		}
	}
	
	#THIS FUNCTION SAVES THE PROJECT BOX FORM CONTENTS
	public function mp_meta_boxes_portfolio_form_save($post_id)
	{		
		#SAVE PROJECT BOX FORM CONTENTS
		mp_options::mp_meta_boxes_save($post_id, 'portfolio_nonce', 'portfolio_client_name', 'post');
		mp_options::mp_meta_boxes_save($post_id, 'portfolio_nonce', 'portfolio_client_location', 'post');
		mp_options::mp_meta_boxes_save($post_id, 'portfolio_nonce', 'portfolio_project_url', 'post');
		mp_options::mp_meta_boxes_save($post_id, 'portfolio_nonce', 'portfolio_project_gallery', 'post');
		
		#RETURN POST ID
		return $post_id;
	}
	
	#THIS FUNCTION DISPLAYS THE PROJECTS
	public function mp_display_projects($category, $page, $pagination = true, $ul_id = 'projects', $ul_class = '', $li_class = '', $strip_line_breaks = true, $max_words = 20)
	{		
		#RETRIEVE THE POST
		global $post;
		
		#INITIALISE PROJECT ARGUMENTS OF PROJECT PAGE
		if(empty($category))
		{
			#INITIALISE PROJECT ARGUMENTS
			$args = array
			(
				'post_type' => 'project',
				'post_status' => 'publish',
				'posts_per_page' => get_option('posts_per_page'),
				'paged' => $page,
				'order' => 'DESC',
				'orderby' => 'menu_order'
			);
		}
		#INITIALISE PROJECT ARGUMENTS OF PROJECT CATEGORIES
		else
		{
			#INITIALISE PROJECT ARGUMENTS
			$args = array
			(
				'post_type' => 'project',
				'post_status' => 'publish',
				'posts_per_page' => get_option('posts_per_page'),
				'paged' => $page,
				'order' => 'DESC',
				'orderby' => 'menu_order',
				'tax_query' =>
				array
				(
					array
					(
						'taxonomy' => 'portfolio-categories',
						'field' => 'slug',
						'terms' => $category
					)
				)
			);
		}
		
		#INITIALISE PROJECTS
		$projects = new WP_Query($args);
		
		#PROJECTS EXISTS
		if($projects->have_posts())
		{
			#OPEN PROJECT LIST
			echo '<ul id="' . $ul_id . '" class="' . $ul_class . '">';
			
			#DISPLAY PROJECTS
			while($projects->have_posts())
			{
				#RETRIEVE THE PROJECT CONTENT
				$projects->the_post();
				
				#INITIALISE PROJECT TITLE
				$project_title = $post->post_title;
				
				#OPEN PROJECT LIST ITEM
				echo '<li class="' . $li_class . '">';
				
				#OPEN PROJECT LINK
				echo '<a href="' . get_permalink() . '">';
				
				#PROJECT THUMBNAIL EXISTS
				if(has_post_thumbnail())
				{
					#INITIALISE PROJECT THUMBNAIL FILE
					$project_thumbnail_file = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
					
					#DISPLAY PROJECT THUMBNAIL
					echo '<img src="' . $project_thumbnail_file[0] . '" alt="' . $project_title . '" title="' . $project_title . '" class="project-thumbnail">';
				}
				#PROJECT THUMBNAIL DOES NOT EXIST
				else
				{
					#DISPLAY DEFAULT PROJECT THUMBNAIL
					echo '<img src="' . get_bloginfo('template_url') . '/images/portfolio-thumbnail-default.png" alt="' . $project_title . '" title="' . $project_title . '" class="project-thumbnail">';
				}
				
				#CLOSE PROJECT LINK
				echo '</a>';
				
				#DISPLAY PROJECT TITLE
				echo '<h2 class="project-title"><a href="' . get_permalink() . '" class="project-title-link">' . $project_title . '</a></h2>';
				
				#DISPLAY PROJECT CATEGORIES
				echo '<p class="project-categories">' . get_the_term_list($post->ID, 'portfolio-categories', '', ', ') . '</p>';
				
				#DISPLAY EXCERPT VIA THE ADVANCED EXCERPT PLUGIN
				if(function_exists('the_advanced_excerpt'))
				{
					the_advanced_excerpt("length=$max_words");
				}
				#DISPLAY EXCERPT VIA THE CLASS FUNCTION
				else
				{
					echo mp_options::mp_get_excerpt($max_words, true, $strip_line_breaks);
				}
				
				#CLOSE PROJECT LIST ITEM
				echo '</li>';				
			}
			
			#CLOSE PROJECT LIST
			echo '</ul>';
			
			#PAGING NAVIGATION IS ENABLED
			if($pagination)
			{
				#DISPLAY WP-PAGENAVI PAGING NAVIGATION LINKS
				if(function_exists('wp_pagenavi'))
				{
					wp_pagenavi(array('query' => $projects));
				}
				#DISPLAY DEFAULT WORDPRESS PAGING NAVIGATION LINKS
				else
				{
				?>
					<p class="left"><?php next_posts_link('&laquo; Previous Projects'); ?></p>
					<p class="right"><?php previous_posts_link('Next Projects &raquo;'); ?></p>
				<?php
				}
			}
		}
	}
	
	#THIS FUNCTION DISPLAYS THE PROJECT IMAGES
	public function mp_display_project_images($type = 'thumbnail', $link = false, $link_to = 'image', $li_class = '')
	{
		#NEXTGEN GALLERY PLUGIN IS ACTIVATED
		if(function_exists('nggShowSlideshow'))
		{
			#INITIALISE PROJECT GALLERY
			$portfolio_project_gallery = get_post_meta(get_the_ID(), 'portfolio_project_gallery', true);
			
			#PROJECT GALLERY EXISTS
			if(!empty($portfolio_project_gallery))
			{			
				#INITIALISE PROJECT GALLERY THUMBNAILS
				$project_thumbnails = nggdb::get_gallery($portfolio_project_gallery);
				
				#OPEN UNORDERED LIST
				echo '<ul class="slides">';
			
				#DISPLAY PROJECT GALLERY THUMBNAILS
				foreach($project_thumbnails as $project_thumbnail)
				{
					#OPEN PROJECT IMAGE LIST ITEM
					echo '<li class="' . $li_class . '">';
					
					#OPEN PROJECT IMAGE LINK
					if($link)
					{
						#INITIALISE THUMBNAIL LINK
						if($link_to == 'thumbnail')
						{
							$project_image_link = $project_thumbnail->thumbURL;
						}
						#INITIALISE IMAGE LINK
						elseif($link_to == 'image')
						{
							$project_image_link = $project_thumbnail->imageURL;
						}
						
						#DISPLAY PROJECT IMAGE LINK
						echo '<a href="' . $project_image_link . '" title="' . $project_thumbnail->alttext . ': ' . $project_thumbnail->description . '" class="project-image">';
					}
					
					#DISPLAY PROJECT THUMBNAIL
					if($type == 'thumbnail')
					{
						echo '<img src="' . $project_thumbnail->thumbURL . '" alt="' . $project_thumbnail->alttext . '">';
					}
					#DISPLAY PROJECT IMAGE
					elseif($type == 'image')
					{
						echo '<img src="' . $project_thumbnail->imageURL . '" alt="' . $project_thumbnail->alttext . '">';
					}
					
					#CLOSE PROJECT IMAGE LINK
					if($link)
					{
						echo '</a>';
					}
					
					#CLOSE PROJECT IMAGE LIST ITEM
					echo '</li>';
				}
				
				#CLOSE UNORDERED LIST
				echo '</ul>';
			}
		}
		#NEXTGEN GALLERY PLUGIN IS NOT ACTIVATED
		else
		{
			return;
		}		
	}
	
	#THIS FUNCTION DISPLAYS THE PROJECT DETAILS
	public function mp_display_project_details()
	{
		#INITIALISE PROJECT DETAILS
		$portfolio_client_name = get_post_meta(get_the_ID(), 'portfolio_client_name', true);
		$portfolio_client_location = get_post_meta(get_the_ID(), 'portfolio_client_location', true);
		$portfolio_project_url = get_post_meta(get_the_ID(), 'portfolio_project_url', true);
		$portfolio_project_gallery = get_post_meta(get_the_ID(), 'portfolio_project_gallery', true);
		$portfolio_project_scope_terms = get_the_terms(get_the_ID(), 'portfolio-scope');
		$portfolio_project_skill_terms = get_the_terms(get_the_ID(), 'portfolio-skill');
		
		#OPEN PROJECT DETAILS DIV
		echo '<div class="project-details">';
				
		#OPEN TABLE
		echo '<table>';
		
		#PROJECT URL EXISTS
		if(!empty($portfolio_project_url))
		{
			#DISPLAY PROJECT URL
			echo '<tr><td class="column1">URL:</td><td class="column2"><a href="' . $portfolio_project_url . '" rel="nofollow">' . mp_options::mp_trim_project_url($portfolio_project_url, 25) . "</a></td></tr>";
		}
		
		#CLIENT NAME & LOCATION EXISTS
		if(!empty($portfolio_client_name) && !empty($portfolio_client_location))
		{
			#DISPLAY PROJECT URL
			echo '<tr><td class="column1">Client:</td><td class="column2">' . $portfolio_client_name . ', <em>' . $portfolio_client_location . "</em></td></tr>";
		}
		
		#CLIENT NAME EXISTS ONLY
		if(!empty($portfolio_client_name) && empty($portfolio_client_location))
		{
			#DISPLAY PROJECT URL
			echo '<tr><td class="column1">Client:</td><td class="column2">' . $portfolio_client_name . "</td></tr>";
		}
		
		#PROJECT SCOPE EXISTS
		if($portfolio_project_scope_terms && ! is_wp_error($portfolio_project_scope_terms))
		{
			#INITIALISE PROJECT SCOPE LIST
			$portfolio_project_scope_list = array();
		
			#APPEND PROJECT SCOPE TO PROJECT SCOPE LIST
			foreach($portfolio_project_scope_terms as $portfolio_project_scope_term)
			{
				$portfolio_project_scope_list[] = $portfolio_project_scope_term->name;
			}
			
			#DISPLAY PROJECT SCOPE LIST
			echo '<tr><td class="column1">Scope:</td><td class="column2">' . join(", ", $portfolio_project_scope_list) . "</td></tr>";
		}
		
		#PROJECT SKILLS EXISTS
		if($portfolio_project_skill_terms && ! is_wp_error($portfolio_project_skill_terms))
		{
			#INITIALISE PROJECT SKILL LIST
			$portfolio_project_skill_list = array();
		
			#APPEND PROJECT SKILL TO PROJECT SKILL LIST
			foreach($portfolio_project_skill_terms as $portfolio_project_skill_term)
			{
				$portfolio_project_skill_list[] = $portfolio_project_skill_term->name;
			}
			
			#DISPLAY PROJECT SKILL LIST
			echo '<tr><td class="column1">Skills:</td><td class="column2">' . join(", ", $portfolio_project_skill_list) . "</td></tr>";
		}
		
		#CLOSE TABLE
		echo '</table>';
		
		#CLOSE PROJECT DETAILS DIV
		echo '</div>';
	}
	
	#THIS FUNCTION TRIMS THE PROJECT URL TO FIX INTO THE PROJECT DETAILS BOX
	public function mp_trim_project_url($project_url, $max_characters)
	{
		#PROJECT URL IS OVER THE MAXIMUM NUMBER OF CHARACTERS
		if(strlen($project_url) > $max_characters)
		{
			$project_url = substr($project_url, 0, $max_characters) . "...";
		}
		
		#RETURN PROJECT URL
		return $project_url;
	}
	
	#THIS FUNCTION DISPLAYS THE PROJECT CATEGORIES IN THE SIDEBAR
	public function mp_display_portfolio_categories($current_category)
	{
		#CUSTOM TAXONOMY ORDER NE PLUGIN ACTIVATED
		if(function_exists('customtaxorder'))
		{
			#INITIALISE CATEGORIES WITH CUSTOM SORT ORDER
			$categories = get_terms('portfolio-categories', 'orderby=term_order&order=ASC&hide_empty=1');
		}
		#CUSTOM TAXONOMY SORT PLUGIN DEACTIVATED
		else
		{
			#INITIALISE CATEGORIES WITH NAME SORT ORDER
			$categories = get_terms('portfolio-categories', 'orderby=name&hide_empty=1');
		}
		
		#CATEGORIES EXIST
		if(count($categories) > 0)
		{			
			#DISPLAY CATEGORY LINKS
			foreach($categories as $category)
			{
				#DISPLAY SELECTED SUB CATEGORY LINKS
				if($category->slug == $current_category)
				{
					echo '<li class="current"><a href="' . get_term_link($category->slug, "portfolio-categories") . '">' . $category->name . ' (' . $category->count . ')</a></li>';
				}
				#DISPLAY UNSELECTED SUB CATEGORY LINKS
				else
				{
					echo '<li><a href="' . get_term_link($category->slug, "portfolio-categories") . '">' . $category->name . ' (' . $category->count . ')</a></li>';
				}
			}
		}
	}
	
	/**************************************************************************
	7. TESTIMONIAL FUNCTIONS
	**************************************************************************/
	
	#THIS FUNCTION CREATES THE TESTIMONIALS CUSTOM POST TYPE
	public function mp_custom_posts_testimonials()
	{
		#INITIALISE TESTIMONIAL CUSTOM POST TYPE LABELS
		$labels = array
		(
			'name' => _x('Testimonials', 'post type general name'),
			'singular_name' => _x('Testimonial', 'post type singular name'),
			'add_new' => _x('Add New', 'testimonial'),
			'add_new_item' => __('Add New Testimonial'),
			'edit_item' => __('Edit Testimonial'),
			'new_item' => __('New Testimonial'),
			'all_items' => __('All Testimonials'),
			'view_item' => __('View Testimonial'),
			'search_items' => __('Search Testimonials'),
			'not_found' =>  __('No Testimonials found'),
			'not_found_in_trash' => __('No Testimonials found in Trash'), 
			'parent_item_colon' => '',
			'menu_name' => 'Testimonials'
		);
		
		#INITIALISE TESTIMONIAL CUSTOM POST TYPE ARGUMENTS
		$args = array
		(
			'labels' => $labels,
			'description' => 'Testimonial',
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true, 
			'show_in_menu' => true,
			'menu_position' => 20,
			'menu_icon' => null,
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array('title', 'editor', 'revisions'),
			'has_archive' => false,
			'rewrite' => array('slug' => 'testimonial', 'with_front' => false),
			'query_var' => false,
			'can_export' => true,
			'show_in_nav_menus' => true
		);
		
		#REGISTER TESTIMONIAL CUSTOM POST TYPE
		register_post_type('testimonial', $args);
	}
	
	#THIS FUNCTION DISPLAYS THE TESTIMONIAL COLUMNS
	public function mp_testimonial_edit_columns($columns)
	{
		#INITIALISE TESTIMONIAL COLUMNS
		$columns = 
		array
		(
			'cb' => '<input type="checkbox">',
			'title' => 'Title',
			'project' => 'Project',
			'name' => 'Name',
			'location' => 'Location',
			'photo' => 'Photo',
			'url' => 'URL',
			'pdf' => 'PDF',
			'feature' => 'Feature',
			'date' => 'Date'
		);
		
		#RETURN TESTIMONIAL COLUMNS
		return $columns;
	}
	
	#THIS FUNCTION DISPLAYS THE SORTABLE TESTIMONIAL COLUMNS
	public function mp_testimonial_sortable_columns()
	{
		#INITIALISE SORTABLE TESTIMONIAL COLUMNS
		$columns = 
		array
		(
			'title' => 'Title',
			'project' => 'Project',
			'name' => 'Name',
			'location' => 'Location',
			'feature' => 'Feature',
			'date' => 'Date'
		);
		
		#RETURN SORTABLE TESTIMONIAL COLUMNS
		return $columns;
	}
	
	#THIS FUNCTION DISPLAYS THE TESTIMONIAL COLUMN VALUES
	public function mp_testimonial_custom_columns($column)
	{
		#RETRIEVE THE POST
		global $post;
		
		#DISPLAY TESTIMONIAL VALUES
		switch($column)
		{
			#TESTIMONIAL PROJECT
			case 'project':
			
				#INITIALISE TESTIMONIAL PROJECT ID
				$testimonial_project = get_post_meta($post->ID, 'testimonial_project', true);
			
				#INITIALISE TESTIMONIAL PROJECT TITLE
				$testimonial_project_title = get_the_title($testimonial_project);
				
				#DISPLAY TESTIMONIAL PROJECT TITLE WITH LINK
				if(!empty($testimonial_project_title))
				{
					echo '<a href="post.php?post=' . $testimonial_project . '&action=edit">' . $testimonial_project_title . '</a>';
				}
				
				break;
			
			#TESTIMONIAL NAME
			case 'name':
				
				#INITIALISE TESTIMONIAL NAME
				$testimonial_name = get_post_meta($post->ID, 'testimonial_name', true);
				
				#DISPLAY TESTIMONIAL NAME
				if(!empty($testimonial_name))
				{
					echo $testimonial_name;
				}
				
				break;
				
			#TESTIMONIAL LOCATION
			case 'location':
				
				#INITIALISE TESTIMONIAL LOCATION
				$testimonial_location = get_post_meta($post->ID, 'testimonial_location', true);
				
				#DISPLAY TESTIMONIAL LOCATION
				if(!empty($testimonial_location))
				{
					echo $testimonial_location;
				}
				
				break;
				
			#TESTIMONIAL PHOTO
			case 'photo':
				
				#INITIALISE TESTIMONIAL NAME & PHOTO
				$testimonial_name = get_post_meta($post->ID, 'testimonial_name', true);
				$testimonial_photo = get_post_meta($post->ID, 'testimonial_photo', true);
				
				#DISPLAY TESTIMONIAL PHOTO ICON
				if(!empty($testimonial_photo))
				{
					echo '<a href="' . $testimonial_photo . '" title="' . $testimonial_name . '"><img src="' . get_bloginfo('template_url') . '/images/icon-picture.png" alt=""></a>';
				}
				
				break;
				
			#TESTIMONIAL URL
			case 'url':
			
				#INITIALISE TESTIMONIAL URL
				$testimonial_url = get_post_meta($post->ID, 'testimonial_url', true);
				
				#DISPLAY TESTIMONIAL URL ICON
				if(!empty($testimonial_url))
				{
					echo '<a href="' . $testimonial_url . '" target="_blank"><img src="' . get_bloginfo('template_url') . '/images/icon-url.png" alt=""></a>';
				}
				
				break;
				
			#TESTIMONIAL PDF
			case 'pdf':
			
				#INITIALISE TESTIMONIAL PDF
				$testimonial_pdf = get_post_meta($post->ID, 'testimonial_pdf', true);
				
				#DISPLAY TESTIMONIAL PDF ICON
				if(!empty($testimonial_pdf))
				{
					echo '<a href="' . $testimonial_pdf . '" target="_blank"><img src="' . get_bloginfo('template_url') . '/images/icon-pdf.png" alt=""></a>';
				}
				
				break;
				
			#TESTIMONIAL FEATURE
			case 'feature':
			
				#INITIALISE TESTIMONIAL FEATURE
				$testimonial_feature = get_post_meta($post->ID, 'testimonial_feature', true);
				
				#DISPLAY TESTIMONIAL FEATURE ICON
				if($testimonial_feature)
				{
					echo '<img src="' . get_bloginfo('template_url') . '/images/icon-pin.png" alt="">';
				}
				
				break;
		}
	}
	
	#THIS FUNCTION CREATES THE TESTIMONIAL BOX
	public function mp_meta_boxes_testimonial()
	{
		#ADD TESTIMONIAL BOX TO TESTIMONIAL CUSTOM POSTS
		add_meta_box('testimonial_box', 'Testimonial Information', array('mp_options', 'mp_meta_boxes_testimonial_form'), 'testimonial', 'normal', 'high');
	 
		#SAVE TESTIMONIAL BOX FORM CONTENTS
		add_action('save_post', array('mp_options', 'mp_meta_boxes_testimonial_form_save'));
	}
	
	#THIS FUNCTION CREATES THE TESTIMONIAL BOX FORM
	public function mp_meta_boxes_testimonial_form()
	{
		#RETRIEVE THE POST
		global $post;
		
		#INITIALISE TESTIMONIAL OPTIONS
		$testimonial_project = get_post_meta($post->ID, 'testimonial_project', true);
		$testimonial_name = get_post_meta($post->ID, 'testimonial_name', true);
		$testimonial_location = get_post_meta($post->ID, 'testimonial_location', true);
		$testimonial_photo = get_post_meta($post->ID, 'testimonial_photo', true);
		$testimonial_url = get_post_meta($post->ID, 'testimonial_url', true);
		$testimonial_pdf = get_post_meta($post->ID, 'testimonial_pdf', true);
		$testimonial_feature = get_post_meta($post->ID, 'testimonial_feature', true);
		
		#DISPLAY TESTIMONIAL NONCE FIELD
		echo '<input name="testimonial_nonce" id="testimonial_nonce" type="hidden" value="' . wp_create_nonce(__FILE__) . '">';
				
		#DISPLAY TESTIMONIAL FIELDS
		echo '<p><strong>Project:</strong><br>'; mp_options::mp_display_project_list("testimonial_project", $testimonial_project); echo '</p><p>Select the project of the testimonial.</p>';
		
		echo '<p><strong>Name:</strong><br><input name="testimonial_name" id="testimonial_name" type="text" size="80" value="' . $testimonial_name . '"></p><p>Enter the name of the person who wrote the testimonial.</p>';
		
		echo '<p><strong>Location:</strong><br><input name="testimonial_location" id="testimonial_location" type="text" size="80" value="' . $testimonial_location . '"></p><p>Enter the location of the person who wrote the testimonial.</p>';
		
		echo '<p><strong>Photo:</strong><br>'; mp_options::mp_display_media_upload('testimonial_photo', urldecode($testimonial_photo)); echo '</p><p>Enter the photo URL of the person who wrote the testimonial.</p>';
		
		echo '<p><strong>URL:</strong><br><input name="testimonial_url" id="testimonial_url" type="text" size="80" value="' . urldecode($testimonial_url) . '"></p><p>Enter the URL of the person who wrote the testimonial.</p>';
		
		echo '<p><strong>PDF:</strong><br><input name="testimonial_pdf" id="testimonial_pdf" type="text" size="80" value="' . urldecode($testimonial_pdf) . '"></p><p>Enter the PDF document URL of the testimonial.</p>';
		
		echo '<p><strong>Feature on Home Page:</strong><br>'; mp_options::mp_display_yes_no_list('testimonial_feature', $testimonial_feature); echo '</p><p>Select whether you wish to display this testimonial on the home page.</p>';
		
		#LAUNCH VALIDATOR
		echo '<script>jQuery(document).ready(function() { mp_module_admin.run_validator_testimonials(); });</script>';
	}
	
	#THIS FUNCTION SAVES THE TESTIMONIAL BOX FORM CONTENTS
	public function mp_meta_boxes_testimonial_form_save($post_id) 
	{
		#SAVE TESTIMONIAL BOX FORM CONTENTS
		mp_options::mp_meta_boxes_save($post_id, 'testimonial_nonce', 'testimonial_project', 'post');
		mp_options::mp_meta_boxes_save($post_id, 'testimonial_nonce', 'testimonial_name', 'post');
		mp_options::mp_meta_boxes_save($post_id, 'testimonial_nonce', 'testimonial_location', 'post');
		mp_options::mp_meta_boxes_save($post_id, 'testimonial_nonce', 'testimonial_photo', 'post');
		mp_options::mp_meta_boxes_save($post_id, 'testimonial_nonce', 'testimonial_url', 'post');
		mp_options::mp_meta_boxes_save($post_id, 'testimonial_nonce', 'testimonial_pdf', 'post');
		mp_options::mp_meta_boxes_save($post_id, 'testimonial_nonce', 'testimonial_feature', 'post');
		
		#RETURN POST ID
		return $post_id;
	}
	
	#THIS FUNCTION DISPLAYS THE TESTIMONIALS
	public function mp_display_testimonials($scope, $page, $pagination = true, $max_words = 100)
	{
		#RETRIEVE THE POST
		global $post;
		
		#INITIALISE TESTIMONIAL ARGUMENTS OF PROJECT PAGE
		if($scope == 'project')
		{
			#INITIALISE TESTIMONIAL ARGUMENTS
			$args = array
			(
				'post_type' => 'testimonial',
				'post_status' => 'publish',
				'posts_per_page' => get_option('posts_per_page'),
				'paged' => $page,
				'order' => 'DESC',
				'orderby' => 'date',
				'meta_query' =>
				array
				(
					array
					(
						'key' => 'testimonial_project',
						'value' => get_the_ID()
					)
				)
			);
		}
		#INITIALISE TESTIMONIAL ARGUMENTS OF TESTIMONIALS PAGE
		elseif($scope == 'testimonials')
		{
			#INITIALISE TESTIMONIAL ARGUMENTS
			$args = array
			(
				'post_type' => 'testimonial',
				'post_status' => 'publish',
				'posts_per_page' => get_option('posts_per_page'),
				'paged' => $page,
				'order' => 'DESC',
				'orderby' => 'date'
			);
		}
		#INITIALISE TESTIMONIAL ARGUMENTS OF FEATURED TESTIMONIALS ON HOME PAGE
		elseif($scope == 'home')
		{
			#INITIALISE TESTIMONIAL ARGUMENTS
			$args = array
			(
				'post_type' => 'testimonial',
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'paged' => $page,
				'order' => 'DESC',
				'orderby' => 'rand',
				'meta_query' =>
				array
				(
					array
					(
						'key' => 'testimonial_feature',
						'value' => 1
					)
				)
			);
		}	
	
		#INITIALISE TESTIMONIALS
		$testimonials = new WP_Query($args);
		
		#TESTIMONIALS EXISTS
		if($testimonials->have_posts())
		{
			#DISPLAY TESTIMONIAL SUB HEADING
			if($scope == 'project' || $scope == 'home')
			{
				echo '<h3 class="sub-heading">Testimonials</h3>';
			}
			
			#DISPLAY TESTIMONIAL
			while($testimonials->have_posts())
			{
				#RETRIEVE THE TESTIMONIAL CONTENT
				$testimonials->the_post();
				
				#INITIALISE TESTIMONIAL DETAILS
				$testimonial_name = get_post_meta($post->ID, 'testimonial_name', true);
				$testimonial_location = get_post_meta($post->ID, 'testimonial_location', true);
				$testimonial_photo = get_post_meta($post->ID, 'testimonial_photo', true);
				$testimonial_url = get_post_meta($post->ID, 'testimonial_url', true);
				$testimonial_pdf = get_post_meta($post->ID, 'testimonial_pdf', true);
				
				#INITIALISE TESTIMONIAL CONTENT FOR NON-PROJECT PAGES
				if($scope == 'home' || $scope == 'testimonials')
				{
					$testimonial_content = mp_options::mp_get_excerpt($max_words);
				}
				#INITIALISE TESTIMONIAL CONTENT FOR PROJECT PAGE
				else
				{
					$testimonial_content = get_the_content();
				}		
				
				#APPEND TESTIMONIAL NAME & LOCATION
				$testimonial_content .= '<br><br>- ' . $testimonial_name . ', ' . $testimonial_location;
				
				#APPEND TESTIMONIAL URL
				if(!empty($testimonial_url))
				{
					$testimonial_content .= ', <a href="' . $testimonial_url . '" rel="nofollow">' . $testimonial_url . '</a>';
				}
				
				#APPEND TESTIMONIAL PHOTO
				if(!empty($testimonial_photo))
				{					
					$testimonial_content = '<img src="' . $testimonial_photo . '" alt="'. $testimonial_name . '" title="'. $testimonial_name . '" class="testimonial-photo">' . $testimonial_content;
				}
				
				#APPEND TESTIMONIAL PDF
				if(!empty($testimonial_pdf))
				{
					$testimonial_content .= ', <a href="' . $testimonial_pdf . '" title="Testimonial in PDF" rel="nofollow" target="_blank"><img src="' . get_bloginfo('template_url') . '/images/icon-pdf.png" alt="Testimonial in PDF" title="Testimonial in PDF" class="testimonial-pdf">PDF</a>';
				}
				
				#DISPLAY TESTIMONIAL BOX
				echo mp_options::mp_testimonial_shortcode("", $testimonial_content);
			}
			
			#PAGING NAVIGATION IS ENABLED
			if($pagination)
			{		
				#DISPLAY WP-PAGENAVI PAGING NAVIGATION LINKS
				if(function_exists('wp_pagenavi'))
				{
					wp_pagenavi(array('query' => $testimonials));
				}
				#DISPLAY DEFAULT WORDPRESS PAGING NAVIGATION LINKS
				else

				{
				?>
					<p class="left"><?php next_posts_link('&laquo; Previous Testimonials'); ?></p>
					<p class="right"><?php previous_posts_link('Next Testimonials &raquo;'); ?></p>
				<?php
				}
			}
		}
		#TESTIMONIALS DO NOT EXIST
		else
		{
			return;	
		}
	}
	
	#THIS FUNCTION DISPLAYS A SINGLE TESTIMONIAL
	public function mp_display_testimonial()
	{
		#RETRIEVE THE POST
		global $post;
		
		#INITIALISE TESTIMONIAL DETAILS
		$testimonial_name = get_post_meta($post->ID, 'testimonial_name', true);
		$testimonial_location = get_post_meta($post->ID, 'testimonial_location', true);
		$testimonial_photo = get_post_meta($post->ID, 'testimonial_photo', true);
		$testimonial_url = get_post_meta($post->ID, 'testimonial_url', true);
		$testimonial_pdf = get_post_meta($post->ID, 'testimonial_pdf', true);
		$testimonial_project = get_post_meta($post->ID, 'testimonial_project', true);
		
		#INITIALISE TESTIMONIAL CONTENT
		$testimonial_content = get_the_content();
		
		#APPEND TESTIMONIAL NAME & LOCATION
		$testimonial_content .= '<br><br>- ' . $testimonial_name . ', ' . $testimonial_location;
		
		#APPEND TESTIMONIAL URL
		if(!empty($testimonial_url))
		{
			$testimonial_content .= ', <a href="' . $testimonial_url . '" rel="nofollow">' . $testimonial_url . '</a>';
		}
		
		#APPEND TESTIMONIAL PHOTO
		if(!empty($testimonial_photo))
		{					
			$testimonial_content = '<img src="' . $testimonial_photo . '" alt="'. $testimonial_name . '" title="'. $testimonial_name . '" class="testimonial-photo">' . $testimonial_content;
		}
		
		#APPEND TESTIMONIAL PDF
		if(!empty($testimonial_pdf))
		{
			$testimonial_content .= ', <a href="' . $testimonial_pdf . '" title="Testimonial in PDF" rel="nofollow" target="_blank"><img src="' . get_bloginfo('template_url') . '/images/icon-pdf.png" alt="Testimonial in PDF" title="Testimonial in PDF" class="testimonial-pdf">PDF</a>';
		}
		
		#DISPLAY TESTIMONIAL PROJECT
		if(!empty($testimonial_project))
		{
			echo '<h3 class="sub-heading">Project: <a href="' . get_permalink($testimonial_project) . '">' . get_the_title($testimonial_project) . '</a></h3>';
		}
		
		#DISPLAY TESTIMONIAL BOX
		echo mp_options::mp_testimonial_shortcode('', $testimonial_content);	
	}
	
	#THIS FUNCTION ADDS CONTENT TO A TESTIMONIAL BOX
	public function mp_testimonial_shortcode($parameters, $content = null)
	{
		#ADD CONTENT TO TESTIMONIAL BOX
		$content = '<div class="testimonial-box"><div class="testimonial">' . wpautop($content) . '</div></div>';
		
		#RETURN CONTENT
		return do_shortcode($content);
	}
	
	/**************************************************************************
	8. AUTHOR FUNCTIONS
	**************************************************************************/
			
	#THIS FUNCTION REPLACES THE "BIOGRAPHICAL INFO" FIELD IN THE USER PROFILE WITH A TINYMCE EDITOR
	public function mp_tinymce_biography($user)
	{
		?>
		<table class="form-table">
		<tr>
			<th><label for="description"><?php _e('Biographical Info'); ?></label></th>
			<td><?php wp_editor(get_user_meta($user->ID, 'description', true), 'description', array('textarea_rows' => 20)); ?><p class="description"><?php _e('Share a little biographical information to fill out your profile. This may be shown publicly.'); ?></p></td>
		</tr>
		</table>
		<?php
	}
	
	#THIS FUNCTION UPDATES THE USER PROFILE CONTACT INFO FIELDS
	public function mp_contact_info($contact_fields)
	{
		#DELETE AIM, YIM & JABBER FIELDS
		unset($contact_fields['aim']);
		unset($contact_fields['jabber']);
		unset($contact_fields['yim']);
		unset($contact_fields['googleplus']);
		
		#ADD FACEBOOK, TWITTER, GOOGLE+, PINTEREST, LINKEDIN, GITHUB, DRIBBLE, INSTAGRAM, INSTAGRAM RSS FEED
		$contact_fields['facebook'] = 'Facebook URL';
		$contact_fields['twitter'] = 'Twitter Username';
		$contact_fields['google_plus'] = 'Google+ URL';
		$contact_fields['pinterest'] = 'Pinterest Username';
		$contact_fields['linkedin'] = 'LinkedIn URL';
		$contact_fields['github'] = 'Github Username';
		$contact_fields['dribbble'] = 'Dribbble Username';
		$contact_fields['dribbble_rss'] = 'Dribbble RSS Feed';
		$contact_fields['instagram'] = 'Instagram URL';
		$contact_fields['instagram_rss'] = 'Instagram RSS Feed';
		
		return $contact_fields;
	}
	
	#THIS FUNCTION RETURNS THE AUTHOR ID
	public function mp_get_author_id()
	{
		#INITIALISE AUTHOR ID
		$mp_author = get_option('mp_author');
			
		#SET DEFAULT AUTHOR ID
		if(empty($mp_author))
		{
			$mp_author = 1;
		}
		
		#RETURN AUTHOR ID
		return $mp_author;
	}
	
	#THIS FUNCTION DISPLAYS THE LIST OF AUTHORS
	private function mp_display_author_list($select_id, $selected_author, $default_author = 1)
	{
		#RETRIEVE THE DATABASE
		global $wpdb;
		
		#INITIALISE AUTHORS
		$authors = $wpdb->get_results("SELECT ID, display_name from $wpdb->users ORDER BY ID");
		
		#SELECT DEFAULT AUTHOR IF NO AUTHOR WAS SELECTED
		if(empty($selected_author) && !empty($default_author))
		{
			$selected_author = $default_author;
		}
		
		#INITIALISE SELECT LIST HTML
		$select_list = '<select name="' . $select_id . '" id="' . $select_id . '" class="postform">' . "\n";
		
		#APPEND AUTHORS
		foreach($authors as $author)
		{
			#SELECTED AUTHOR
			if($selected_author == $author->ID)
			{
				$select_list .= '<option class="level-0" selected="selected" value="' . $author->ID . '">' . $author->display_name . '</option>' . "\n";
			}
			#UNSELECTED AUTHOR
			else
			{
				$select_list .= '<option class="level-0" value="' . $author->ID . '">' . $author->display_name . '</option>' . "\n";
			}
		}
		
		#CLOSE SELECT LIST HTML
		$select_list .= '</select>';
		
		#DISPLAY SELECT LIST
		echo $select_list;
	}
	
	/**************************************************************************
	9. SOCIAL FUNCTIONS
	**************************************************************************/
	
	#THIS FUNCTION DISPLAYS THE INSTAGRAM THUMBNAILS
	public function mp_display_instagram_thumbnails()
	{	
		#INITIALISE INSTAGRAM RSS FEED
		$instagram_rss = get_user_meta(mp_options::mp_get_author_id(), 'instagram_rss', true);
		
		#INSTAGRAM RSS FEED EXISTS
		if(!empty($instagram_rss))
		{
			#INCLUDE SIMPLEPIE RSS PARSER
			include_once(ABSPATH.WPINC . '/class-simplepie.php');
			
			#INITIALISE SIMPLEPIE OBJECT
			$feed = new SimplePie();
			 
			#INITIALISE SIMPLEPIE FEED
			$feed->set_feed_url($instagram_rss);
				
			#INITIALISE SIMPLEPIE CACHE LOCATION
			$feed->set_cache_location(dirname(dirname(__FILE__)) . '/cache');
			 
			#RUN SIMPLEPIE FEED
			$feed->init();
			
			#OPEN UNORDERED LIST
			echo '<ul>';
			
			#DISPLAY INSTAGRAM THUMBNAILS
			foreach($feed->get_items(0, 12) as $item)
			{
				#FORMAT INSTAGRAM THUMBNAIL WITH THUMBNAIL IMAGE & IMAGE TITLE IN ALT/TITLE ATTRIBUTE
				$instagram_thumbnail = str_replace('_7.jpg', '_5.jpg', $item->get_description());
				//$instagram_thumbnail = str_replace('">', '" alt="' . $item->get_title() . '" title="' . $item->get_title() . '">', $instagram_thumbnail);
				
				#DISPLAY INSTAGRAM THUMBNAIL
				echo '<li><a href="' . $item->get_permalink() . '" rel="nofollow" class="instagram-iframe"><span class="magnify"></span>' . $instagram_thumbnail . '</a></li>' . "\n";
			}
			
			#CLOSE UNORDERED LIST
			echo '</ul>';
		}
	}
	
	public function oauth_base_string($baseURI, $method, $params)
	{
		$r = array();
		
		ksort($params);
		
		foreach($params as $key=>$value)
		{
			$r[] = "$key=" . rawurlencode($value);
		}
		
		return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
	}
	
	public function oauth_authorization_header($oauth)
	{
		$r = 'Authorization: OAuth ';
		
		$values = array();
		
		foreach($oauth as $key=>$value)
		{
			$values[] = "$key=\"" . rawurlencode($value) . "\"";
		}
		
		$r .= implode(', ', $values);
		
		return $r;
	}
	
	#THIS FUNCTION DISPLAYS THE TWITTER TWEETS
	public function mp_display_twitter_tweets()
	{
		#INITIALISE TWITTER TWEETS
		$mp_footer_twitter = get_option('mp_footer_twitter');
		
		#TWITTER TWEETS ENABLED
		if(!empty($mp_footer_twitter))
		{
			#DISPLAY TWITTER HEADING
			echo '<h5>Twitter</h5>';
		
			#INITIALISE TWITTER USERNAME
			$twitter = get_user_meta(mp_options::mp_get_author_id(), 'twitter', true);
			
			#INITIALISE TWITTER API URL
			$twitter_api_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

			#INITIALISE TWITTER OAUTH SETTINGS
			$oauth_access_token = '17037036-IlRoBHMrxAVe83fGf1rrhkF1nTTxhxaXICG0TOMXo';
			$oauth_access_token_secret = 'smHXAcXEqjmeR2bxRe90Qqfzp9mDbQEs9KJAAP4AU';
			$consumer_key = 'gvU14iYCg6NlITv6AcGWw';
			$consumer_secret = 'GTl4kl9gUgJ3Ao2gZQDfm4TVTEcfrSA1buiP35j4YM';
			$oauth = array
			(
				'screen_name' => $twitter,
        		'count' => 5,
				'oauth_consumer_key' => $consumer_key,
				'oauth_nonce' => time(),
				'oauth_signature_method' => 'HMAC-SHA1',
				'oauth_token' => $oauth_access_token,
				'oauth_timestamp' => time(),
				'oauth_version' => '1.0'
			);
			
			#INITIALISE TWITTER OATH API URL
			$base_info = mp_options::oauth_base_string($twitter_api_url, 'GET', $oauth);
			$composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
			
			#INITIALISE TWITTER OATH SIGNATURE
			$oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
			$oauth['oauth_signature'] = $oauth_signature;
			
			#INITIALISE CURL SETTINGS
			$header = array(mp_options::oauth_authorization_header($oauth), 'Expect:');
			$options = array
			(
				CURLOPT_HTTPHEADER => $header,
				CURLOPT_HEADER => false,
				CURLOPT_URL => $twitter_api_url . '?screen_name=' . $twitter . '&count=5',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false
			);
			
			#INITIALISE CURL REQUEST
			$feed = curl_init();			
			curl_setopt_array($feed, $options);			
			$json = curl_exec($feed);			
			curl_close($feed);
			
			#INITIALISE TWITTER DATA
			$twitter_data = json_decode($json);
			
			#TWITTER TWEETS EXIST
			if(count($twitter_data) > 0)
			{
				#OPEN UNORDERED LIST
				echo '<ul class="twitter">';
				
				#DISPLAY TWITTER TWEETS
				for($tweet_counter = 0; $tweet_counter < count($twitter_data); $tweet_counter ++)
				{
					#INITIALISE TWITTER TWEET, DATE & URL
					$tweet = $twitter_data[$tweet_counter]->text;
					$tweet_date = date('j F Y g:i A', strtotime($twitter_data[$tweet_counter]->created_at));
					$tweet_url = $twitter_data[$tweet_counter]->entities->urls[0]->url;
										
					#FORMAT TWITTER TWEETS WITH LINKS
					$tweet = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\" rel=\"nofollow\">\\2</a>", $tweet);
					$tweet = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\" rel=\"nofollow\">\\2</a>", $tweet);
					$tweet = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\" rel=\"nofollow\">@\\1</a>", $tweet);
					$tweet = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\" rel=\"nofollow\">#\\1</a>", $tweet);
					
					#DISPLAY TWITTER TWEET
					echo '<li>' . $tweet . '<br>' . $tweet_date . ' &middot; <a href="' . $tweet_url . '" target="_blank" rel="nofollow" class="tweet">Twitter</a></li>' . "\n";
				}
				
				#CLOSE UNORDERED LIST
				echo '</ul>';
			}
		}
	}
	
	#THIS FUNCTION DISPLAYS THE DRIBBBLE THUMBNAILS
	public function mp_display_dribbble_thumbnails()
	{
		#INITIALISE DRIBBBLE THUMBNAILS
		$mp_footer_dribbble = get_option('mp_footer_dribbble');
		
		#DRIBBBLE THUMBNAILS ENABLED
		if(!empty($mp_footer_dribbble))
		{
			#DISPLAY DRIBBBLE HEADING
			echo '<h5>Dribbble</h5>';
		
			#INITIALISE DRIBBBLE RSS FEED
			$dribbble_rss = get_user_meta(mp_options::mp_get_author_id(), 'dribbble_rss', true);
			
			#DRIBBBLE RSS FEED EXISTS
			if(!empty($dribbble_rss))
			{
				#INCLUDE SIMPLEPIE RSS PARSER
				include_once(ABSPATH.WPINC . '/class-simplepie.php');
				
				#INITIALISE SIMPLEPIE OBJECT
				$feed = new SimplePie();
				 
				#INITIALISE SIMPLEPIE FEED
				$feed->set_feed_url($dribbble_rss);
				
				#INITIALISE SIMPLEPIE CACHE LOCATION
				$feed->set_cache_location(dirname(dirname(__FILE__)) . '/cache');
				 
				#RUN SIMPLEPIE FEED
				$feed->init();
				
				#OPEN UNORDERED LIST
				echo '<ul class="dribbble">';
				
				#DISPLAY DRIBBBLE THUMBNAILS
				foreach($feed->get_items(0, 4) as $item)
				{
					#FORMAT DRIBBBLE THUMBNAIL WITHOUT HYPERLINK & IMAGE TITLE IN ALT/TITLE ATTRIBUTE
					//<img alt="" height="" src="" width="">
					//<img alt="" height="" src="" width=""> --> NOTE THAT THE LATEST DRIBBBLE RSS DOES NOT COME WITH A CLOSING SLASH
					if(preg_match('#<img alt="(.+?)" height="(.+?)" src="(.+?)" width="(.+?)">#i', $item->get_description(), $dribbble_thumbnail_url))
					{
						$dribbble_thumbnail = '<img src="' . $dribbble_thumbnail_url[3] . '" alt="' . $item->get_title() . '" title="' . $item->get_title() . '">';
					}
					
					#DISPLAY DRIBBBLE THUMBNAIL
					echo '<li><a href="' . $item->get_permalink() . '" title="' . $item->get_title() . '" rel="nofollow" class="dribbble-iframe"><span class="magnify"></span>' . $dribbble_thumbnail . '</a></li>' . "\n";
				}
				
				#CLOSE UNORDERED LIST
				echo '</ul>';
			}
		}
	}
	
	#THIS FUNCTION DISPLAYS THE TWITTER TWEETS OR DRIBBBLE THUMBNAILS
	public function mp_display_dribbble_or_twitter()
	{
		#INITIALISE TWITTER TWEETS
		$mp_footer_twitter = get_option('mp_footer_twitter');
	
		#INITIALISE DRIBBBLE THUMBNAILS
		$mp_footer_dribbble = get_option('mp_footer_dribbble');
		
		#DISPLAY TWITTER TWEETS
		if(!empty($mp_footer_twitter) && empty($mp_footer_dribbble))
		{
			mp_options::mp_display_twitter_tweets();
		}
		#DISPLAY DRIBBBLE THUMBNAILS
		if(!empty($mp_footer_dribbble) && empty($mp_footer_twitter))
		{
			mp_options::mp_display_dribbble_thumbnails();
		}
	}
	
	#THIS FUNCTION DISPLAYS THE SOCIAL MEDIA BUTTONS IN THE SIDEBAR
	public function mp_display_social_buttons()
	{
		#INITIALISE AUTHOR ID
		$author_id = mp_options::mp_get_author_id();
		
		#INITIALISE SOCIAL MEDIA PROFILES
		$facebook = get_user_meta($author_id, 'facebook', true);
		$twitter = get_user_meta($author_id, 'twitter', true);
		$google_plus = get_user_meta($author_id, 'google_plus', true);
		$pinterest = get_user_meta($author_id, 'pinterest', true);
		$linkedin = get_user_meta($author_id, 'linkedin', true);
		$github = get_user_meta($author_id, 'github', true);
		$dribbble = get_user_meta($author_id, 'dribbble', true);
		$instagram = get_user_meta($author_id, 'instagram', true);
		
		#OPEN SOCIAL BOX & UNORDERED LIST
		echo '<div class="social-buttons"><ul>';
		
		#DISPLAY FACEBOOK BUTTON
		if(!empty($facebook))
		{
			echo '<li><a href="' . $facebook . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo('template_directory') . '/images/icon-facebook.png" alt="Facebook" title="Facebook"></a></li>';
		}
		
		#DISPLAY TWITTER BUTTON
		if(!empty($twitter))
		{
			echo '<li><a href="http://twitter.com/' . $twitter . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo('template_directory') . '/images/icon-twitter.png" alt="Twitter" title="Twitter"></a></li>';
		}
		
		#DISPLAY GOOGLE+ BUTTON
		if(!empty($google_plus))
		{
			echo '<li><a href="' . $google_plus . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo('template_directory') . '/images/icon-google.png" alt="Google+" title="Google+"></a></li>';
		}
		
		#DISPLAY PINTEREST BUTTON
		if(!empty($pinterest))
		{
			echo '<li><a href="http://pinterest.com/' . $pinterest . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo('template_directory') . '/images/icon-pinterest.png" alt="Pinterest" title="Pinterest"></a></li>';
		}
		
		#DISPLAY LINKEDIN BUTTON
		if(!empty($linkedin))
		{
			echo '<li><a href="' . $linkedin . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo('template_directory') . '/images/icon-linkedin.png" alt="LinkedIn" title="LinkedIn"></a></li>';
		}
		
		#DISPLAY GITHUB BUTTON
		if(!empty($github))
		{
			echo '<li><a href="http://github.com/' . $github . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo('template_directory') . '/images/icon-github.png" alt="GitHub" title="GitHub"></a></li>';
		}
		
		#DISPLAY DRIBBBLE BUTTON
		if(!empty($dribbble))
		{
			echo '<li><a href="http://dribbble.com/' . $dribbble . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo('template_directory') . '/images/icon-dribbble.png" alt="Dribbble" title="Dribbble"></a></li>';
		}
		
		#DISPLAY INSTAGRAM BUTTON
		if(!empty($instagram))
		{
			echo '<li><a href="' . $instagram . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo('template_directory') . '/images/icon-instagram.png" alt="Instagram" title="Instagram"></a></li>';
		}
		
		#CLOSE SOCIAL BOX & UNORDERED LIST
		echo '</ul></div>';
	}
	
	#THIS FUNCTION DISPLAYS THE FACEBOOK LIKE BOX IN THE SIDEBAR
	public function mp_display_facebook_like_box()
	{
		#INITIALISE FACEBOOK LIKE BOX CODE
		$facebook_code = get_option('mp_facebook_like_box');
		
		#DISPLAY FACEBOOK LIKE BOX
		if(!empty($facebook_code))
		{
			echo '<div class="facebook-like-box">' . $facebook_code . '</div>';
		}
	}
	
	#THIS FUNCTION DISPLAYS THE FACEBOOK LIKE URL IN THE HEADER
	public function mp_display_facebook_like_url()
	{
		#INITIALISE FACEBOOK LIKE URL
		$mp_facebook_like_url = get_option('mp_facebook_like_url');
		
		#DISPLAY FACEBOOK LIKE URL
		if(!empty($mp_facebook_like_url))
		{
			echo $mp_facebook_like_url;
		}
		#DISPLAY SITE URL
		else
		{
			bloginfo('url');
		}
	}
	
	#THIS FUNCTION DISPLAYS THE ADDTHIS PROFILE ID IN THE HEADER
	public function mp_display_addthis_profile_id()
	{
		#INITIALISE ADDTHIS PROFILE ID
		$mp_addthis_profile_id = get_option('mp_addthis_profile_id');
		
		#DISPLAY ADDTHIS PROFILE ID
		if(!empty($mp_addthis_profile_id))
		{
			echo $mp_addthis_profile_id;
		}
	}
	
	/**************************************************************************
	10. BLOG & COMMENT FUNCTIONS
	**************************************************************************/
	
	#THIS FUNCTION DISPLAYS THE BLOG POSTS
	public function mp_display_blog_posts($page, $cat = '', $search = '', $year = '', $month = '', $author = '')
	{		
		#RETRIEVE THE POST
		global $post;
		
		#INITIALISE BLOG POST ARGUMENTS
		$args = array
		(
			'posts_per_page' => get_option('posts_per_page'),
			'post_status' => 'publish',
			'paged' => $page,
			'order' => 'DESC',
			'orderby' => 'date'
		);
		
		#APPEND SEARCH TERMS & ALL POST TYPES TO BLOG POST ARGUMENTS
		if(is_search())
		{
			$args['s'] = $search;
			$args['post_type'] = 'any';	
		}
		#APPEND BLOG POST TYPE TO BLOG POST ARGUMENTS
		else
		{
			$args['post_type'] = 'post';
		}
		
		#APPEND CATEGORY TO BLOG POST ARGUMENTS
		if(!empty($cat))
		{
			$args['cat'] = $cat;
		}
		
		#APPEND YEAR TO BLOG POST ARGUMENTS
		if(!empty($year))
		{
			$args['year'] = $year;
		}
		
		#APPEND MONTH TO BLOG POST ARGUMENTS
		if(!empty($month))
		{
			$args['monthnum'] = $month;
		}
		
		#APPEND AUTHOR ID TO BLOG POST ARGUMENTS
		if(!empty($author))
		{
			$args['author'] = $author;
		}
		
		#RETRIEVE BLOG POSTS
		$blog_posts = new WP_Query($args);
		
		#DISPLAY SEARCH TITLE
		if(is_search())
		{
			echo '<h1 class="page-title">' . mp_options::mp_display_search_results_title($blog_posts->found_posts) . ' for &quot;' . $search . '&quot;</h1>';
		}
		
		#BLOG POSTS EXISTS
		if($blog_posts->have_posts())
		{			
			#DISPLAY BLOG POSTS
			while($blog_posts->have_posts())
			{
				$blog_posts->the_post();
				
				#INCLUDE BLOG POST TEMPLATE
				include(TEMPLATEPATH . '/includes/inc-blog-post.php');
			}

			#DISPLAY WP-PAGENAVI PAGING NAVIGATION LINKS
			if(function_exists('wp_pagenavi'))
			{
				wp_pagenavi(array('query' => $blog_posts));
			}
			#DISPLAY DEFAULT WORDPRESS PAGING NAVIGATION LINKS
			else
			{
			?>
				<p class="left"><?php next_posts_link('&laquo; Previous Entries'); ?></p>
				<p class="right"><?php previous_posts_link('Next Entries &raquo;'); ?></p>
			<?php
			}
		}
		#NO BLOG POSTS EXIST
		else
		{
		?>		
		<p>Sorry, no posts matched your criteria.</p>
		<?php
		}
	}
	
	#THIS FUNCTION DISPLAYS THE BLOG CATEGORIES IN THE SIDEBAR
	public function mp_display_blog_categories()
	{
		#INITIALISE CATEGORIES WITH NAME SORT ORDER
		$categories = wp_list_categories('orderby=name&order=ASC&show_count=1&hierarchical=0&title_li=&echo=0&hide_empty=1');	
		
		#MOVE THE ENDING ANCHOR TAG TO THE END OF THE LIST ITEM
		$categories = str_replace('</a>', '', $categories);
		$categories = str_replace('</li>', '</a></li>', $categories);
		
		#DISPLAY CATEGORIES
		echo $categories;
	}
	
	#THIS FUNCTION DISPLAYS THE RECENT BLOG POSTS ON THE HOME PAGE
	public function mp_display_recent_posts_home()
	{
		#RETRIEVE THE DATABASE
		global $wpdb;
		
		#RETREIVE POSTS
		$posts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC LIMIT 5");
		
		#POSTS EXIST
		if(!empty($posts))
		{
			#OPEN UNORDERED LIST
			echo '<ul>';
			
			#DISPLAY POSTS
			foreach($posts as $post)
			{
				echo '<li><a href="' . get_permalink($post->ID) . '" title="' . $post->post_title . '">' . $post->post_title . '</a><br><span class="info">' . get_the_time(get_option('date_format') . " " . get_option('time_format'), $post->ID) . '</span></li>';
			}
			
			#CLOSE UNORDERED LIST
			echo '</ul>' . "\n";
		}
	}
	
	#THIS FUNCTION DISPLAYS THE RECENT BLOG POSTS
	public function mp_display_recent_posts()
	{
		#RETRIEVE THE DATABASE
		global $wpdb;
		
		#INITIALISE NUMBER OF POSTS
		$number_of_posts = get_option('mp_posts_recent_number');
		
		#INITIALISE DEFAULT NUMBER OF POSTS
		if(empty($number_of_posts))
		{
			$number_of_posts = 5;
		}

		#RETREIVE POSTS
		$posts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC LIMIT $number_of_posts");
		
		#OPEN UNORDERED LIST
		echo '<ul id="recent-posts" class="sidebar-list posts">';
		
		#POSTS EXIST
		if(!empty($posts))
		{			
			#DISPLAY POSTS
			foreach($posts as $post)
			{
				#POST THUMBNAIL EXISTS
				if(has_post_thumbnail($post->ID))
				{
					#INITIALISE POST THUMBNAIL URL
					$thumbnail_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
				
					#DISPLAY POST THUMBNAIL & POST TITLE
					echo '<li><a href="' . get_permalink($post->ID) . '" title="' . $post->post_title . '"><img src="' . $thumbnail_image_url[0] . '" width="30" height="30" title="' . $post->post_title . '" alt="' . $post->post_title . '">' . $post->post_title . '</a></li>';
				}
				#POST THUMBNAIL DOES NOT EXIST
				else
				{
					#DISPLAY DEFAULT POST THUMBNAIL & POST TITLE
					echo '<li><a href="' . get_permalink($post->ID) . '" title="' . $post->post_title . '"><img src="' . get_bloginfo('template_directory') . '/images/post-thumbnail-default.png" width="30" height="30" title="' . $post->post_title . '" alt="' . $post->post_title . '">' . $post->post_title . '</a></li>';
				}
			}
		}
		
		#CLOSE UNORDERED LIST
		echo '</ul>' . "\n";
	}
	
	#THIS FUNCTION DISPLAYS THE POPULAR BLOG POSTS
	public function mp_display_popular_posts()
	{
		#POPULAR POSTS PLUGIN HAS BEEN ACTIVATED
		if(function_exists('popular_posts'))
		{
			#RETRIEVE THE DATABASE
			global $wpdb;
			
			#INITIALISE NUMBER OF POSTS
			$number_of_posts = get_option('mp_posts_popular_number');
			
			#INITIALISE DEFAULT NUMBER OF POSTS
			if(empty($number_of_posts))
			{
				$number_of_posts = 5;
			}
			
			#RETREIVE POSTS
			$posts = $wpdb->get_results("SELECT ID, post_title, meta_value + 0 AS viewcount FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON post_id = ID WHERE meta_key = 'pvc_views' AND post_status = 'publish' AND post_type ='post' AND post_password = '' ORDER BY viewcount DESC LIMIT $number_of_posts");
			
			#OPEN UNORDERED LIST
			echo '<ul id="popular-posts" class="sidebar-list posts hide">';
			
			#POSTS EXIST
			if(!empty($posts))
			{
				#DISPLAY POSTS
				foreach($posts as $post)
				{
					#POST THUMBNAIL EXISTS
					if(has_post_thumbnail($post->ID))
					{
						#INITIALISE POST THUMBNAIL URL
						$thumbnail_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
					
						#DISPLAY POST THUMBNAIL & POST TITLE
						echo '<li><a href="' . get_permalink($post->ID) . '" title="' . $post->post_title . '"><img src="' . $thumbnail_image_url[0] . '" width="30" height="30" title="' . $post->post_title . '" alt="' . $post->post_title . '">' . $post->post_title . '</a></li>';
					}
					#POST THUMBNAIL DOES NOT EXIST
					else
					{
						#DISPLAY DEFAULT POST THUMBNAIL & POST TITLE
						echo '<li><a href="' . get_permalink($post->ID) . '" title="' . $post->post_title . '"><img src="' . get_bloginfo('template_directory') . '/images/post-thumbnail-default.png" width="30" height="30" title="' . $post->post_title . '" alt="' . $post->post_title . '">' . $post->post_title . '</a></li>';
					}
				}
			}
			
			#CLOSE UNORDERED LIST
			echo '</ul>' . "\n";
		}
		#POPULAR POSTS PLUGIN HAS NOT BEEN ACTIVATED
		else
		{
			return;
		}	
	}
	
	#THIS FUNCTION DISPLAYS THE MOST COMMENTED BLOG POSTS
	public function mp_display_most_commented_posts()
	{
		#RETRIEVE THE DATABASE
		global $wpdb;
		
		#INITIALISE NUMBER OF POSTS
		$number_of_posts = get_option('mp_posts_comments_number');
		
		#INITIALISE DEFAULT NUMBER OF POSTS
		if(empty($number_of_posts))
		{
			$number_of_posts = 5;
		}

		#RETREIVE POSTS
		$posts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE comment_count > 0 AND post_status = 'publish' AND post_type = 'post' ORDER BY comment_count DESC LIMIT $number_of_posts");
		
		#OPEN UNORDERED LIST
		echo '<ul id="most-comments" class="sidebar-list posts hide">';
		
		#POSTS EXIST
		if(!empty($posts))
		{			
			#DISPLAY POSTS
			foreach($posts as $post)
			{
				#POST THUMBNAIL EXISTS
				if(has_post_thumbnail($post->ID))
				{
					#INITIALISE POST THUMBNAIL URL
					$thumbnail_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
				
					#DISPLAY POST THUMBNAIL & POST TITLE
					echo '<li><a href="' . get_permalink($post->ID) . '" title="' . $post->post_title . '"><img src="' . $thumbnail_image_url[0] . '" width="30" height="30" title="' . $post->post_title . '" alt="' . $post->post_title . '">' . $post->post_title . ' (' . mp_options::mp_get_comment_type_count($post->ID, "comment") . ')</a></li>';
				}
				#POST THUMBNAIL DOES NOT EXIST
				else
				{
					#DISPLAY DEFAULT POST THUMBNAIL & POST TITLE
					echo '<li><a href="' . get_permalink($post->ID) . '" title="' . $post->post_title . '"><img src="' . get_bloginfo('template_directory') . '/images/post-thumbnail-default.png" width="30" height="30" title="' . $post->post_title . '" alt="' . $post->post_title . '">' . $post->post_title . ' (' . mp_options::mp_get_comment_type_count($post->ID, "comment") . ')</a></li>';
				}
			}
		}
		
		#CLOSE UNORDERED LIST
		echo '</ul>' . "\n";
	}
	
	#THIS FUNCTION DISPLAYS THE RECENT COMMENTS
	public function mp_display_recent_comments()
	{
		#RETRIEVE THE DATABASE
		global $wpdb;
		
		#INITIALISE NUMBER OF COMMENTS
		$number_of_comments = get_option('mp_comments_recent_number');
		
		#INITIALISE DEFAULT NUMBER OF COMMENTS
		if(empty($number_of_comments))
		{
			$number_of_comments = 5;
		}
		
		#INITIALISE SQL QUERY
		$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved, comment_type,comment_author_url, SUBSTRING(comment_content, 1, 65) AS com_excerpt
		FROM $wpdb->comments
		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
		WHERE comment_approved = '1'
		AND comment_type = ''
		ORDER BY comment_date_gmt DESC
		LIMIT $number_of_comments";
 
		#RETREIVE COMMENTS
		$comments = $wpdb->get_results($sql);
		
		#OPEN UNORDERED LIST
		echo '<ul id="recent-comments" class="sidebar-list comments">';
		
		#COMMENTS EXIST
		if(!empty($comments))
		{
			#DISPLAY COMMENTS
			foreach($comments as $comment)
			{
				echo '<li><a href="' . get_permalink($comment->ID) . '#comment-' . $comment->comment_ID . '" title="' . $comment->post_title . '">' . strip_tags($comment->comment_author) . ': ' . strip_tags($comment->com_excerpt) . '… ' . get_the_time("j F Y", $comment->comment_ID) . '</a></li>';

			}		
		}
		
		#CLOSE UNORDERED LIST
		echo '</ul>' . "\n";	
	}
	
	#THIS FUNCTION DISPLAYS THE TOP COMMENTERS
	public function mp_display_top_commenters()
	{
		#RETRIEVE THE DATABASE
		global $wpdb;
		
		#INITIALISE NUMBER OF COMMENTERS
		$number_of_commenters = get_option('mp_comments_commenters_number');
		
		#INITIALISE DEFAULT NUMBER OF COMMENTERS
		if(empty($number_of_commenters))
		{
			$number_of_commenters = 5;
		}
		
		#INITIALISE SQL QUERY
		$sql = "SELECT user_id, comment_author, comment_author_url, comment_author_email, comment_post_ID, COUNT(comment_ID) AS total_comments
		FROM $wpdb->comments
		WHERE comment_approved = '1'
		AND comment_type = ''
		AND comment_author != ''
		GROUP BY comment_author_email
		ORDER BY total_comments DESC
		LIMIT $number_of_commenters";
	
		#RETREIVE COMMENTERS
		$commenters = $wpdb->get_results($sql);
		
		#COMMENTERS EXIST
		if(!empty($commenters))
		{					
			#OPEN ORDERED LIST
			$html = '<ol id="top-commenters" class="sidebar-list numbered hide">';
			
			#DISPLAY COMMENTERS
			foreach($commenters as $commenter)
			{
				#INITIALISE LIST ITEM
				$html .= '<li>';
				
				#COMMENTER IS A REGISTERED USER
				if($commenter->user_id > 0)
				{
					#RETRIEVE COMMENTER DISPLAY NAME & URL
					$commenter_data = $wpdb->get_row("SELECT user_url FROM $wpdb->users WHERE ID = '$commenter->user_id'");
					
					#COMMENTER URL IN USER DATA DOES NOT EXIST
					if(empty($commenter_data->user_url))
					{
						#COMMENTER URL DOES NOT EXIST
						if(empty($commenter->comment_author_url))
						{
							$html .= '<a href="#AA">' . $commenter->comment_author;
						}
						#COMMENTER URL EXISTS
						else
						{
							$html .= '<a title="Visit ' . $commenter->comment_author . '\'s site" href="' . $commenter->comment_author_url . '" rel="nofollow">' . $commenter->comment_author;
						}
					}
					#COMMENTER URL IN USER DATA EXISTS
					else
					{
						$html .= '<a title="Visit ' . $commenter->comment_author . '\'s site" href="' . $commenter_data->user_url . '" rel="nofollow">' . $commenter->comment_author;
					}
				}				
				#COMMENTER IS NOT A REGISTERED USER
				elseif($commenter->user_id == 0)
				{
					#COMMENTER URL DOES NOT EXIST
					if(empty($commenter->comment_author_url))
					{
						$html .= '<a href="#BB">' . $commenter->comment_author;
					}
					#COMMENTER URL EXISTS
					else
					{
						$html .= '<a title="Visit ' . $commenter->comment_author . '\'s site" href="' . $commenter->comment_author_url . '" rel="nofollow">' . $commenter->comment_author;
					}
				}
				
				#APPEND COMMENTER'S NUMBER OF COMMENTS	
				$html .= ' (' . $commenter->total_comments . ')';
				
				#CLOSE LIST ITEM
				$html .= '</a></li>';
			}			
			
			#CLOSE ORDERED LIST
			$html .= '</ol>' . "\n";
			
			#DISPLAY ORDERED LIST
			echo $html;
		}
		#COMMENTERS DO NOT EXIST
		else
		{
			#OPEN & CLOSE ORDERED LIST
			echo '<ol id="top-commenters" class="sidebar-list hide"></ol>' . "\n";
		}
	}
	
	#THIS FUNCTION RETURNS THE COMMENT TYPE COUNT
	#ACCEPTS: 'comment', 'trackback', 'pingback' for $comment_type
	public function mp_get_comment_type_count($post_id, $comment_type)
	{
		#RETRIEVE THE DATABASE
		global $wpdb;
		
		#INITIALISE SQL QUERY
		switch($comment_type)
		{
			#COMMENTS
			case 'comment':
				$sql = "SELECT COUNT(comment_id) FROM $wpdb->comments WHERE comment_type = '$comment_type' OR comment_type = '' and comment_approved = 1 and comment_post_id = $post_id";
				break;
			
			#PINGBACKS & TRACKBACKS
			case 'pingback':
			case 'trackback':
				$sql = "SELECT COUNT(comment_id) FROM $wpdb->comments WHERE comment_type = '$comment_type' and comment_approved = 1 and comment_post_id = $post_id";
				break;			
		}
		
		#INITIALISE COMMENT TYPE COUNT
		$comment_type_count = $wpdb->get_var($sql);
	
		#RETURN COMMENT TYPE COUNT
		return $comment_type_count;
	}
	
	#THIS FUNCTION DISPLAYS THE COMMENT TYPE COUNT LABELS
	#REPLACES THE comments_number() FUNCTION
	#mp_display_comment_counter(139, 'comment', '0 Comments', '1 Comments', 'Comments')
	public function mp_display_comment_counter($post_id, $comment_type, $label_zero, $label_single, $label_multiple)
	{
		#INITIALISE COMMENT TYPE COUNT
		$comment_type_count = mp_options::mp_get_comment_type_count($post_id, $comment_type);
		
		#DISPLAY ZERO COMMENTS
		if($comment_type_count == 0)
		{
			echo $label_zero;
			
			return;
		}
		#DISPLAY 1 COMMENT
		elseif($comment_type_count == 1)
		{
			echo $label_single;
			
			return;
		}
		#DISPLAY MULTIPLE COMMENTS
		elseif($comment_type_count > 1)
		{
			echo $comment_type_count . ' ' .  $label_multiple;
			
			return;
		}
	}
	
	#THIS FUNCTION DISPLAYS THE COMMENTS
	public function mp_display_comment_list($comment, $args, $depth)
	{
		#RETRIEVE THE COMMENT
   		$GLOBALS['comment'] = $comment;
		
		#DISPLAY APPROVED COMMENT
      	if($comment->comment_approved)
		{
			#INITIALISE GRAVATAR DEFAULT AVATAR & AVATAR HASH			
			$gravatar_default = urlencode(get_bloginfo('template_directory') . '/images/icon-avatar.png');
			$gravatar_hash = md5(strtolower(trim(get_comment_author_email())));
			?>			
			<!-- COMMENT <?php comment_ID(); ?> - START -->
			<li id="comment<?php comment_ID(); ?>" <?php comment_class(); ?>>
				
				<!-- COMMENT AVATAR, AUTHOR & DATE - START -->
				<a name="comment-<?php comment_ID(); ?>"></a>
				<div class="comment-header">
					<div class="comment-avatar"><a href="<?php comment_author_url(); ?>" rel="nofollow"><img src="http://www.gravatar.com/avatar/<?php echo $gravatar_hash; ?>?s=80&r=g&d=<?php echo $gravatar_default; ?>" alt="<?php comment_author(); ?>" title="<?php comment_author(); ?>"></a></div>
					<div class="comment-author-date">
						<p class="comment-author"><a href="<?php comment_author_url(); ?>" rel="nofollow"><?php comment_author(); ?></a></p>
						<p class="comment-date"><?php comment_date(); ?><br> <?php comment_time() ?></p>
					</div>
				</div>
				<!-- COMMENT AVATAR, AUTHOR & DATE - END -->				
			
				<!-- COMMENT TEXT - START -->
				<div class="comment-text">
					<?php
					#DISPLAY COMMENT TEXT
					comment_text();
					
					#DISPLAY COMMENT REPLY LINK
					if($args['max_depth'] != $depth)
					{
						echo '<p class="reply">' . get_comment_reply_link(array_merge($args, array("depth" => $depth, "max_depth" => $args["max_depth"]))) . "</p>";
					}
					?>
				</div>
				<!-- COMMENT TEXT - END -->
				
			
			<!-- COMMENT <?php comment_ID(); ?> - END -->
			
		<?php
		}
	}
	
	#THIS FUNCTION DISPLAYS THE TRACKBACKS & PINGBACKS
	public function mp_display_ping_list($comment)
	{
		#RETRIEVE THE COMMENT
   		$GLOBALS["comment"] = $comment;
		
		#DISPLAY APPROVED COMMENT
      	if($comment->comment_approved)
		{
			echo "<li>" . get_comment_author_link() . " - " . get_comment_date() . " " . get_comment_time();
		}
	}

	#THIS FUNCTION DISPLAYS THE SEARCH RESULTS TITLE
	public function mp_display_search_results_title($search_results)
	{		
		#NO SEARCH RESULTS
		if($search_results == 0)
		{
			return 'No Search Results';
		}
		#1 SEARCH RESULT
		elseif($search_results == 1)
		{
			return '1 Search Result';
		}
		#MORE THAN 1 SEARCH RESULT
		elseif($search_results > 1)
		{
			return $search_results . ' Search Results';
		}
	}

	#THIS FUNCTION RETURNS THE POST EXCERPT WITH A MAXIMUM NUMBER OF WORDS
	public function mp_get_excerpt($max_words, $strip_tags = false, $strip_line_breaks = false)
	{
		#INITIALISE CONTENT
		$content = get_the_content();
		
		#CONTENT EXISTS
		if(!empty($content))
		{
			#DISPLAY SHORTCODE IN CONTENT
			$content = do_shortcode($content);
			$content = apply_filters('the_content', $content);
			
			#INITIALISE CONTENT WORD COUNT
			$content_word_count = str_word_count($content);
			
			#CONTENT CONTAINS LESS WORDS THAN MAXIMUM NUMBER OF WORDS
			if($content_word_count < $max_words)
			{
				return $content;
			}
			#CONTENT CONTAINS MORE WORDS THAN MAXIMUM NUMBER OF WORDS
			else
			{
				#REMOVE TAGS FROM CONTENT
				if($strip_tags)
				{
					$content = trim(strip_tags($content));
				}
				
				#REMOVE LINE BREAKS FROM CONTENT
				if($strip_line_breaks)
				{
					$content = ereg_replace("\n", ' ', $content);
					$content = ereg_replace("\r", ' ', $content);
				}
				
				#TRUNCATE CONTENT INTO THE MAXIMUM NUMBER OF WORDS
				preg_match("/(\S+\s*){0,$max_words}/", $content, $excerpt);
				
				#INITIALISE TRUNCATED CONTENT
				$content_excerpt = trim($excerpt[0]) . '... <a href="' . get_permalink() . '" title="' . get_the_title() . '">Read the rest</a>';
				
				#RETURN TRUNCATED CONTENT
				return wpautop($content_excerpt);
			}
		}
	}

	#THIS FUNCTION RETURNS THE POST EXCERPT READ THE REST LINK
	public function mp_excerpt_more($more)
	{
		#RETRIEVE THE POST
		global $post;
		
		#RETURN READ THE REST LINK
		return '... <a href="'. get_permalink($post->ID) . '"> Read the rest</a>';
	}

	#THIS FUNCTION RETURNS THE PAGINATION PAGE
	public function mp_get_page()
	{
		#INITIALISE PAGE VIA PAGED VARIABLE
		if(get_query_var('paged'))
		{
			$page = get_query_var('paged');
		}
		#INITIALISE PAGE VIA PAGE VARIABLE
		elseif(get_query_var('page'))
		{
			$page = get_query_var('page');
		}
		#INITIALISE DEFAULT PAGE
		else
		{
			$page = 1;
		}
		
		#RETURN PAGE
		return $page;	
	}
	
	/**************************************************************************
	11. RSS FUNCTIONS
	**************************************************************************/

	#THIS FUNCTION DISPLAYS THE RSS FEEDS IN THE HEADER
	public function mp_display_rss_feeds_header()
	{
		#INITIALISE SITE NAME
		$mp_site_name = get_bloginfo('name');
		
		#INITIALISE FEEDBURNER RSS FEED
		$mp_feedburner_rss = get_option('mp_feedburner_rss');
		
		#INITIALISE WORDPRESS RSS FEED
		$mp_wordpress_rss = get_bloginfo('rss2_url');
		
		#DISPLAY FEEDBURNER RSS FEED
		if(!empty($mp_feedburner_rss))
		{
			echo '<link rel="alternate" type="application/rss+xml" title="' . $mp_site_name . ' RSS Feed" href="' . $mp_feedburner_rss . '">' . "\n";
		}
		#DISPLAY WORDPRESS RSS FEED
		else
		{
			echo '<link rel="alternate" type="application/rss+xml" title="' . $mp_site_name . ' RSS Feed" href="' . $mp_wordpress_rss . '">' . "\n";
		}
		
		#DISPLAY ARTICLES RSS FEED
		if(get_option('mp_rss_articles'))
		{
			echo '<link rel="alternate" type="application/rss+xml" title="' . $mp_site_name . ' Articles RSS Feed" href="' . $mp_wordpress_rss . '?post_type=article">' . "\n";
		}
		
		#DISPLAY SLIDES RSS FEED
		if(get_option('mp_rss_slides'))
		{
			echo '<link rel="alternate" type="application/rss+xml" title="' . $mp_site_name . ' Slides RSS Feed" href="' . $mp_wordpress_rss . '?post_type=slide">' . "\n";
		}
		
		#DISPLAY PROJECTS RSS FEED
		if(get_option('mp_rss_projects'))
		{
			echo '<link rel="alternate" type="application/rss+xml" title="' . $mp_site_name . ' Projects RSS Feed" href="' . $mp_wordpress_rss . '?post_type=project">' . "\n";
		}
		
		#DISPLAY TESTIMONIALS RSS FEED
		if(get_option('mp_rss_testimonials'))
		{
			echo '<link rel="alternate" type="application/rss+xml" title="' . $mp_site_name . ' Testimonials RSS Feed" href="' . $mp_wordpress_rss . '?post_type=testimonial">' . "\n";
		}
		
		#DISPLAY COMMENTS RSS FEED
		if(get_option('mp_rss_comments'))
		{
			echo '<link rel="alternate" type="application/rss+xml" title="' . $mp_site_name . ' Comments RSS Feed" href="' . get_bloginfo('comments_rss2_url') . '">' . "\n";
		}
	}
	
	#THIS FUNCTION DISPLAYS THE RSS FEED IN THE SIDEBAR
	public function mp_display_rss_feed_sidebar()
	{		
		#INITIALISE FEEDBURNER RSS FEED
		$mp_feedburner_rss = get_option('mp_feedburner_rss');

		#INITIALISE FEEDBURNER EMAIL SUBSCRIPTION ADDRESS
		$mp_feedburner_email = get_option('mp_feedburner_email');
		
		#INITIALISE WORDPRESS RSS FEED
		$mp_wordpress_rss = get_bloginfo('rss2_url');
		
		#INIITALISE RSS LINK WITH FEEDBURNER RSS FEED
		if(!empty($mp_feedburner_rss))
		{
			$mp_rss = $mp_feedburner_rss;	
		}
		#INIITALISE RSS LINK WITH WORDPRESS RSS FEED
		else
		{
			$mp_rss = $mp_wordpress_rss;
		}
		
		#INITIALISE EMAIL LINK WITH FEEDBURNER EMAIL SUBSCRIPTION ADDRESS
		if(!empty($mp_feedburner_email))
		{
			$mp_email = '<a href="' . $mp_feedburner_email . '" rel="nofollow">Email</a>';
		}
		#INITIALISE EMAIL LINK WITH NO URL
		else
		{
			$mp_email = "Email";
		}
		
		#DISPLAY RSS FEED SUBSCRIPTION TEXT
		echo '<p><a href="' . $mp_rss . '" rel="nofollow"><img src="' . get_bloginfo('template_directory') . '/images/icon-rss-small.png" alt="RSS" class="rss"></a>Subscribe to my blog via ' . $mp_email . ' or <a href="' . $mp_rss . '" rel="nofollow">RSS</a></p>';
	}

	#THIS FUNCTION DISPLAYS THE RSS FEEDS WITH A SHORTCODE [RSS]
	public function mp_rss_shortcode($parameters, $content = null)
	{
		#INITIALISE SITE NAME
		$mp_site_name = get_bloginfo('name');
		
		#INITIALISE FEEDBURNER RSS FEED
		$mp_feedburner_rss = get_option('mp_feedburner_rss');
		
		#INITIALISE WORDPRESS RSS FEED
		$mp_wordpress_rss = get_bloginfo('rss2_url');
		
		#INITILIASE EXTERNAL RSS FEEDS
		$mp_rss_external = get_option('mp_rss_external');
		
		#DISPLAY RSS FEEDS HEADER
		echo '<h2>' . $mp_site_name . ' RSS Feeds</h2>' . "\n";
		
		#OPEN UNORDERED LIST
		echo "<ul>";
		
		#DISPLAY FEEDBURNER RSS FEED
		if(!empty($mp_feedburner_rss))
		{
			echo '<li><a href="' . $mp_feedburner_rss . '" rel=nofollow">' . $mp_site_name . ' RSS Feed</li></a>' . "\n";
		}
		#DISPLAY WORDPRESS RSS FEED
		else
		{
			echo '<li><a href="' . $mp_wordpress_rss . '" rel=nofollow">' . $mp_site_name . ' RSS Feed</li></a>' . "\n";
		}
		
		#DISPLAY ARTICLES RSS FEED
		if(get_option('mp_rss_articles'))
		{
			echo '<li><a href="' . $mp_wordpress_rss . '?post_type=article" rel=nofollow">' . $mp_site_name . ' Articles RSS Feed</li></a>' . "\n";
		}
		
		#DISPLAY SLIDES RSS FEED
		if(get_option('mp_rss_slides'))
		{
			echo '<li><a href="' . $mp_wordpress_rss . '?post_type=slide" rel=nofollow">' . $mp_site_name . ' Slides RSS Feed</li></a>' . "\n";
		}
		
		#DISPLAY PROJECTS RSS FEED
		if(get_option('mp_rss_projects'))
		{
			echo '<li><a href="' . $mp_wordpress_rss . '?post_type=project" rel=nofollow">' . $mp_site_name . ' Projects RSS Feed</li></a>' . "\n";
		}
		
		#DISPLAY TESTIMONIALS RSS FEED
		if(get_option('mp_rss_testimonials'))
		{
			echo '<li><a href="' . $mp_wordpress_rss . '?post_type=testimonial" rel=nofollow">' . $mp_site_name . ' Testimonials RSS Feed</li></a>' . "\n";
		}
		
		#DISPLAY COMMENTS RSS FEED
		if(get_option('mp_rss_comments'))
		{
			echo '<li><a href="' . get_bloginfo('comments_rss2_url') . '" rel=nofollow">' . $mp_site_name . ' Comments RSS Feed</li></a>' . "\n";
		}
		
		#CLOSE UNORDERED LIST
		echo '</ul>';
		
		#DISPLAY EXTERNAL RSS FEEDS
		if(!empty($mp_rss_external))
		{			
			#SPLIT EXTERNAL RSS FEEDS CONTENT INTO LINES
			$mp_rss_external_lines = preg_split("/\r?\n|\r/", $mp_rss_external);
			
			#DISPLAY RSS FEEDS HEADER
			echo '<h2>External RSS Feeds</h2>' . "\n";
			
			#OPEN UNORDERED LIST
			echo '<ul>';
			
			#DISPLAY EXTERNAL RSS FEEDS
			foreach($mp_rss_external_lines as $mp_rss_external_line)
			{
				#SPLIT EXTERNAL RSS FEED LINE INTO NAME & ADDRESS
				$mp_rss_external = split(",", $mp_rss_external_line);
				
				#DISPLAY EXTERNAL RSS FEED NAME & ADDRES
				echo '<li><a href="' . trim($mp_rss_external[1]) . '" rel=nofollow">' . trim($mp_rss_external[0]) . '</li></a>' . "\n";
			}
			
			#CLOSE UNORDERED LIST
			echo '</ul>';
		}
	}
	
	/**************************************************************************
	12. AD FUNCTIONS
	**************************************************************************/
	
	#THIS FUNCTION DISPLAYS THE SIDEBAR ADS
	public function mp_display_ads_sidebar()
	{
		#INITIALISE SIDEBAR ADS
		$mp_sidebar_ads = get_option('mp_sidebar_ads');
		
		#SIDEBAR ADS ENABLED
		if(!empty($mp_sidebar_ads))
		{
			#INITIALISE SIDEBAR SQUARE BUTTONS
			$mp_sidebar_ads_square1 = get_option('mp_sidebar_ads_square1');
			$mp_sidebar_ads_square2 = get_option('mp_sidebar_ads_square2');
			$mp_sidebar_ads_square3 = get_option('mp_sidebar_ads_square3');
			$mp_sidebar_ads_square4 = get_option('mp_sidebar_ads_square4');
			$mp_sidebar_ads_square5 = get_option('mp_sidebar_ads_square5');
			$mp_sidebar_ads_square6 = get_option('mp_sidebar_ads_square6');
			
			?>
			<!-- SPONSORS - START -->
			<div class="sidebar-box">
			
				<h4>Sponsors</h4>
                
                <div class="sidebar-sponsor-wrapper">
                
                    <div class="sidebar-square-button"><?php echo trim(do_shortcode($mp_sidebar_ads_square1)); ?></div>
                    <div class="sidebar-square-button"><?php echo trim(do_shortcode($mp_sidebar_ads_square2)); ?></div>
                    <div class="sidebar-square-button"><?php echo trim(do_shortcode($mp_sidebar_ads_square3)); ?></div>
                    <div class="sidebar-square-button"><?php echo trim(do_shortcode($mp_sidebar_ads_square4)); ?></div>
                    <div class="sidebar-square-button"><?php echo trim(do_shortcode($mp_sidebar_ads_square5)); ?></div>
                    <div class="sidebar-square-button"><?php echo trim(do_shortcode($mp_sidebar_ads_square6)); ?></div>
                    
                </div>
                    
        	</div>
			<!-- SPONSORS - END -->
			<?php
		}
	}
}
?>