<?php
#THIS CLASS INITIALISES THE THEME OPTIONS
class mp_options
{
	#THIS CONSTRUCTOR FUNCTION INITIALISES THE THEME OPTIONS
	function __construct()
	{	
		#REMOVE UNNECESSARY META DATA FROM WORDPRESS HEAD
		remove_action("wp_head", "wp_generator");
		remove_action("wp_head", "start_post_rel_link");
		remove_action("wp_head", "index_rel_link");
		remove_action("wp_head", "adjacent_posts_rel_link");
		remove_action("wp_head", "adjacent_posts_rel_link_wp_head");
		remove_action("wp_head", "parent_post_rel_link");
		remove_action("wp_head", "parent_post_rel_link_wp_head");
		
		#ENABLE POST THUMBNAILS
		add_theme_support("post-thumbnails");
		
		#INITIALISE MENUS
		register_nav_menu("menu_top", "Top Menu");
		register_nav_menu("menu_footer", "Footer Menu");
		
		#ENABLE SIDEBAR WIDGETS
		register_sidebar(array("before_widget" => '<div class="sidebar_box">',"after_widget" => "</div>", "before_title" => "<h4>", "after_title" => "</h4>",));
		
		#INITIALISE JQUERY LIBRARY
		add_action("init", array("mp_options", "mp_jquery"));
		
		#INITIALISE THEME OPTIONS
		add_action("admin_menu", array("mp_options", "mp_admin_menu"));
		add_action("admin_init", array("mp_options", "mp_theme_settings"));
		
		#INITIALISE THEME ADMIN JAVASCRIPT & CSS
		add_action("admin_head", array("mp_options", "mp_admin_head"));
		
		#INITIALISE SLIDE CUSTOM POST TYPE
		add_action("init", array("mp_options", "mp_custom_posts_slides"));
		add_filter("manage_edit-slide_columns", array("mp_options", "mp_slide_edit_columns"));
		add_action("manage_slide_posts_custom_column",  array("mp_options", "mp_slide_custom_columns"));
		
		#INITIALISE SLIDE META BOX
		add_action("admin_init", array("mp_options", "mp_meta_boxes_slide"));
		
		#INITIALISE PROJECT CUSTOM POST TYPE & TAXONOMIES
		add_action("init", array("mp_options", "mp_custom_posts_project"));
		add_action("init", array("mp_options", "mp_custom_taxonomies_project_categories"));
		add_action("init", array("mp_options", "mp_custom_taxonomies_project_scope"));
		add_action("init", array("mp_options", "mp_custom_taxonomies_project_skills"));
		add_filter("manage_edit-project_columns", array("mp_options", "mp_project_edit_columns"));
		add_action("manage_project_posts_custom_column",  array("mp_options", "mp_project_custom_columns"));
		
		#INITIALISE PROJECT META BOX
		add_action("admin_init", array("mp_options", "mp_meta_boxes_project"));
		
		#INITIALISE TESTIMONIAL CUSTOM POST TYPE
		add_action("init", array("mp_options", "mp_custom_posts_testimonials"));
		add_filter("manage_edit-testimonial_columns", array("mp_options", "mp_testimonial_edit_columns"));
		add_action("manage_testimonial_posts_custom_column",  array("mp_options", "mp_testimonial_custom_columns"));
		
		#INITIALISE TESTIMONIAL META BOX
		add_action("admin_init", array("mp_options", "mp_meta_boxes_testimonial"));
		
		#INITIALISE TINYMCE EDITOR FOR USER BIOGRAPHY IN WORDPRESS 3.3 +
		if(function_exists("wp_editor") && current_user_can("edit_posts"))
		{
			#REPLACE BIOGRAPHY FIELD WITH TINYMCE EDITOR
			add_action("show_user_profile", array("mp_options", "mp_tinymce_biography"));
			add_action("edit_user_profile", array("mp_options", "mp_tinymce_biography"));
			
			#REMOVE TEXTAREA FILTERS FROM BIOGRAPHY FIELD
			remove_all_filters("pre_user_description");
			
			#ADD CONTENT FILTERS TO THE BIOGRAPHY FIELD
			add_filter("get_the_author_description", "wptexturize");
			add_filter("get_the_author_description", "convert_chars");
			add_filter("get_the_author_description", "wpautop");
		}
		
		#INITIALISE USER CONTACT INFO FIELDS
		add_filter("user_contactmethods", array("mp_options", "mp_contact_info"));
		
		#INITIALISE SHORTCODES
		add_shortcode("testimonial", array("mp_options", "mp_testimonial_shortcode"));
		
		#INITIALISE TRACKING CODE IN FOOTER
		add_action("wp_footer", array("mp_options", "mp_tracking"));
	}
	
	#THIS FUNCTION ADDS THE THEME OPTIONS MENU ITEM TO THE APPEARANCE MENU
	function mp_admin_menu()
	{
		add_theme_page("Options", "Options", "administrator", "mp_options", array("mp_options", "mp_options_page"));
	}
	
	#THIS FUNCTION REGISTERS THE THEME OPTION SETTINGS
	function mp_theme_settings()
	{
		register_setting("mp_settings_author", "mp_author");
		register_setting("mp_settings_sidebar", "mp_facebook_like_box");
		register_setting("mp_settings_sidebar", "mp_posts_recent_number");
		register_setting("mp_settings_sidebar", "mp_posts_comments_number");
		register_setting("mp_settings_sidebar", "mp_comments_recent_number");
		register_setting("mp_settings_sidebar", "mp_comments_commenters_number");
		register_setting("mp_settings_tracking", "mp_tracking");
	}
	
	#THIS FUNCTION RESETS THE THEME OPTION SETTINGS
	function mp_reset_options($option)
	{
		#RESET OPTIONS
		switch($option)
		{
			#AUTHOR
			case "author":
			
				update_option("mp_author", 1);
				
				break;
				
			#SIDEBAR
			case "sidebar":
			
				update_option("mp_facebook_like_box", "");
				update_option("mp_posts_recent_number", 5);
				update_option("mp_posts_comments_number", 5);
				update_option("mp_comments_recent_number", 5);
				update_option("mp_comments_commenters_number", 5);
				
				break;
				
			#TRACKING
			case "tracking":
			
				update_option("mp_tracking", "");
				
				break;
		}
	}
	
	#THIS FUNCTION DISPLAYS THE THEME'S OPTIONS PAGE
	function mp_options_page()
	{
		#INITIALISE SUB PAGE
		$sub_page = $_REQUEST["sub_page"];
		
		#SET DEFAULT SUB PAGE TO AUTHOR
		if(empty($sub_page))
		{
			$sub_page = "author";
		}
		
		?>
		<div id="mp-options" class="wrap">
			
			<div class="icon32" id="icon-tools"><br /></div>
			
			<h2>Options</h2>
			
			<ul style="display: block">
				<li style="display: inline"><?php if($sub_page == "author" || empty($sub_page)) { echo "<strong>Author</strong>"; } else { ?><a href="/wp-admin/themes.php?page=mp_options&sub_page=author">Author</a><?php } ?></li>
				<li style="display: inline"><?php if($sub_page == "sidebar") { echo "<strong>Sidebar</strong>"; } else { ?><a href="/wp-admin/themes.php?page=mp_options&sub_page=sidebar">Sidebar</a><?php } ?></li>
				<li style="display: inline"><?php if($sub_page == "tracking") { echo "<strong>Tracking</strong>"; } else { ?><a href="/wp-admin/themes.php?page=mp_options&sub_page=tracking">Tracking</a><?php } ?></li>
				<li style="display: inline"><?php if($sub_page == "reset") { echo "<strong>Reset</strong>"; } else { ?><a href="/wp-admin/themes.php?page=mp_options&sub_page=reset">Reset</a><?php } ?></li>
				<li style="display: inline"><a href="http://www.employvince.com/contact/" target="_blank">Support</a></li>			
			</ul>
			
		</div>
		<?php
		#DISPLAY SUB PAGES
		switch($sub_page)
		{
			#AUTHOR
			case "author":
				
				#DISPLAY UPDATE MESSAGE
				if(isset($_GET["settings-updated"]) && ($_GET["settings-updated"] == true))
				{
				?>
				<div class="updated fade"><p><strong><?php _e("Your Author options have been saved."); ?></strong></p></div>
				<?php
				}
				?>
				
				<form method="post" action="options.php">
				<?php
				settings_fields("mp_settings_author");
				
				#DISPLAY AUTHORS
				mp_options::mp_option_field("Author", "", true, true, "Author", "author", "mp_author", "mp_author", "Select the author you wish to display in the sidebar and footer", "", true);
				?>
			
				</form>
			
				<?php
				break;
				
			#SIDEBAR
			case "sidebar":
				
				#DISPLAY UPDATE MESSAGE
				if(isset($_GET["settings-updated"]) && ($_GET["settings-updated"] == true))
				{
				?>
				<div class="updated fade"><p><strong><?php _e("Your Sidebar options have been saved."); ?></strong></p></div>
				<?php
				}
				?>
				
				<form method="post" action="options.php">
				<?php
				settings_fields("mp_settings_sidebar");
				
				#INITIALISE FACEBOOK LIKE BOX DESCRIPTION
				$facebook_description = '<p>Generate a <a href="https://developers.facebook.com/docs/reference/plugins/like-box/" target="_blank">Facebook Like Box</a> Iframe social plugin code from Facebook. For best results, please enter 260 for the Width, select the Dark colour scheme, uncheck "Show header" and enter #333333 for the Border Color.</p>';
				
				#DISPLAY FACEBOOK LIKE BOX
				mp_options::mp_option_field("Facebook Like Box", $facebook_description, true, true, "Facebook Like Box Code", "textarea", "mp_facebook_like_box", "mp_facebook_like_box", "Enter the Facebook Like Box Iframe social plugin code of your Facebook Page", "", true);
				
				#DISPLAY RECENT POSTS SELECT LIST
				mp_options::mp_option_field("Posts", "", true, false, "Recent Posts", "sidebar_lists", "mp_posts_recent_number", "mp_posts_recent_number", "Select the number of posts to display in the Recent Posts list", 5, false, 20);
				
				#DISPLAY MOST COMMENTS SELECT LIST
				mp_options::mp_option_field("", "", false, true, "Most Comments", "sidebar_lists", "mp_posts_comments_number", "mp_posts_comments_number", "Select the number of posts to display in the Most Comments list", 5, true, 20);
				
				#DISPLAY RECENT COMMENTS SELECT LIST
				mp_options::mp_option_field("Comments", "", true, false, "Recent Comments", "sidebar_lists", "mp_comments_recent_number", "mp_comments_recent_number", "Select the number of comments to display in the Recent Comments list", 5, false, 20);
				
				#DISPLAY TOP COMMENTERS SELECT LIST
				mp_options::mp_option_field("", "", false, true, "Top Commenters", "sidebar_lists", "mp_comments_commenters_number", "mp_comments_commenters_number", "Select the number of commenters to display in the Top Commenters list", 5, true, 20);
				?>
			
				</form>
			
				<?php
				break;
		
			#TRACKING
			case "tracking":
				
				#DISPLAY UPDATE MESSAGE
				if(isset($_GET["settings-updated"]) && ($_GET["settings-updated"] == true))
				{
				?>
				<div class="updated fade"><p><strong><?php _e("Your Tracking options have been saved."); ?></strong></p></div>
				<?php
				}
				?>
				
				<form method="post" action="options.php">
				<?php
				settings_fields("mp_settings_tracking");
				
				#INITIALISE TRACKING DESCRIPTION
				$tracking_description = '<p>To use <a href="http://www.google.com/analytics/" target="_blank">Google Analytics</a>, your web site must be registered with your <a href="http://www.google.com/analytics/" target="_blank">Google Analytics</a> account.</p>';
				
				#DISPLAY TRACKING
				mp_options::mp_option_field("Google Analytics Or Other Tracking Services", $tracking_description, true, true, "Tracking Code", "textarea", "mp_tracking", "mp_tracking", "Enter the tracking code of your tracking service. The tracking code will appear just before the &lt;/body&gt; tag of your web site", "", true);
				?>
			
				</form>
			
				<?php
				break;
				
			#RESET
				case "reset":
					
					#AUTHOR RESET SECURITY CHECK PASSED
					if(!empty($_POST["author_reset"]) && check_admin_referer("author_reset_check"))
					{
						#RESET AUTHOR OPTIONS
						mp_options::mp_reset_options("author");
						
						#DISPLAY RESET MESSAGE
						?>
						<div class="updated fade"><p><strong><?php _e("Your Author options have been reset."); ?></strong></p></div>
						<?php
					}
					#SIDEBAR RESET SECURITY CHECK PASSED
					if(!empty($_POST["sidebar_reset"]) && check_admin_referer("sidebar_reset_check"))
					{
						#RESET SIDEBAR OPTIONS
						mp_options::mp_reset_options("sidebar");
						
						#DISPLAY RESET MESSAGE
						?>
						<div class="updated fade"><p><strong><?php _e("Your Sidebar options have been reset."); ?></strong></p></div>
						<?php
					}
					#TRACKING RESET SECURITY CHECK PASSED
					if(!empty($_POST["tracking_reset"]) && check_admin_referer("tracking_reset_check"))
					{
						#RESET TRACKING OPTIONS
						mp_options::mp_reset_options("tracking");
						
						#DISPLAY RESET MESSAGE
						?>
						<div class="updated fade"><p><strong><?php _e("Your Tracking options have been reset."); ?></strong></p></div>
						<?php
					}
					?>
					
					<h3 class="title">Author</h3>
					
					<form name="author_reset_form" method="post">
					<?php wp_nonce_field("author_reset_check"); ?>
					
					<input type="submit" name="author_reset" class="button-primary" value="<?php _e("Reset Options") ?>" onclick="javascript:check = confirm('<?php _e('Reset all Author options to default settings?', 'author_reset'); ?>'); if(check == false) { return false; }" />
					
					</form>
					
					<h3 class="title">Sidebar</h3>
					
					<form name="sidebar_reset_form" method="post">
					<?php wp_nonce_field("sidebar_reset_check"); ?>
					
					<input type="submit" name="sidebar_reset" class="button-primary" value="<?php _e("Reset Options") ?>" onclick="javascript:check = confirm('<?php _e('Reset all Sidebar options to default settings?', 'sidebar_reset'); ?>'); if(check == false) { return false; }" />
					
					</form>
					
					<h3 class="title">Tracking</h3>
					
					<form name="tracking_reset_form" method="post">
					<?php wp_nonce_field("tracking_reset_check"); ?>
					
					<input type="submit" name="tracking_reset" class="button-primary" value="<?php _e("Reset Options") ?>" onclick="javascript:check = confirm('<?php _e('Reset all Tracking options to default settings?', 'tracking_reset'); ?>'); if(check == false) { return false; }" />
					
					</form>					
					
					<?php	
					break;
		}
	}
	
	#THIS FUNCTION DISPLAYS THE THEME'S OPTIONS PAGE FIELDS
	function mp_option_field($h3_title = "", $below_h3_title = "", $open_table = false, $close_table = false, $column_name, $input_type, $input_id, $input_option, $input_description, $input_default, $save_button = false, $min_width = "", $max_width = "")
	{
		#INITIALISE OPTION
		$mp_option = get_option("$input_option");
		
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
			#TEXT BOX
			case "text":
			
				mp_options::mp_display_text($input_id, $mp_option);
				break;
				
			#TEXTAREA:
			case "textarea":
				
				mp_options::mp_display_textarea($input_id, $mp_option);
				break;
				
			#AUTHOR SELECT LIST:
			case "author":
				
				mp_options::mp_display_author_list($input_id, $mp_option, $input_default);
				break;
			
			#SIDEBAR LIST ITEMS
			case "sidebar_lists":
			
				mp_options::mp_display_sidebar_list($input_id, $mp_option, $input_default, $min_width);
				break;
		}
		
		#CLOSE 2ND COLUMN WITH INPUT DESCRIPTION & DEFAULT VALUES OF MINIMUM & MAXIMUM WIDTH
		if($input_type == "width" && !empty($min_width) && !empty($max_width))
		{
			echo '<br /><span class="description">' . $input_description . '. Default: ' . $input_default . ', Minimum: ' . $min_width . 'px, Maximum: ' . $max_width . 'px.</span></td>' . "\n";
		}
		#CLOSE 2ND COLUMN WITH INPUT DESCRIPTION & DEFAULT VALUE
		else
		{
			#DEFAULT VALUE EXISTS
			if(!empty($input_default))
			{
				echo '<br /><span class="description">' . $input_description . '. Default: ' . $input_default . '.</span></td>' . "\n";
			}
			#DEFAULT VALUE DOES NOT EXIST
			else
			{
				echo '<br /><span class="description">' . $input_description . '.</span></td>' . "\n";
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
			echo '<p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>' . "\n";
		}
	}
	
	#THIS FUNCTION DISPLAYS A TEXT FIELD
	function mp_display_text($text_id, $entered_text)
	{
		#INITIALISE TEXT FIELD HTML
		$text_box = "<input name=\"$text_id\" id=\"$text_id\" type=\"text\" value=\"$entered_text\" class=\"regular-text\" />";
		
		#DISPLAY TEXT FIELD
		echo $text_box;
	}
	
	#THIS FUNCTION DISPLAYS A TEXTAREA
	function mp_display_textarea($text_id, $entered_text)
	{
		#INITIALISE TEXTAREA
		$textarea = "<textarea name=\"$text_id\" id=\"$text_id\" rows=\"10\" cols=\"50\" class=\"large-text code\">$entered_text</textarea>";
		
		#DISPLAY TEXTAREA
		echo $textarea;
	}
	
	#THIS FUNCTION DISPLAYS THE LIST OF AUTHORS
	function mp_display_author_list($select_id, $selected_author, $default_author = 1)
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
		$select_list = "<select name=\"$select_id\" id=\"$select_id\" class=\"postform\">\n";
		
		#APPEND AUTHORS
		foreach($authors as $author)
		{
			#SELECTED AUTHOR
			if($selected_author == $author->ID)
			{
				$select_list .= "<option class=\"level-0\" selected=\"selected\" value=\"" . $author->ID . "\">" . $author->display_name . "</option>\n";
			}
			#UNSELECTED AUTHOR
			else
			{
				$select_list .= "<option class=\"level-0\" value=\"" . $author->ID . "\">" . $author->display_name . "</option>\n";
			}
		}
		
		#CLOSE SELECT LIST HTML
		$select_list .= "</select>";
		
		#DISPLAY SELECT LIST
		echo $select_list;
	}
	
	#THIS FUNCTION DISPLAYS THE LIST OF PROJECTS
	function mp_display_project_list($select_id, $selected_project)
	{
		#RETRIEVE THE POST
		global $post;
	
		#INITIALISE PROJECT ARGUMENTS
		$args = array
		(
			"post_type" => "project",
			"post_status" => "publish",
			"posts_per_page" => -1,
			"orderby" => "title",
			"order" => "ASC"
		);
		
		#INITIALISE PROJECTS
		$projects = new WP_Query($args);
		
		#PROJECTS EXISTS
		if($projects->have_posts())
		{			
			#INITIALISE SELECT LIST HTML
			$select_list = "<select name=\"$select_id\" id=\"$select_id\" class=\"postform\">\n";
			$select_list .= "<option class=\"level-0\" value=\"\">Select Project</option>\n";
			
			#DISPLAY PROJECTS
			while($projects->have_posts())
			{
				#RETRIEVE THE PROJECT CONTENT
				$projects->the_post();  
				
				#SELECTED PROJECT
				if($selected_project == $post->ID)
				{
					$select_list .= "<option class=\"level-0\" selected=\"selected\" value=\"" . $post->ID . "\">" . $post->post_title . "</option>\n";
				}
				#UNSELECTED PROJECT
				else
				{
					$select_list .= "<option class=\"level-0\" value=\"" . $post->ID . "\">" . $post->post_title . "</option>\n";
				}
			}
			
			#CLOSE SELECT LIST HTML
			$select_list .= "</select>";
			
			#DISPLAY SELECT LIST
			echo $select_list;
		}
	}
	
	#THIS FUNCTION DISPLAYS THE LIST OF GALLERIES
	function mp_display_gallery_list($select_id, $selected_gallery)
	{
		#NEXTGEN GALLERY PLUGIN IS ACTIVATED
		if(function_exists("nggShowSlideshow"))
		{
			#RETRIEVE THE DATABASE
			global $wpdb;
			
			#RETREIVE ALL NEXTGEN GALLERIES
			$galleries = $wpdb->get_results("SELECT gid, title FROM $wpdb->prefix" . "ngg_gallery ORDER BY gid ASC");
		
			#INITIALISE SELECT LIST HTML
			$select_list = "<select name=\"$select_id\" id=\"$select_id\" class=\"postform\">\n";
			$select_list .= "<option class=\"level-0\" value=\"\">Select Project Gallery</option>\n";
			
			#DISPLAY OTHER NEXTGEN GALLERY SELECT LIST OPTIONS
			foreach($galleries as $gallery)
			{
				#DISPLAY SELECTED NEXTGEN GALLERY
				if($selected_gallery == $gallery->gid)
				{
					$select_list .= "<option class=\"level-0\" selected=\"selected\" value=\"" . $gallery->gid . "\">" . $gallery->title . "</option>\n";
				}
				#DISPLAY UNSELECTED NEXTGEN GALLERY
				else
				{
					$select_list .= "<option class=\"level-0\" value=\"" . $gallery->gid . "\">" . $gallery->title . "</option>\n";
				}
			}
			
			#CLOSE SELECT LIST HTML
			$select_list .= "</select>";
			
			#DISPLAY SELECT LIST
			echo $select_list;
		}
	}
	
	#THIS FUNCTION DISPLAYS THE NUMBER OF LIST ITEMS OF SIDEBAR LISTS
	function mp_display_sidebar_list($select_id, $selected_number_of_items, $default_number_of_items = 5, $number_of_items)
	{
		#SELECT DEFAULT NUMBER OF ITEMS IF NO NUMBER OF ITEMS WAS SELECTED
		if(empty($selected_number_of_items) && !empty($default_number_of_items))
		{
			$selected_number_of_items = $default_number_of_items;
		}
	
		#INITIALISE SELECT LIST HTML
		$select_list = "<select name=\"$select_id\" id=\"$select_id\" class=\"postform\">\n";
		
		#APPEND LIST ITEMS
		for($item_counter = 1; $item_counter <= $number_of_items; $item_counter ++)
		{
			#SELECTED LIST ITEM
			if($selected_number_of_items == $item_counter)
			{
	
				$select_list .= "<option class=\"level-0\" selected=\"selected\" value=\"$item_counter\">" . $item_counter . "</option>\n";
			}
			#UNSELECTED LIST ITEM
			else
			{
				$select_list .= "<option class=\"level-0\" value=\"$item_counter\">" . $item_counter . "</option>\n";
			}
		}
		
		#CLOSE SELECT LIST HTML
		$select_list .= "</select>";
		
		#DISPLAY SELECT LIST
		echo $select_list;
	}
	
	#THIS FUNCTION DISPLAYS THE TRACKING CODE JUST BEFORE THE </BODY> TAG
	function mp_tracking()
	{
		#INITIALISE TRACKING SETTINGS
		$mp_tracking = get_option("mp_tracking");
		
		#TRACKING SETTINGS EXIST
		if(!empty($mp_tracking))
		{
			echo $mp_tracking . "\n\n";
		}
	}
	
	#THIS FUNCTION INCLUDES THE JQUERY LIBRARY INTO NON-ADMIN WORDPRESS PAGES
	function mp_jquery()
	{
		#PAGE IS NON-ADMIN
		if(!is_admin())
		{
			#DEREGISTER DEFAULT JQUERY INCLUDES
			wp_deregister_script("jquery");	
	
			#LOAD THE GOOGLE API JQUERY INCLUDES
			wp_register_script("jquery", "http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js", false, "1.7.2", false);
	
			#REGISTER CUSTOM JQUERY INCLUDES
			wp_enqueue_script("jquery");
		}
	}
	
	#THIS FUNCTION INCLUDES THE JAVASCRIPT & CSS FILES OF THE THEME OPTIONS
	function mp_admin_head()
	{
		echo '<link rel="stylesheet" media="all" href="' . get_bloginfo("template_url") . '/css/admin.php" type="text/css" />' . "\n";
		echo '<link rel="stylesheet" media="all" href="' . get_bloginfo("template_url") . '/css/colorbox.php" type="text/css" />' . "\n";
		echo '<script type="text/javascript" src="' . get_bloginfo("template_url") . '/js/jquery-colorbox-min.js"></script>' . "\n";
		echo '<script type="text/javascript" src="' . get_bloginfo("template_url") . '/js/jquery-colorbox-admin-initialise.js"></script>' . "\n";
		echo '<script type="text/javascript" src="' . get_bloginfo("template_url") . '/js/jquery-metadata.js"></script>' . "\n";
		echo '<script type="text/javascript" src="' . get_bloginfo("template_url") . '/js/jquery-validate.js"></script>' . "\n";
		echo '<script type="text/javascript" src="' . get_bloginfo("template_url") . '/js/jquery-validate-additional-methods.js"></script>' . "\n";
		
		#LOAD JAVASCRIPT FOR TINYMCE EDITOR FOR USER BIOGRAPHY IN WORDPRESS 3.3 +
		if(function_exists("wp_editor"))
		{
			echo '<script type="text/javascript" src="' . get_bloginfo("template_url") . '/js/jquery-tinymce-biography.js"></script>' . "\n";
		}
	}
	
	#THIS FUNCTION DISPLAYS THE SLIDES
	function mp_display_slides()
	{
		#RETRIEVE THE POST
		global $post;
	
		#INITIALISE SLIDE ARGUMENTS
		$args = array
		(
			"post_type" => "slide",
			"post_status" => "publish",
			"posts_per_page" => 5,
			"order" => "DESC",
			"orderby" => "date"
		);
		
		#INITIALISE SLIDES
		$slides = new WP_Query($args);
		
		#SLIDES EXISTS
		if($slides->have_posts())
		{
			#OPEN SLIDE LIST
			echo '<ul id="slider">';
			
			#DISPLAY SLIDES
			while($slides->have_posts())
			{
				#RETRIEVE THE SLIDE CONTENT
				$slides->the_post();
			
				#INITIALISE THE SLIDE DETAILS
				$slide_image = get_post_meta($post->ID, "slide_image", true);
				$slide_url = get_post_meta($post->ID, "slide_url", true);
				$slide_title = get_the_title($post->ID);
				
				#OPEN SLIDE LIST ITEM
				echo '<li data-animate="flash, bounce">';
				
				#DISPLAY IMAGE WITH LINK
				echo '<a href="' . $slide_url . '"><img src="' . $slide_image . '" alt="' . $slide_title . '" title="' . $slide_title . '" /></a>';
								
				#CLOSE SLIDE LIST ITEM
				echo '</li>';
			}
			
			#CLOSE SLIDE LIST
			echo '</ul>';
		}
	}
	
	#THIS FUNCTION CREATES THE SLIDE CUSTOM POST TYPE
	function mp_custom_posts_slides()
	{
		#INITIALISE SLIDE CUSTOM POST TYPE LABELS
		$labels = array
		(
			"name" => _x("Slides", "post type general name"),
			"singular_name" => _x("Slide", "post type singular name"),
			"add_new" => _x("Add New", "slide"),
			"add_new_item" => __("Add New Slide"),
			"edit_item" => __("Edit Slide"),
			"new_item" => __("New Slide"),
			"all_items" => __("All Slides"),
			"view_item" => __("View Slide"),
			"search_items" => __("Search Slides"),
			"not_found" =>  __("No Slides found"),
			"not_found_in_trash" => __("No Slides found in Trash"), 
			"parent_item_colon" => "",
			"menu_name" => "Slides"
		);
		
		#INITIALISE SLIDE CUSTOM POST TYPE ARGUMENTS
		$args = array
		(
			"labels" => $labels,
			"description" => "Slide",
			"public" => true,
			"publicly_queryable" => true,
			"exclude_from_search" => false,
			"show_ui" => true, 
			"show_in_menu" => true,
			"menu_position" => 20,
			"menu_icon" => null,
			"capability_type" => "post",
			"hierarchical" => false,
			"supports" => array("title", "editor", "revisions"),
			"has_archive" => false,
			"rewrite" => array("slug" => "slide", "with_front" => false),
			"query_var" => true,
			"can_export" => true,
			"show_in_nav_menus" => true
		);
		
		#REGISTER SLIDE CUSTOM POST TYPE
		register_post_type("slide", $args);
	}
	
	#THIS FUNCTION DISPLAYS THE SLIDE COLUMNS
	function mp_slide_edit_columns($columns)
	{
		#INITIALISE SLIDE COLUMNS
		$columns = 
		array
		(
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Title",
			"image" => "Image",
			"url" => "URL",
			"date" => "Date",
			"author" => "Author"
		);
		
		#RETURN SLIDE COLUMNS
		return $columns;
	}
	
	#THIS FUNCTION DISPLAYS THE SLIDE COLUMN VALUES
	function mp_slide_custom_columns($column)
	{
		#RETRIEVE THE POST
		global $post;
		
		#DISPLAY SLIDE COLUMN VALUES
		switch($column)
		{			
			#SLIDE IMAGE
			case "image":
			
				#INITIALISE SLIDE IMAGE
				$slide_image = get_post_meta($post->ID, "slide_image", true);
				
				#DISPLAY SLIDE IMAGE ICON
				if(!empty($slide_image))
				{
					echo '<a href="' . $slide_image . '" title="" class="colorbox"><img src="' . get_bloginfo("template_url") . '/images/icon-picture.png" /></a>';
				}
				
				break;
				
			#SLIDE URL
			case "url":
				
				#INITIALISE SLIDE URL
				$slide_url = get_post_meta($post->ID, "slide_url", true);
				
				#DISPLAY SLIDE URL ICON
				if(!empty($slide_url))
				{
					echo '<a href="' . $slide_url . '" target="_blank"><img src="' . get_bloginfo("template_url") . '/images/icon-url.png" /></a>';
				}
				
				break;
		}
	}
	
	#THIS FUNCTION CREATES THE SLIDE BOX
	function mp_meta_boxes_slide()
	{
		#ADD SLIDE BOX TO SLIDE CUSTOM POSTS
		add_meta_box("slide_box", "Slide Information", array("mp_options", "mp_meta_boxes_slide_form"), "slide", "normal", "high");
	 
		#SAVE SLIDE BOX FORM CONTENTS
		add_action("save_post", array("mp_options", "mp_meta_boxes_slide_form_save"));
	}
	
	#THIS FUNCTION CREATES THE SLIDE BOX FORM
	function mp_meta_boxes_slide_form()
	{
		#RETRIEVE THE POST
		global $post;
	
		#INITIALISE SLIDE ERROR BOX ID
		$slide_error_box = "slide_errors" . $post->ID;
	
		#INITIALISE SLIDE OPTIONS
		$slide_image = get_post_meta($post->ID, "slide_image", true);
		$slide_url = get_post_meta($post->ID, "slide_url", true);
		
		#DISPLAY SLIDE NONCE FIELD
		echo '<input name="slide_nonce" id="slide_nonce" type="hidden" value="' . wp_create_nonce(__FILE__) . '" />';
				
		#DISPLAY SLIDE IMAGE FIELD
		echo '<p><strong>Slide Image:</strong><br /><input name="slide_image" id="slide_image" type="text" size="80" value="' . urldecode($slide_image) . '" /></p><p>Enter the URL of the slide image.</p>';
		
		#DISPLAY SLIDE URL FIELD
		echo '<p><strong>Slide URL:</strong><br /><input name="slide_url" id="slide_url" type="text" size="80" value="' . urldecode($slide_url) . '" /></p><p>Enter the URL of the slide.</p>';
		?>
		<script type="text/javascript">
		jQuery(document).ready(function()
		{
			jQuery("div.wrap").after('<div id="<?php echo $slide_error_box; ?>" class="mp_errors error"></div>');
			
			jQuery("form#post").validate(
			{
				//VALIDATION CONTAINER & ERROR MESSAGES
				errorLabelContainer: jQuery("#<?php echo $slide_error_box; ?>"),
				errorElement: "p",
				errorClass: "mp_error_field",
				
				//VALIDATION RULES
				rules:
				{
					slide_image:
					{
						required: true,
						url2: true
					},
					slide_url:
					{
						required: true,
						url2: true
					}
				},
				//VALIDATION MESSAGES
				messages:
				{
					slide_image:
					{
						required: "Please enter a Slide Image.",
						url2: "Please enter a valid Slide Image."
					},
					slide_url:
					{
						required: "Please enter a Slide URL.",
						url2: "Please enter a valid Slide URL."
					}
				}
			});
			
			jQuery("#publish").click(function()
			{
				form_check = jQuery("#post").valid();
				
				if(!form_check)
				{
					return false;
				}
			});
		});
		</script>
		<?php
	}
	
	#THIS FUNCTION SAVES THE SLIDE BOX FORM CONTENTS
	function mp_meta_boxes_slide_form_save($post_id) 
	{
		#SAVE SLIDE BOX FORM CONTENTS
		mp_options::mp_meta_boxes_save($post_id, "slide_nonce", "slide_image", "post");
		mp_options::mp_meta_boxes_save($post_id, "slide_nonce", "slide_url", "post");
		
		#RETURN POST ID
		return $post_id;
	}
	
	#THIS FUNCTION CREATES THE PROJECT CUSTOM POST TYPE
	function mp_custom_posts_project()
	{
		#INITIALISE PROJECT CUSTOM POST TYPE LABELS
		$labels = array
		(
			"name" => _x("Projects", "post type general name"),
			"singular_name" => _x("Project", "post type singular name"),
			"add_new" => _x("Add New", "project"),
			"add_new_item" => __("Add New Project"),
			"edit_item" => __("Edit Project"),
			"new_item" => __("New Project"),
			"all_items" => __("All Projects"),
			"view_item" => __("View Projects"),
			"search_items" => __("Search Projects"),
			"not_found" =>  __("No Projects found"),
			"not_found_in_trash" => __("No Projects found in Trash"), 
			"parent_item_colon" => "",
			"menu_name" => "Portfolio"
		);
		
		#INITIALISE PROJECT CUSTOM POST TYPE ARGUMENTS
		$args = array
		(
			"labels" => $labels,
			"description" => "Project",
			"public" => true,
			"publicly_queryable" => true,
			"exclude_from_search" => false,
			"show_ui" => true, 
			"show_in_menu" => true,
			"menu_position" => 20,
			"menu_icon" => null,
			"capability_type" => "post",
			"hierarchical" => false,
			"supports" => array("title", "editor", "revisions", "thumbnail"),
			"has_archive" => false,
			"rewrite" => array("slug" => "portfolio/project", "with_front" => false),
			"query_var" => true,
			"can_export" => true,
			"show_in_nav_menus" => true
		);
		
		#REGISTER PROJECT CUSTOM POST TYPE
		register_post_type("project", $args);
	}
	
	#THIS FUNCTION CREATES THE PROJECT CATEGORIES CUSTOM TAXONOMY
	function mp_custom_taxonomies_project_categories()
	{
		#INITIALISE PROJECT CATEGORIES CUSTOM TAXONOMY LABELS
		$labels = array
		(
			"name" => _x("Project Categories", "taxonomy general name"),
			"singular_name" => _x("Project Category", "taxonomy singular name"),
			"search_items" =>  __("Search Project Categories"),
			"all_items" => __("All Project Categories"),
			"parent_item" => __("Parent Project Category"),
			"parent_item_colon" => __("Parent Project Category:"),
			"edit_item" => __("Edit Project Category"), 
			"update_item" => __("Update Project Category"),
			"add_new_item" => __("Add New Project Category"),
			"new_item_name" => __("New Project Category"),
			"menu_name" => __("Project Categories"),
			"choose_from_most_used" => __("Choose from the most used Project Categories")
		);
		
		#INITIALISE PROJECT CATEGORIES CUSTOM TAXONOMY ARGUMENTS
		$args = array
		(
			"labels" => $labels,	
			"public" => true,
			"show_in_nav_menus" => true,
			"show_ui" => true,
			"show_tagcloud" => false,
			"hierarchical" => true,
			"rewrite" => array("slug" => "portfolio-categories", "with_front" => false),
			"query_var" => true
		);
		
		#REGISTER PROJECT CATEGORIES CUSTOM TAXONOMY
		register_taxonomy("portfolio-categories", "project", $args);
	}
	
	#THIS FUNCTION CREATES THE PROJECT SCOPE CUSTOM TAXONOMY
	function mp_custom_taxonomies_project_scope()
	{
		#INITIALISE PROJECT SCOPE CUSTOM TAXONOMY LABELS
		$labels = array
		(
			"name" => _x("Project Scope", "taxonomy general name"),
			"singular_name" => _x("Project Scope", "taxonomy singular name"),
			"search_items" =>  __("Search Project Scope"),
			"all_items" => __("All Project Scope"),
			"parent_item" => __("Parent Project Scope"),
			"parent_item_colon" => __("Parent Project Scope:"),
			"edit_item" => __("Edit Project Scope"), 
			"update_item" => __("Update Project Scope"),
			"add_new_item" => __("Add New Project Scope"),
			"new_item_name" => __("New Project Scope"),
			"menu_name" => __("Project Scope"),
			"choose_from_most_used" => __("Choose from the most used Project Scope")
		);
		
		#INITIALISE PROJECT SCOPE CUSTOM TAXONOMY ARGUMENTS
		$args = array
		(
			"labels" => $labels,	
			"public" => true,
			"show_in_nav_menus" => true,
			"show_ui" => true,
			"show_tagcloud" => false,
			"hierarchical" => true,
			"rewrite" => array("slug" => "portfolio-scope", "with_front" => false),
			"query_var" => true
		);
		
		#REGISTER PROJECT SCOPE CUSTOM TAXONOMY
		register_taxonomy("portfolio-scope", "project", $args);
	}
	
	#THIS FUNCTION CREATES THE PROJECT SKILLS CUSTOM TAXONOMY
	function mp_custom_taxonomies_project_skills()
	{
		#INITIALISE PROJECT SKILLS CUSTOM TAXONOMY LABELS
		$labels = array
		(
			"name" => _x("Project Skills", "taxonomy general name"),
			"singular_name" => _x("Project Skill", "taxonomy singular name"),
			"search_items" =>  __("Search Project Skills"),
			"all_items" => __("All Project Skills"),
			"parent_item" => __("Parent Project Skill"),
			"parent_item_colon" => __("Parent Project Skill:"),
			"edit_item" => __("Edit Project Skill"), 
			"update_item" => __("Update Project Skill"),
			"add_new_item" => __("Add New Project Skill"),
			"new_item_name" => __("New Project Skill"),
			"menu_name" => __("Project Skills"),
			"choose_from_most_used" => __("Choose from the most used Project Skills")
		);
		
		#INITIALISE PROJECT SKILLS CUSTOM TAXONOMY ARGUMENTS
		$args = array
		(
			"labels" => $labels,	
			"public" => true,
			"show_in_nav_menus" => true,
			"show_ui" => true,
			"show_tagcloud" => false,
			"hierarchical" => true,
			"rewrite" => array("slug" => "portfolio-skill", "with_front" => false),
			"query_var" => true
		);
		
		#REGISTER PROJECT SKILLS CUSTOM TAXONOMY
		register_taxonomy("portfolio-skill", "project", $args);
	}
	
	#THIS FUNCTION DISPLAYS THE PROJECT COLUMNS
	function mp_project_edit_columns($columns)
	{
		#INITIALISE PROJECT COLUMNS
		$columns = 
		array
		(
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Title",
			"project_category" => "Category",
			"project_scope" => "Scope",
			"project_skills" => "Skills",
			"project_thumbnail" => "Thumbnail",
			"project_gallery" => "Gallery",			
			"project_url" => "URL",
			"client_name" => "Client Name",
			"client_location" => "Client Location",			
			"date" => "Date"
		);
		
		#RETURN TESTIMONIAL COLUMNS
		return $columns;
	}
	
	#THIS FUNCTION DISPLAYS THE PROJECT COLUMN VALUES
	function mp_project_custom_columns($column)
	{
		#RETRIEVE THE POST
		global $post;
		
		#DISPLAY PROJECT VALUES
		switch($column)
		{
			#PROJECT CATEGORY
			case "project_category":
			
				echo get_the_term_list($post->ID, "portfolio-categories", "", ", ");
				break;
				
			#PROJECT SCOPE
			case "project_scope":
			
				echo get_the_term_list($post->ID, "portfolio-scope", "", ", ");
				break;
				
			#PROJECT SKILLS
			case "project_skills":
			
				echo get_the_term_list($post->ID, "portfolio-skill", "", ", ");
				break;
			
			#PROJECT THUMBNAIL
			case "project_thumbnail":
				
				#PRODUCT THUMBNAIL EXISTS
				if(has_post_thumbnail($post->ID))
				{
					#INITIALISE PROJECT THUMBNAIL ARRAY
					$project_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "thumbnail");
					
					#DISPLAY PROJECT THUMBNAIL
					echo '<img src="' . $project_thumbnail[0] . '" width="123" height="78" alt="" />';
				}
				
				break;
				
			#PROJECT GALLERY
			case "project_gallery":
				
				#INITIALISE PROJECT GALLERY
				$portfolio_project_gallery = get_post_meta($post->ID, "portfolio_project_gallery", true);
				
				#DISPLAY PROJECT GALLERY ICON
				if(!empty($portfolio_project_gallery))
				{
					echo '<a href="admin.php?page=nggallery-manage-gallery&mode=edit&gid=' . $portfolio_project_gallery . '" target="_blank"><img src="' . get_bloginfo("template_url") . '/images/icon-gallery.png" /></a>';
				}
				
				break;
			
			#PROJECT URL
			case "project_url":
			
				#INITIALISE PROJECT URL
				$portfolio_project_url = get_post_meta($post->ID, "portfolio_project_url", true);
				
				#DISPLAY PROJECT URL ICON
				if(!empty($portfolio_project_url))
				{
					echo '<a href="' . $portfolio_project_url . '" target="_blank"><img src="' . get_bloginfo("template_url") . '/images/icon-url.png" /></a>';
				}
				
				break;
			
			#CLIENT NAME
			case "client_name":
				
				#INITIALISE CLIENT NAME
				$portfolio_client_name = get_post_meta($post->ID, "portfolio_client_name", true);
				
				#DISPLAY CLIENT NAME
				if(!empty($portfolio_client_name))
				{
					echo $portfolio_client_name;
				}
				
				break;
				
			#CLIENT LOCATION
			case "client_location":
				
				#INITIALISE CLIENT LOCATION
				$portfolio_client_location = get_post_meta($post->ID, "portfolio_client_location", true);
				
				#DISPLAY CLIENT LOCATION
				if(!empty($portfolio_client_location))
				{
					echo $portfolio_client_location;
				}
				
				break;
		}
	}
	
	#THIS FUNCTION CREATES THE PROJECT BOX
	function mp_meta_boxes_project()
	{
		#ADD PROJECT BOX TO PROJECT CUSTOM POSTS
		add_meta_box("portfolio_box", "Project Information", array("mp_options", "mp_meta_boxes_portfolio_form"), "project", "normal", "high");
	 
		#SAVE PROJECT BOX FORM CONTENTS
		add_action("save_post", array("mp_options", "mp_meta_boxes_portfolio_form_save"));
	}
	
	#THIS FUNCTION CREATES THE PROJECT BOX FORM
	function mp_meta_boxes_portfolio_form()
	{
		#RETRIEVE THE POST
		global $post;
	
		#INITIALISE PROJECT ERROR BOX ID
		$portfolio_error_box = "portfolio_errors" . $post->ID;
	
		#INITIALISE PROJECT OPTIONS
		$portfolio_client_name = get_post_meta($post->ID, "portfolio_client_name", true);
		$portfolio_client_location = get_post_meta($post->ID, "portfolio_client_location", true);
		$portfolio_project_url = get_post_meta($post->ID, "portfolio_project_url", true);
		$portfolio_project_gallery = get_post_meta($post->ID, "portfolio_project_gallery", true);
		
		#DISPLAY PROJECT NONCE FIELD
		echo '<input name="portfolio_nonce" id="portfolio_nonce" type="hidden" value="' . wp_create_nonce(__FILE__) . '" />';
				
		#DISPLAY TESTIMONIAL FIELDS
		echo '<p><strong>Client Name:</strong><br /><input name="portfolio_client_name" id="portfolio_client_name" type="text" size="80" value="' . $portfolio_client_name . '" /></p><p>Enter the name of the client.</p>';
		echo '<p><strong>Client Location:</strong><br /><input name="portfolio_client_location" id="portfolio_client_location" type="text" size="80" value="' . $portfolio_client_location . '" /></p><p>Enter the location of the client.</p>';
		echo '<p><strong>Project URL:</strong><br /><input name="portfolio_project_url" id="portfolio_project_url" type="text" size="80" value="' . urldecode($portfolio_project_url) . '" /></p><p>Enter the URL of the project.</p>';
		echo '<p><strong>Project Gallery:</strong><br />'; mp_options::mp_display_gallery_list("portfolio_project_gallery", $portfolio_project_gallery); echo '</p><p>Select the gallery of the project.</p>';		
		
		?>
		<script type="text/javascript">
		jQuery(document).ready(function()
		{
			jQuery("div.wrap").after('<div id="<?php echo $portfolio_error_box; ?>" class="mp_errors error"></div>');
			
			jQuery("form#post").validate(
			{
				//VALIDATION CONTAINER & ERROR MESSAGES
				errorLabelContainer: jQuery("#<?php echo $portfolio_error_box; ?>"),
				errorElement: "p",
				errorClass: "mp_error_field",
				
				//VALIDATION RULES
				rules:
				{
					portfolio_project_url:
					{
						url2: true
					}
				},
				//VALIDATION MESSAGES
				messages:
				{
					portfolio_project_url:
					{
						url2: "Please enter a valid URL."
					}
				}
			});
			
			jQuery("#publish").click(function()
			{
				form_check = jQuery("#post").valid();
				
				if(!form_check)
				{
					return false;
				}
			});
		});
		</script>
		<?php
	}
	
	#THIS FUNCTION SAVES THE PROJECT BOX FORM CONTENTS
	function mp_meta_boxes_portfolio_form_save($post_id) 
	{		
		#SAVE PROJECT BOX FORM CONTENTS
		mp_options::mp_meta_boxes_save($post_id, "portfolio_nonce", "portfolio_client_name", "post");
		mp_options::mp_meta_boxes_save($post_id, "portfolio_nonce", "portfolio_client_location", "post");
		mp_options::mp_meta_boxes_save($post_id, "portfolio_nonce", "portfolio_project_url", "post");
		mp_options::mp_meta_boxes_save($post_id, "portfolio_nonce", "portfolio_project_gallery", "post");
		
		#RETURN POST ID
		return $post_id;
	}
	
	#THIS FUNCTION DISPLAYS THE PROJECTS
	function mp_display_projects($category, $page)
	{		
		#RETRIEVE THE POST
		global $post;
		
		#INITIALISE PROJECT ARGUMENTS OF PROJECT PAGE
		if(empty($category))
		{
			#INITIALISE PROJECT ARGUMENTS
			$args = array
			(
				"post_type" => "project",
				"post_status" => "publish",
				"posts_per_page" => get_option("posts_per_page"),
				"paged" => $page,
				"order" => "DESC",
				"orderby" => "date"
			);
		}
		#INITIALISE PROJECT ARGUMENTS OF PROJECT CATEGORIES
		else
		{
			#INITIALISE PROJECT ARGUMENTS
			$args = array
			(
				"post_type" => "project",
				"post_status" => "publish",
				"posts_per_page" => get_option("posts_per_page"),
				"paged" => $page,
				"order" => "DESC",
				"orderby" => "date",
				"tax_query" =>
				array
				(
					array
					(
						"taxonomy" => "portfolio-categories",
						"field" => "slug",
						"terms" => $category
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
			echo '<ul id="projects">';
			
			#DISPLAY PROJECTS
			while($projects->have_posts())
			{
				#RETRIEVE THE PROJECT CONTENT
				$projects->the_post();
				
				#INITIALISE PROJECT TITLE
				$project_title = $post->post_title;
				
				#OPEN PROJECT LIST ITEM
				echo "<li>";
				
				#OPEN PROJECT LINK
				echo '<a href="' . get_permalink() . '">';
				
				#PROJECT THUMBNAIL EXISTS
				if(has_post_thumbnail())
				{
					#INITIALISE PROJECT THUMBNAIL FILE
					$project_thumbnail_file = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "full");
					
					#DISPLAY PROJECT THUMBNAIL
					echo '<img src="' . $project_thumbnail_file[0] . '" alt="' . $project_title . '" title="' . $project_title . '" class="project_thumbnail" />';
				}
				#PROJECT THUMBNAIL DOES NOT EXIST
				else
				{
					#DISPLAY DEFAULT PROJECT THUMBNAIL
					echo '<img src="' . get_bloginfo("template_url") . '/images/portfolio-thumbnail-default.png" alt="' . $project_title . '" title="' . $project_title . '" class="project_thumbnail" />';
				}
				
				#CLOSE PROJECT LINK
				echo "</a>";
				
				#DISPLAY PROJECT TITLE
				echo '<h2><a href="' . get_permalink() . '">' . $project_title . "</a></h2>";
				
				#DISPLAY PROJECT CATEGORIES
				echo '<p class="categories">' . get_the_term_list($post->ID, "portfolio-categories", "", ", ") . "</p>";
				
				#DISPLAY EXCERPT VIA THE ADVANCED EXCERPT PLUGIN
				if(function_exists("the_advanced_excerpt"))
				{
					the_advanced_excerpt("length=20");
				}
				#DISPLAY EXCERPT VIA THE CLASS FUNCTION
				else
				{
					mp_options::mp_display_project_excerpt(20);
				}
				
				#CLOSE PROJECT LIST ITEM
				echo "</li>";				
			}
			
			#CLOSE PROJECT LIST
			echo "</ul>";
			
			#DISPLAY WP-PAGENAVI PAGING NAVIGATION LINKS
			if(function_exists("wp_pagenavi"))
			{
				wp_pagenavi(array("query" =>$projects));
			}
			#DISPLAY DEFAULT WORDPRESS PAGING NAVIGATION LINKS
			else
			{
			?>
				<p class="left"><?php next_posts_link("&laquo; Previous Entries"); ?></p>
				<p class="right"><?php previous_posts_link("Next Entries &raquo;"); ?></p>
			<?php
			}
		}
	}
	
	#THIS FUNCTION DISPLAYS THE PROJECT EXCERPT WITH A MAXIMUM NUMBER OF WORDS
	function mp_display_project_excerpt($max_words)
	{
		#INITIALISE CONTENT
		$content = get_the_content();
		
		#INITIALISE CONTENT WORD COUNT
		$content_word_count = str_word_count($content);
		
		#CONTENT CONTAINS LESS WORDS THAN MAXIMUM NUMBER OF WORDS
		if($content_word_count < $max_words)
		{
			echo $content;
		}
		#CONTENT CONTAINS MORE WORDS THAN MAXIMUM NUMBER OF WORDS
		else
		{
			#REMOVE TAGS FROM CONTENT
			$content = trim(strip_tags($content));
			
			#TRUNCATE CONTENT INTO THE MAXIMUM NUMBER OF WORDS
			preg_match("/(\S+\s*){0,$max_words}/", $content, $excerpt);
			
			#INITIALISE TRUNCATED CONTENT
			$content_excerpt = trim($excerpt[0]) . '... <a href="' . get_permalink() . '" title="' . get_the_title() . '">Read the rest</a>';
			
			#DISPLAY TRUNCATED CONTENT
			echo wpautop($content_excerpt);
		}
	}
	
	#THIS FUNCTION DISPLAYS THE PROJECT THUMBNAILS
	function mp_display_project_thumbnails()
	{
		#NEXTGEN GALLERY PLUGIN IS ACTIVATED
		if(function_exists("nggShowSlideshow"))
		{
			#INITIALISE PROJECT GALLERY
			$portfolio_project_gallery = get_post_meta(get_the_ID(), "portfolio_project_gallery", true);
			
			#PROJECT GALLERY EXISTS
			if(!empty($portfolio_project_gallery))
			{			
				#INITIALISE PROJECT GALLERY THUMBNAILS
				$project_thumbnails = nggdb::get_gallery($portfolio_project_gallery);
				
				echo '<!-- PROJECT GALLERY - START -->
				<div id="project_gallery"></div>
				<!-- PROJECT GALLERY - END -->' . "\n\n";
				
				echo '<!-- PROJECT GALLERY CAPTION - START -->
				<div id="project_gallery_caption"></div>
				<!-- PROJECT GALLERY CAPTION - END -->' . "\n\n";
				
				echo '<!-- PROJECT GALLERY THUMBNAILS - START -->
				<div id="project_gallery_thumbnails">' . "\n";
				
				#DISPLAY PREVIOUS BUTTON
				echo '<a class="previous" href="#" title="Previous"><img src="' . get_bloginfo("template_url") . '/images/arrow-slider-left-off.png" alt="Previous" /></a>';
				
				#OPEN UNORDERED LIST
				echo '<ul class="thumbs noscript">';
			
				#DISPLAY PROJECT GALLERY THUMBNAILS
				foreach($project_thumbnails as $project_thumbnail)
				{
					echo '<li><a href="' . $project_thumbnail->imageURL . '" class="thumb" title="' . $project_thumbnail->alttext . ': ' . $project_thumbnail->description . '"><img src="' . $project_thumbnail->thumbURL . '" alt="' . $project_thumbnail->alttext . '" /></a><div class="caption"><p>' . $project_thumbnail->alttext . ': ' . $project_thumbnail->description . '</p></div></li>';
				}
				
				#CLOSE UNORDERED LIST
				echo "</ul>";
				
				#DISPLAY NEXT BUTTON
				echo '<a class="next" href="#" title="Next"><img src="' . get_bloginfo("template_url") . '/images/arrow-slider-right-off.png" alt="Next" /></a>';
				
				echo '</div>
				<!-- PROJECT GALLERY THUMBNAILS - END -->'. "\n";
			}
		}
		#NEXTGEN GALLERY PLUGIN IS NOT ACTIVATED
		else
		{
			return;
		}		
	}
	
	#THIS FUNCTION DISPLAYS THE PROJECT DETAILS
	function mp_display_project_details()
	{
		#INITIALISE PROJECT DETAILS
		$portfolio_client_name = get_post_meta(get_the_ID(), "portfolio_client_name", true);
		$portfolio_client_location = get_post_meta(get_the_ID(), "portfolio_client_location", true);
		$portfolio_project_url = get_post_meta(get_the_ID(), "portfolio_project_url", true);
		$portfolio_project_gallery = get_post_meta(get_the_ID(), "portfolio_project_gallery", true);
		$portfolio_project_scope_terms = get_the_terms(get_the_ID(), "portfolio-scope");
		$portfolio_project_skill_terms = get_the_terms(get_the_ID(), "portfolio-skill");
		
		#OPEN PROJECT DETAILS DIV WITH TOP MARGIN
		if(!empty($portfolio_project_gallery))
		{
			echo '<div id="project_details1">';
		}
		#OPEN PROJECT DETAILS DIV WITHOUT TOP MARGIN
		else
		{
			echo '<div id="project_details2">';
		}
		
		#OPEN TABLE
		echo "<table>";
		
		#PROJECT URL EXISTS
		if(!empty($portfolio_project_url))
		{
			#DISPLAY PROJECT URL
			echo '<tr><td class="column1">URL:</td><td class="column2"><a href="' . $portfolio_project_url . '" rel="nofollow">' . $portfolio_project_url . "</a></td></tr>";
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
		echo "</table>";
		
		#CLOSE PROJECT DETAILS DIV
		echo "</div>";
	}
	
	#THIS FUNCTION DISPLAYS THE TESTIMONIALS
	function mp_display_testimonials($scope, $page)
	{
		#RETRIEVE THE POST
		global $post;
		
		#INITIALISE TESTIMONIAL ARGUMENTS OF PROJECT PAGE
		if($scope == "project")
		{
			#INITIALISE TESTIMONIAL ARGUMENTS
			$args = array
			(
				"post_type" => "testimonial",
				"post_status" => "publish",
				"posts_per_page" => get_option("posts_per_page"),
				"paged" => $page,
				"order" => "DESC",
				"orderby" => "date",
				"meta_query" =>
				array
				(
					array
					(
						"key" => "testimonial_project",
						"value" => get_the_ID()
					)
				)
			);
		}
		#INITIALISE TESTIMONIAL ARGUMENTS OF TESTIMONIALS PAGE
		elseif($scope == "testimonials")
		{
			#INITIALISE TESTIMONIAL ARGUMENTS
			$args = array
			(
				"post_type" => "testimonial",
				"post_status" => "publish",
				"posts_per_page" => get_option("posts_per_page"),
				"paged" => $page,
				"order" => "DESC",
				"orderby" => "date"
			);
		}		
	
		#INITIALISE TESTIMONIALS
		$testimonials = new WP_Query($args);
		
		#TESTIMONIALS EXISTS
		if($testimonials->have_posts())
		{
			#DISPLAY TESTIMONIAL SUB HEADING
			if($scope == "project")
			{
				echo '<h3 class="sub_heading">Testimonials</h3>';
			}
			
			#DISPLAY TESTIMONIAL
			while($testimonials->have_posts())
			{
				#RETRIEVE THE TESTIMONIAL CONTENT
				$testimonials->the_post();
				
				#INITIALISE TESTIMONIAL DETAILS
				$testimonial_name = get_post_meta($post->ID, "testimonial_name", true);
				$testimonial_location = get_post_meta($post->ID, "testimonial_location", true);
				$testimonial_photo = get_post_meta($post->ID, "testimonial_photo", true);
				$testimonial_url = get_post_meta($post->ID, "testimonial_url", true);
				$testimonial_pdf = get_post_meta($post->ID, "testimonial_pdf", true);
				
				#INITIALISE TESTIMONIAL CONTENT
				$testimonial_content = get_the_content();
				
				#APPEND TESTIMONIAL NAME & LOCATION
				$testimonial_content .= "<br /><br />- $testimonial_name, $testimonial_location";
				
				#APPEND TESTIMONIAL URL
				if(!empty($testimonial_url))
				{
					$testimonial_content .= ', <a href="' . $testimonial_url . '" rel="nofollow">' . $testimonial_url . '</a>';
				}
				
				#APPEND TESTIMONIAL PHOTO
				if(!empty($testimonial_photo))
				{					
					$testimonial_content = '<img src="' . $testimonial_photo . '" alt="'. $testimonial_name . '" title="'. $testimonial_name . '" class="testimonial_photo" />' . $testimonial_content;
				}
				
				#APPEND TESTIMONIAL PDF
				if(!empty($testimonial_pdf))
				{
					$testimonial_content .= ', <a href="' . $testimonial_pdf . '" title="Testimonial in PDF" rel="nofollow" target="_blank"><img src="' . get_bloginfo("template_url") . '/images/icon-pdf.png" alt="Testimonial in PDF" title="Testimonial in PDF" class="testimonial_pdf" />PDF</a>';
				}
				
				#DISPLAY TESTIMONIAL BOX
				echo mp_options::mp_testimonial_shortcode("", $testimonial_content);		
			}
							
			#DISPLAY WP-PAGENAVI PAGING NAVIGATION LINKS
			if(function_exists("wp_pagenavi"))
			{
				wp_pagenavi(array("query" =>$testimonials));
			}
			#DISPLAY DEFAULT WORDPRESS PAGING NAVIGATION LINKS
			else
			{
			?>
				<p class="left"><?php next_posts_link("&laquo; Previous Testimonials"); ?></p>
				<p class="right"><?php previous_posts_link("Next Testimonials &raquo;"); ?></p>
			<?php
			}
		}
		#TESTIMONIALS DO NOT EXIST
		else
		{
			return;	
		}
	}
	
	#THIS FUNCTION ADDS CONTENT TO A TESTIMONIAL BOX
	function mp_testimonial_shortcode($parameters, $content = null)
	{
		#ADD CONTENT TO TESTIMONIAL BOX
		$content = '<div class="testimonial_box"><div class="testimonial">' . wpautop($content) . '</div></div>';
		
		#RETURN CONTENT
		return do_shortcode($content);
	}
	
	#THIS FUNCTION CREATES THE TESTIMONIALS CUSTOM POST TYPE
	function mp_custom_posts_testimonials()
	{
		#INITIALISE TESTIMONIAL CUSTOM POST TYPE LABELS
		$labels = array
		(
			"name" => _x("Testimonials", "post type general name"),
			"singular_name" => _x("Testimonial", "post type singular name"),
			"add_new" => _x("Add New", "testimonial"),
			"add_new_item" => __("Add New Testimonial"),
			"edit_item" => __("Edit Testimonial"),
			"new_item" => __("New Testimonial"),
			"all_items" => __("All Testimonials"),
			"view_item" => __("View Testimonial"),
			"search_items" => __("Search Testimonials"),
			"not_found" =>  __("No Testimonials found"),
			"not_found_in_trash" => __("No Testimonials found in Trash"), 
			"parent_item_colon" => "",
			"menu_name" => "Testimonials"
		);
		
		#INITIALISE TESTIMONIAL CUSTOM POST TYPE ARGUMENTS
		$args = array
		(
			"labels" => $labels,
			"description" => "Testimonial",
			"public" => true,
			"publicly_queryable" => true,
			"exclude_from_search" => false,
			"show_ui" => true, 
			"show_in_menu" => true,
			"menu_position" => 20,
			"menu_icon" => null,
			"capability_type" => "post",
			"hierarchical" => false,
			"supports" => array("title", "editor", "revisions"),
			"has_archive" => false,
			"rewrite" => array("slug" => "testimonial", "with_front" => false),
			"query_var" => false,
			"can_export" => true,
			"show_in_nav_menus" => true
		);
		
		#REGISTER TESTIMONIAL CUSTOM POST TYPE
		register_post_type("testimonial", $args);
	}
	
	#THIS FUNCTION DISPLAYS THE TESTIMONIAL COLUMNS
	function mp_testimonial_edit_columns($columns)
	{
		#INITIALISE TESTIMONIAL COLUMNS
		$columns = 
		array
		(
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Title",
			"project" => "Project",
			"name" => "Name",
			"location" => "Location",
			"photo" => "Photo",
			"url" => "URL",
			"pdf" => "PDF",
			"date" => "Date"
		);
		
		#RETURN TESTIMONIAL COLUMNS
		return $columns;
	}
	
	#THIS FUNCTION DISPLAYS THE TESTIMONIAL COLUMN VALUES
	function mp_testimonial_custom_columns($column)
	{
		#RETRIEVE THE POST
		global $post;
		
		#DISPLAY TESTIMONIAL VALUES
		switch($column)
		{
			#TESTIMONIAL PROJECT
			case "project":
			
				#INITIALISE TESTIMONIAL PROJECT ID
				$testimonial_project = get_post_meta($post->ID, "testimonial_project", true);
			
				#INITIALISE TESTIMONIAL PROJECT TITLE
				$testimonial_project_title = get_the_title($testimonial_project);
				
				#DISPLAY TESTIMONIAL PROJECT TITLE WITH LINK
				if(!empty($testimonial_project_title))
				{
					echo '<a href="post.php?post=' . $testimonial_project . '&action=edit">' . $testimonial_project_title . '</a>';
				}
				
				break;
			
			#TESTIMONIAL NAME
			case "name":
				
				#INITIALISE TESTIMONIAL NAME
				$testimonial_name = get_post_meta($post->ID, "testimonial_name", true);
				
				#DISPLAY TESTIMONIAL NAME
				if(!empty($testimonial_name))
				{
					echo $testimonial_name;
				}
				
				break;
				
			#TESTIMONIAL LOCATION
			case "location":
				
				#INITIALISE TESTIMONIAL LOCATION
				$testimonial_location = get_post_meta($post->ID, "testimonial_location", true);
				
				#DISPLAY TESTIMONIAL LOCATION
				if(!empty($testimonial_location))
				{
					echo $testimonial_location;
				}
				
				break;
				
			#TESTIMONIAL PHOTO
			case "photo":
				
				#INITIALISE TESTIMONIAL NAME & PHOTO
				$testimonial_name = get_post_meta($post->ID, "testimonial_name", true);
				$testimonial_photo = get_post_meta($post->ID, "testimonial_photo", true);
				
				#DISPLAY TESTIMONIAL PHOTO ICON
				if(!empty($testimonial_photo))
				{
					echo '<a href="' . $testimonial_photo . '" title="' . $testimonial_name . '" class="colorbox"><img src="' . get_bloginfo("template_url") . '/images/icon-picture.png" /></a>';
				}
				
				break;
				
			#TESTIMONIAL URL
			case "url":
			
				#INITIALISE TESTIMONIAL URL
				$testimonial_url = get_post_meta($post->ID, "testimonial_url", true);
				
				#DISPLAY TESTIMONIAL URL ICON
				if(!empty($testimonial_url))
				{
					echo '<a href="' . $testimonial_url . '" target="_blank"><img src="' . get_bloginfo("template_url") . '/images/icon-url.png" /></a>';
				}
				
				break;
				
			#TESTIMONIAL PDF
			case "pdf":
			
				#INITIALISE TESTIMONIAL PDF
				$testimonial_pdf = get_post_meta($post->ID, "testimonial_pdf", true);
				
				#DISPLAY TESTIMONIAL PDF ICON
				if(!empty($testimonial_pdf))
				{
					echo '<a href="' . $testimonial_pdf . '" target="_blank"><img src="' . get_bloginfo("template_url") . '/images/icon-pdf.png" /></a>';
				}
				
				break;
		}
	}
	
	#THIS FUNCTION CREATES THE TESTIMONIAL BOX
	function mp_meta_boxes_testimonial()
	{
		#ADD TESTIMONIAL BOX TO TESTIMONIAL CUSTOM POSTS
		add_meta_box("testimonial_box", "Testimonial Information", array("mp_options", "mp_meta_boxes_testimonial_form"), "testimonial", "normal", "high");
	 
		#SAVE TESTIMONIAL BOX FORM CONTENTS
		add_action("save_post", array("mp_options", "mp_meta_boxes_testimonial_form_save"));
	}
	
	#THIS FUNCTION CREATES THE TESTIMONIAL BOX FORM
	function mp_meta_boxes_testimonial_form()
	{
		#RETRIEVE THE POST
		global $post;
	
		#INITIALISE TESTIMONIAL ERROR BOX ID
		$testimonial_error_box = "testimonial_errors" . $post->ID;
	
		#INITIALISE TESTIMONIAL OPTIONS
		$testimonial_project = get_post_meta($post->ID, "testimonial_project", true);
		$testimonial_name = get_post_meta($post->ID, "testimonial_name", true);
		$testimonial_location = get_post_meta($post->ID, "testimonial_location", true);
		$testimonial_photo = get_post_meta($post->ID, "testimonial_photo", true);
		$testimonial_url = get_post_meta($post->ID, "testimonial_url", true);
		$testimonial_pdf = get_post_meta($post->ID, "testimonial_pdf", true);
		
		#DISPLAY TESTIMONIAL NONCE FIELD
		echo '<input name="testimonial_nonce" id="testimonial_nonce" type="hidden" value="' . wp_create_nonce(__FILE__) . '" />';
				
		#DISPLAY TESTIMONIAL FIELDS
		echo '<p><strong>Project:</strong><br />'; mp_options::mp_display_project_list("testimonial_project", $testimonial_project); echo '</p><p>Select the project of the testimonial.</p>';
		echo '<p><strong>Name:</strong><br /><input name="testimonial_name" id="testimonial_name" type="text" size="80" value="' . $testimonial_name . '" /></p><p>Enter the name of the person who wrote the testimonial.</p>';
		echo '<p><strong>Location:</strong><br /><input name="testimonial_location" id="testimonial_location" type="text" size="80" value="' . $testimonial_location . '" /></p><p>Enter the location of the person who wrote the testimonial.</p>';
		echo '<p><strong>Photo:</strong><br /><input name="testimonial_photo" id="testimonial_photo" type="text" size="80" value="' . urldecode($testimonial_photo) . '" /></p><p>Enter the photo URL of the person who wrote the testimonial.</p>';
		echo '<p><strong>URL:</strong><br /><input name="testimonial_url" id="testimonial_url" type="text" size="80" value="' . urldecode($testimonial_url) . '" /></p><p>Enter the URL of the person who wrote the testimonial.</p>';
		echo '<p><strong>PDF:</strong><br /><input name="testimonial_pdf" id="testimonial_pdf" type="text" size="80" value="' . urldecode($testimonial_pdf) . '" /></p><p>Enter the URL of the PDF document of the testimonial.</p>';
		?>
		<script type="text/javascript">
		jQuery(document).ready(function()
		{
			jQuery("div.wrap").after('<div id="<?php echo $testimonial_error_box; ?>" class="mp_errors error"></div>');
			
			jQuery("form#post").validate(
			{
				//VALIDATION CONTAINER & ERROR MESSAGES
				errorLabelContainer: jQuery("#<?php echo $testimonial_error_box; ?>"),
				errorElement: "p",
				errorClass: "mp_error_field",
				
				//VALIDATION RULES
				rules:
				{
					testimonial_name:
					{
						required: true
					},
					testimonial_location:
					{
						required: true
					},
					testimonial_photo:
					{
						url2: true
					},
					testimonial_url:
					{
						url2: true
					}
				},
				//VALIDATION MESSAGES
				messages:
				{
					testimonial_name:
					{
						required: "Please enter a Name."
					},
					testimonial_location:
					{
						required: "Please enter a Location."
					},
					testimonial_photo:
					{
						url2: "Please enter a valid Photo URL."
					},
					testimonial_url:
					{
						url2: "Please enter a valid URL."
					}
				}
			});
			
			jQuery("#publish").click(function()
			{
				form_check = jQuery("#post").valid();
				
				if(!form_check)
				{
					return false;
				}
			});
		});
		</script>
		<?php
	}
	
	#THIS FUNCTION SAVES THE TESTIMONIAL BOX FORM CONTENTS
	function mp_meta_boxes_testimonial_form_save($post_id) 
	{		
		#SAVE TESTIMONIAL BOX FORM CONTENTS
		mp_options::mp_meta_boxes_save($post_id, "testimonial_nonce", "testimonial_project", "post");
		mp_options::mp_meta_boxes_save($post_id, "testimonial_nonce", "testimonial_name", "post");
		mp_options::mp_meta_boxes_save($post_id, "testimonial_nonce", "testimonial_location", "post");
		mp_options::mp_meta_boxes_save($post_id, "testimonial_nonce", "testimonial_photo", "post");
		mp_options::mp_meta_boxes_save($post_id, "testimonial_nonce", "testimonial_url", "post");
		mp_options::mp_meta_boxes_save($post_id, "testimonial_nonce", "testimonial_pdf", "post");
		
		#RETURN POST ID
		return $post_id;
	}
	
	#THIS FUNCTION SAVES THE META BOX FORM CONTENTS
	function mp_meta_boxes_save($post_id, $nonce, $field_name, $type, $url_encode = false)
	{		
		#FORMATTING FORM DID NOT SUBMIT FROM THE RIGHT PLACE
		if(!wp_verify_nonce($_POST["$nonce"], __FILE__))
		{
			return $post_id;
		}
		
		#DETERMINE USER'S PERMISSIONS TO EDIT PAGE/POST
		switch($type)
		{
			#PAGES
			case "page":
			
				#USER HAS NO PERMISSION TO EDIT PAGE
				if($_POST["post_type"] == "page" && !current_user_can("edit_pages", $post_id)) 
				{
					return $post_id;
				}
				
			#POSTS
			case "post":
			
				#USER HAS NO PERMISSION TO EDIT POST
				if($_POST["post_type"] == "post" && !current_user_can("edit_posts", $post_id)) 
				{
					return $post_id;
				}
		}
		
		#INITIALISE CURRENT FIELD
		$current_field = get_post_meta($post_id, $field_name, true);
		
		#INITIALISE NEW FIELD
		$new_field = $_POST["$field_name"];
		
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
	
	#THIS FUNCTION REPLACES THE "BIOGRAPHICAL INFO" FIELD IN THE USER PROFILE WITH A TINYMCE EDITOR
	function mp_tinymce_biography($user)
	{
		?>
		<table class="form-table">
		<tr>
			<th><label for="description"><?php _e("Biographical Info"); ?></label></th>
			<td><?php wp_editor(get_user_meta($user->ID, "description", true), "description", array("textarea_rows" => 15)); ?><p class="description"><?php _e("Share a little biographical information to fill out your profile. This may be shown publicly."); ?></p></td>
		</tr>
		</table>
		<?php
	}
	
	#THIS FUNCTION UPDATES THE USER PROFILE CONTACT INFO FIELDS
	function mp_contact_info($contact_fields)
	{
		#DELETE AIM, YIM & JABBER FIELDS
		unset($contact_fields["aim"]);
		unset($contact_fields["jabber"]);
		unset($contact_fields["yim"]);
		
		#ADD FACEBOOK, TWITTER, GOOGLE+, PINTEREST, LINKEDIN, GITHUB, DRIBBLE, INSTAGRAM, INSTAGRAM RSS FEED
		$contact_fields["facebook"] = "Facebook";
		$contact_fields["twitter"] = "Twitter";
		$contact_fields["google_plus"] = "Google+";
		$contact_fields["pinterest"] = "Pinterest";
		$contact_fields["linkedin"] = "LinkedIn";
		$contact_fields["github"] = "Github";
		$contact_fields["dribbble"] = "Dribbble";
		$contact_fields["dribbble_rss"] = "Dribbble RSS Feed";
		$contact_fields["instagram"] = "Instagram";
		$contact_fields["instagram_rss"] = "Instagram RSS Feed";
		
		return $contact_fields;
	}
	
	#THIS FUNCTION DISPLAYS THE INSTAGRAM THUMBNAILS
	function mp_display_instagram_thumbnails()
	{	
		#INITIALISE INSTAGRAM RSS FEED
		$instagram_rss = get_user_meta(mp_options::mp_get_author_id(), "instagram_rss", true);
		
		#INSTAGRAM RSS FEED EXISTS
		if(!empty($instagram_rss))
		{
			#INCLUDE SIMPLEPIE RSS PARSER
			include_once(ABSPATH.WPINC . "/class-simplepie.php");
			
			#INITIALISE SIMPLEPIE OBJECT
			$feed = new SimplePie();
			 
			#INITIALISE SIMPLEPIE FEED
			$feed->set_feed_url($instagram_rss);
				
			#INITIALISE SIMPLEPIE CACHE LOCATION
			$feed->set_cache_location(dirname(dirname(__FILE__)) . "/cache");
			 
			#RUN SIMPLEPIE FEED
			$feed->init();
			
			#OPEN UNORDERED LIST
			echo "<ul>";
			
			#DISPLAY INSTAGRAM THUMBNAILS
			foreach($feed->get_items(0, 12) as $item)
			{
				#FORMAT INSTAGRAM THUMBNAIL WITH THUMBNAIL IMAGE & IMAGE TITLE IN ALT/TITLE ATTRIBUTE
				$instagram_thumbnail = str_replace("_7.jpg", "_5.jpg", $item->get_description());
				$instagram_thumbnail = str_replace('" />', '" alt="' . $item->get_title() . '" title="' . $item->get_title() . '" />', $instagram_thumbnail);
				
				#DISPLAY INSTAGRAM THUMBNAIL
				echo '<li><a href="' . $item->get_permalink() . '" title="' . $item->get_title() . '" class="instagram_iframe"><span class="magnify"></span>' . $instagram_thumbnail . '</a></li>' . "\n";
			}
			
			#CLOSE UNORDERED LIST
			echo "</ul>";
		}
	}
	
	#THIS FUNCTION DISPLAYS THE DRIBBBLE THUMBNAILS
	function mp_display_dribbble_thumbnails()
	{
		#INITIALISE DRIBBBLE RSS FEED
		$dribbble_rss = get_user_meta(mp_options::mp_get_author_id(), "dribbble_rss", true);
		
		#DRIBBBLE RSS FEED EXISTS
		if(!empty($dribbble_rss))
		{
			#INCLUDE SIMPLEPIE RSS PARSER
			include_once(ABSPATH.WPINC . "/class-simplepie.php");
			
			#INITIALISE SIMPLEPIE OBJECT
			$feed = new SimplePie();
			 
			#INITIALISE SIMPLEPIE FEED
			$feed->set_feed_url($dribbble_rss);
			
			#INITIALISE SIMPLEPIE CACHE LOCATION
			$feed->set_cache_location(dirname(dirname(__FILE__)) . "/cache");
			 
			#RUN SIMPLEPIE FEED
			$feed->init();
			
			#OPEN UNORDERED LIST
			echo "<ul>";
			
			#DISPLAY DRIBBBLE THUMBNAILS
			foreach($feed->get_items(0, 4) as $item)
			{
				#FORMAT DRIBBBLE THUMBNAIL WITHOUT HYPERLINK & IMAGE TITLE IN ALT/TITLE ATTRIBUTE
				if(preg_match('#<img alt="(.+?)" height="300" src="(.+?)" width="400" />#i', $item->get_description(), $dribbble_thumbnail_url))
				{
					$dribbble_thumbnail = '<img src="' . $dribbble_thumbnail_url[2] . '" alt="' . $item->get_title() . '" title="' . $item->get_title() . '" />';
				}
				
				#DISPLAY DRIBBBLE THUMBNAIL
				echo '<li><a href="' . $item->get_permalink() . '" title="' . $item->get_title() . '" class="dribbble_iframe"><span class="magnify"></span>' . $dribbble_thumbnail . '</a></li>' . "\n";
			}
			
			#CLOSE UNORDERED LIST
			echo "</ul>";
		}
	}
	
	#THIS FUNCTION DISPLAYS THE PROJECT CATEGORIES IN THE SIDEBAR
	function mp_display_portfolio_categories($current_category)
	{
		#CUSTOM TAXONOMY SORT PLUGIN ACTIVATED
		if(class_exists("CustomTaxonomySort"))
		{
			#INITIALISE CATEGORIES WITH CUSTOM SORT ORDER
			$categories = get_terms("portfolio-categories", "orderby=custom_sort&order=ASC&hide_empty=0");
		}
		#CUSTOM TAXONOMY SORT PLUGIN DEACTIVATED
		else
		{
			#INITIALISE CATEGORIES WITH NAME SORT ORDER
			$categories = get_terms("portfolio-categories", "orderby=name&hide_empty=0");
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
					echo '<li class="current"><a href="' . get_term_link($category->slug, "portfolio-categories") . '">' . $category->name . "</a></li>";
				}
				#DISPLAY UNSELECTED SUB CATEGORY LINKS
				else
				{
					echo '<li><a href="' . get_term_link($category->slug, "portfolio-categories") . '">' . $category->name . "</a></li>";
				}
			}
		}
	}
	
	#THIS FUNCTION DISPLAYS THE SOCIAL MEDIA BUTTONS IN THE SIDEBAR
	function mp_display_social_buttons()
	{
		#INITIALISE AUTHOR ID
		$author_id = mp_options::mp_get_author_id();
		
		#INITIALISE SOCIAL MEDIA PROFILES
		$facebook = get_user_meta($author_id, "facebook", true);
		$twitter = get_user_meta($author_id, "twitter", true);
		$google_plus = get_user_meta($author_id, "google_plus", true);
		$pinterest = get_user_meta($author_id, "pinterest", true);
		$linkedin = get_user_meta($author_id, "linkedin", true);
		$github = get_user_meta($author_id, "github", true);
		$dribbble = get_user_meta($author_id, "dribbble", true);
		$instagram = get_user_meta($author_id, "instagram", true);
		
		#OPEN SOCIAL BOX & UNORDERED LIST
		echo '<div class="social_buttons"><ul class="social_buttons_list">';
		
		#DISPLAY FACEBOOK BUTTON
		if(!empty($facebook))
		{
			echo '<li><a href="' . $facebook . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo("template_directory") . '/images/icon-facebook.png" alt="Facebook" title="Facebook" /></a></li>';
		}
		
		#DISPLAY TWITTER BUTTON
		if(!empty($twitter))
		{
			echo '<li><a href="' . $twitter . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo("template_directory") . '/images/icon-twitter.png" alt="Twitter" title="Twitter" /></a></li>';
		}
		
		#DISPLAY GOOGLE+ BUTTON
		if(!empty($google_plus))
		{
			echo '<li><a href="' . $google_plus . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo("template_directory") . '/images/icon-google.png" alt="Google+" title="Google+" /></a></li>';
		}
		
		#DISPLAY PINTEREST BUTTON
		if(!empty($pinterest))
		{
			echo '<li><a href="' . $pinterest . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo("template_directory") . '/images/icon-pinterest.png" alt="Pinterest" title="Pinterest" /></a></li>';
		}
		
		#DISPLAY LINKEDIN BUTTON
		if(!empty($linkedin))
		{
			echo '<li><a href="' . $linkedin . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo("template_directory") . '/images/icon-linkedin.png" alt="LinkedIn" title="LinkedIn" /></a></li>';
		}
		
		#DISPLAY GITHUB BUTTON
		if(!empty($github))
		{
			echo '<li><a href="' . $github . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo("template_directory") . '/images/icon-github.png" alt="GitHub" title="GitHub" /></a></li>';
		}
		
		#DISPLAY DRIBBBLE BUTTON
		if(!empty($github))
		{
			echo '<li><a href="' . $dribbble . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo("template_directory") . '/images/icon-dribbble.png" alt="Dribbble" title="Dribbble" /></a></li>';
		}
		
		#DISPLAY INSTAGRAM BUTTON
		if(!empty($instagram))
		{
			echo '<li><a href="' . $instagram . '" target="_blank" rel="nofollow"><img src="' . get_bloginfo("template_directory") . '/images/icon-instagram.png" alt="Instagram" title="Instagram" /></a></li>';
		}
		
		#CLOSE SOCIAL BOX & UNORDERED LIST
		echo "</ul></div>";
	}
	
	#THIS FUNCTION DISPLAYS THE FACEBOOK LIKE BOX IN THE SIDEBAR
	function mp_display_facebook_like_box()
	{
		#INITIALISE FACEBOOK LIKE BOX CODE
		$facebook_code = get_option("mp_facebook_like_box");
		
		#DISPLAY FACEBOOK LIKE BOX
		if(!empty($facebook_code))
		{
			echo '<div class="facebook_like_box">' . $facebook_code . '</div>';
		}
	}
	
	#THIS FUNCTION DISPLAYS THE RECENT POSTS
	function mp_display_recent_posts()
	{
		#RETRIEVE THE DATABASE
		global $wpdb;
		
		#INITIALISE NUMBER OF POSTS
		$number_of_posts = get_option("mp_posts_recent_number");
		
		#INITIALISE DEFAULT NUMBER OF POSTS
		if(empty($number_of_posts))
		{
			$number_of_posts = 5;
		}

		#RETREIVE POSTS
		$posts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC LIMIT $number_of_posts");
		
		#POSTS EXIST
		if(!empty($posts))
		{
			#OPEN UNORDERED LIST
			echo '<ul id="recent_posts" class="sidebar">';
			
			#DISPLAY POSTS
			foreach($posts as $post)
			{
				echo '<li><a href="' . get_permalink($post->ID) . '" title="' . $post->post_title . '">' . $post->post_title . '</a></li>';
			}
			
			#CLOSE UNORDERED LIST
			echo "</ul>\n";
		}
	}
	
	#THIS FUNCTION DISPLAYS THE MOST COMMENTED POSTS
	function mp_display_most_commented_posts()
	{
		#RETRIEVE THE DATABASE
		global $wpdb;
		
		#INITIALISE NUMBER OF POSTS
		$number_of_posts = get_option("mp_posts_comments_number");
		
		#INITIALISE DEFAULT NUMBER OF POSTS
		if(empty($number_of_posts))
		{
			$number_of_posts = 5;
		}

		#RETREIVE POSTS
		$posts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE comment_count > 0 AND post_status = 'publish' AND post_type = 'post' ORDER BY comment_count DESC LIMIT $number_of_posts");
		
		#POSTS EXIST
		if(!empty($posts))
		{
			#OPEN UNORDERED LIST
			echo '<ul id="most_comments" class="sidebar hide">';
			
			#DISPLAY POSTS
			foreach($posts as $post)
			{
				echo '<li><a href="' . get_permalink($post->ID) . '" title="' . $post->post_title . '">' . $post->post_title . ' (' . mp_options::mp_get_comment_type_count($post->ID, "comment") . ')</a></li>';
			}
			
			#CLOSE UNORDERED LIST
			echo "</ul>\n";
		}
	}
	
	#THIS FUNCTION DISPLAYS THE RECENT COMMENTS
	function mp_display_recent_comments()
	{
		#RETRIEVE THE DATABASE
		global $wpdb;
		
		#INITIALISE NUMBER OF COMMENTS
		$number_of_comments = get_option("mp_comments_recent_number");
		
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
		
		#COMMENTS EXIST
		if(!empty($comments))
		{
			#OPEN UNORDERED LIST
			echo '<ul id="recent_comments" class="sidebar">';
			
			#DISPLAY COMMENTS
			foreach($comments as $comment)
			{
				echo '<li><a href="' . get_permalink($comment->ID) . '#comment-' . $comment->comment_ID . '" title="' . $comment->post_title . '">' . strip_tags($comment->comment_author) . ': ' . strip_tags($comment->com_excerpt) . '… ' . get_the_time("j F Y", $comment->comment_ID) . '</a></li>';

			}
			
			#CLOSE UNORDERED LIST
			echo "</ul>\n";
		}		
	}
	
	#THIS FUNCTION DISPLAYS THE TOP COMMENTERS
	function mp_display_top_commenters()
	{
		#RETRIEVE THE DATABASE
		global $wpdb;
		
		#INITIALISE NUMBER OF COMMENTERS
		$number_of_commenters = get_option("mp_comments_commenters_number");
		
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
		GROUP BY user_id
		ORDER BY total_comments DESC
		LIMIT $number_of_commenters";
	
		#RETREIVE COMMENTERS
		$commenters = $wpdb->get_results($sql);
		
		#COMMENTERS EXIST
		if(!empty($commenters))
		{					
			#OPEN ORDERED LIST
			$html = '<ol id="top_commenters" class="sidebar hide">';
			
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
			$html .= "</ol>\n";
			
			#DISPLAY ORDERED LIST
			echo $html;
		}
	}
	
	#THIS FUNCTION RETURNS THE AUTHOR ID
	function mp_get_author_id()
	{
		#INITIALISE AUTHOR ID
		$mp_author = get_option("mp_author");
			
		#SET DEFAULT AUTHOR ID
		if(empty($mp_author))
		{
			$mp_author = 1;
		}
		
		#RETURN AUTHOR ID
		return $mp_author;
	}
	
	#THIS FUNCTION RETURNS THE COMMENT TYPE COUNT
	#ACCEPTS: 'comment', 'trackback', 'pingback' for $comment_type
	function mp_get_comment_type_count($post_id, $comment_type)
	{
		#RETRIEVE THE DATABASE
		global $wpdb;
		
		#INITIALISE SQL QUERY
		switch($comment_type)
		{
			#COMMENTS
			case "comment":
				$sql = "SELECT COUNT(comment_id) FROM $wpdb->comments WHERE comment_type = '" . $comment_type . "' OR comment_type = '' and comment_approved = 1 and comment_post_id = $post_id";
				break;
			
			#PINGBACKS & TRACKBACKS
			case "pingback":
			case "trackback":
				$sql = "SELECT COUNT(comment_id) FROM $wpdb->comments WHERE comment_type = '" . $comment_type . "' and comment_approved = 1 and comment_post_id = $post_id";
				break;			
		}
		
		#INITIALISE COMMENT TYPE COUNT
		$comment_type_count = $wpdb->get_var($sql);
	
		#RETURN COMMENT TYPE COUNT
		return $comment_type_count;
	}
	
	#THIS FUNCTION DISPLAYS THE COMMENT TYPE COUNT LABELS
	#REPLACES THE comments_number() FUNCTION
	#mp_display_comment_counter(139, "comment", "0 Comments", "1 Comments", "Comments")
	function mp_display_comment_counter($post_id, $comment_type, $label_zero, $label_single, $label_multiple)
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
			echo "$comment_type_count $label_multiple";
			
			return;
		}
	}
	
	#THIS FUNCTION DISPLAYS THE COMMENTS
	function mp_display_comment_list($comment, $args, $depth)
	{
		#RETRIEVE THE COMMENT
   		$GLOBALS["comment"] = $comment;
		
		#DISPLAY APPROVED COMMENT
      	if($comment->comment_approved)
		{
			#INITIALISE GRAVATAR DEFAULT AVATAR & AVATAR HASH			
			$gravatar_default = urlencode(get_bloginfo("template_directory") . "/images/icon-avatar.png");
			$gravatar_hash = md5(strtolower(trim(get_comment_author_email())));
			?>			
			<!-- COMMENT <?php comment_ID(); ?> - START -->
			<li id="comment<?php comment_ID(); ?>" <?php comment_class(); ?>>
				
				<!-- COMMENT AVATAR, AUTHOR & DATE - START -->
				<a name="comment-<?php comment_ID(); ?>"></a>
				<div class="comment_header">
					<div class="comment_avatar"><a href="<?php comment_author_url(); ?>" rel="nofollow"><img src="http://www.gravatar.com/avatar/<?php echo $gravatar_hash; ?>?s=80&r=g&d=<?php echo $gravatar_default; ?>" alt="<?php comment_author(); ?>" title="<?php comment_author(); ?>" /></a></div>
					<div class="comment_author_date">
						<p class="comment_author"><a href="<?php comment_author_url(); ?>" rel="nofollow"><?php comment_author(); ?></a></p>
						<p class="comment_date"><?php comment_date(); ?> <?php comment_time() ?></p>
					</div>
				</div>
				<!-- COMMENT AVATAR, AUTHOR & DATE - END -->				
			
				<!-- COMMENT TEXT - START -->
				<div class="comment_text">
					<?php
					#DISPLAY COMMENT TEXT
					comment_text();
					
					#DISPLAY COMMENT REPLY LINK
					if($args["max_depth"] != $depth)
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
	function mp_display_ping_list($comment)
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
	function mp_display_search_results_title()
	{
		#RETRIEVE THE QUERY
		global $wp_query;
		
		#INITIALISE NUMBER OF SEARCH RESULTS
		$search_results = sizeof($wp_query->posts);
		
		#NO SEARCH RESULTS
		if($search_results == 0)
		{
			echo "No Search Results";
			
			return;
		}
		#1 SEARCH RESULT
		elseif($search_results == 1)
		{
			echo "1 Search Result";
			
			return;
		}
		#MORE THAN 1 SEARCH RESULT
		elseif($search_results > 1)
		{
			echo "$search_results Search Results";
			
			return;
		}
	}

	#THIS FUNCTION DISPLAYS THE BLOG POSTS
	function mp_display_blog_posts($page)
	{		
		#RETRIEVE THE POST
		global $post;
		
		#INITIALISE BLOG POST ARGUMENTS
		$args = array
		(
			"posts_per_page" => get_option("posts_per_page"),
			"post_type"  => "post",
			"post_status" => "publish",
			"paged" => $page,
			"order" => "DESC",
			"orderby" => "date"
		);
		
		#RETRIEVE BLOG POSTS
		$blog_posts = new WP_Query($args);
		
		#BLOG POSTS EXISTS
		if($blog_posts->have_posts())
		{			
			#DISPLAY BLOG POSTS
			while($blog_posts->have_posts())
			{
				$blog_posts->the_post();
				
				#INCLUDE BLOG POST TEMPLATE
				include(TEMPLATEPATH . "/includes/inc-blog-post.php");
			}

			#DISPLAY WP-PAGENAVI PAGING NAVIGATION LINKS
			if(function_exists("wp_pagenavi"))
			{
				wp_pagenavi(array("query" =>$blog_posts));
			}
			#DISPLAY DEFAULT WORDPRESS PAGING NAVIGATION LINKS
			else
			{
			?>
				<p class="left"><?php next_posts_link("&laquo; Previous Entries"); ?></p>
				<p class="right"><?php previous_posts_link("Next Entries &raquo;"); ?></p>
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

	#THIS FUNCTION DISPLAYS THE BLOG POST WITH A MAXIMUM NUMBER OF WORDS
	function mp_display_blog_post_excerpt($max_words)
	{
		#INITIALISE CONTENT
		$content = get_the_content();
		
		#DISPLAY SHORTCODE IN CONTENT
		$content = do_shortcode($content);
		$content = apply_filters("the_content", $content);
		
		#INITIALISE CONTENT WORD COUNT
		$content_word_count = str_word_count($content);
		
		#CONTENT CONTAINS LESS WORDS THAN MAXIMUM NUMBER OF WORDS
		if($content_word_count < $max_words)
		{
			echo $content;
		}
		#CONTENT CONTAINS MORE WORDS THAN MAXIMUM NUMBER OF WORDS
		else
		{
			#TRUNCATE CONTENT INTO THE MAXIMUM NUMBER OF WORDS
			preg_match("/(\S+\s*){0,$max_words}/", $content, $excerpt);
			
			#INITIALISE TRUNCATED CONTENT
			$content_excerpt = trim($excerpt[0]) . '... <a href="' . get_permalink() . '" title="' . get_the_title() . '">Read the rest</a>';
			
			#DISPLAY TRUNCATED CONTENT
			echo $content_excerpt;
		}
	}

	#THIS FUNCTION RETURNS THE PAGINATION PAGE
	function mp_get_page()
	{
		#INITIALISE PAGE VIA PAGED VARIABLE
		if(get_query_var("paged"))
		{
			$page = get_query_var("paged");
		}
		#INITIALISE PAGE VIA PAGE VARIABLE
		elseif(get_query_var("page"))
		{
			$page = get_query_var("page");
		}
		#INITIALISE DEFAULT PAGE
		else
		{
			$page = 1;
		}
		
		#RETURN PAGE
		return $page;	
	}
}
?>