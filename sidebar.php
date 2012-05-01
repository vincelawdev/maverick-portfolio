<!-- SIDEBAR - START -->
<div id="sidebar">

	<?php	
	#DISPLAY SUB PAGES
	if(is_page())
	{	
		#SUB PAGES EXIST
		if(wp_list_pages("child_of=".$post->ID."&echo=0"))
		{
		?>
			<!-- SUB PAGES - START -->
			<div class="sidebar_box">
			
				<h4><?php wp_title(""); ?></h4>
				<ul class="sidebar"><?php wp_list_pages("child_of=".$post->ID."&sort_column=menu_order&sort_order=ASC&depth=1&title_li="); ?></ul>
				
			</div>
			<!-- SUB PAGES - END -->
		<?php
		}
		#SUB PAGES DO NOT EXIST
		elseif(!wp_list_pages("child_of=".$post->ID."&echo=0"))
		{		
			#RETRIEVE CHILD PAGES FROM PAGE PARENT
			if($post->post_parent)
			{
				$children = wp_list_pages("title_li=&child_of=".$post->post_parent."&sort_column=menu_order&sort_order=ASC&depth=1&echo=0");
			}
			#RETRIEVE CHILD PAGES FROM CURRENT PAGE
			else
			{
				$children = wp_list_pages("title_li=&child_of=".$post->ID."&sort_column=menu_order&sort_order=ASC&depth=1&echo=0");
			}
			
			#DISPLAY CHILD PAGES
			if($children)
			{
			?>
				<!-- SUB PAGES - START -->
				<div class="sidebar_box">
				
					<h4><?php echo get_the_title($post->post_parent); ?></h4>
					<ul class="sidebar"><?php echo $children; ?></ul>
					
				</div>
				<!-- SUB PAGES - END -->
			<?php
			}
		}
	}
	#DISPLAY CATEGORIES, SOCIAL, POSTS, COMMENTS & ARCHIVES
	if(!is_page() || is_page("blog"))
	{
	?>
	<!-- CATEGORIES - START -->
	<div class="sidebar_box">
	
		<h4>Categories</h4>	
		<ul class="sidebar"><?php wp_list_cats("sort_column=name&hierarchical=0"); ?></ul>
		
	</div>
	<!-- CATEGORIES - END -->
	<?php
	}	
	?>
	
	<!-- SOCIAL - START -->
	<div class="sidebar_box">
	
		<h4>Connect With <?php the_author_meta("first_name", mp_options::get_author_id()); ?></h4>
		<?php mp_options::display_social_buttons(); ?>
		<?php mp_options::display_facebook_like_box(); ?>
		
	</div>
	<!-- SOCIAL - END -->
	
	<?php
	#DISPLAY CATEGORIES, SOCIAL, POSTS, COMMENTS & ARCHIVES
	if(!is_page() || is_page("blog"))
	{
	?>
	<!-- POST TABS - START -->
	<div id="post_tabs" class="sidebar_box">
	
		<!-- POST TABS TITLES - START -->
		<div class="tab_box">
			<h4 class="tabs">Posts</h4>
			<ul class="nav">
				<li><a href="#recent_posts" class="current">Recent</a></li>
				<?php if(function_exists("popular_posts")) { ?><li><a href="#popular_posts">Popular</a></li><?php } ?>
				<li class="last"><a href="#most_comments">Most Comments</a></li>
			</ul>
		</div>
		<!-- POST TABS TITLES - END -->
		 
		<!-- RECENT POSTS - START -->
		<?php if(function_exists("recent_posts")) { recent_posts(); } else { mp_options::display_recent_posts(); } ?>
		<!-- RECENT POSTS - END -->
		 
		<!-- POPULAR POSTS - START -->
		<?php if(function_exists("popular_posts")) { popular_posts(); } ?>
		<!-- POPULAR POSTS - END -->
		 
		<!-- MOST COMMENTED POSTS - START -->
		<?php mp_options::display_most_commented_posts(); ?>
		<!-- MOST COMMENTED POSTS - END -->

	</div>
	<!-- POST TABS - END -->
		
	<!-- COMMENT TABS - START -->
	<div id="comment_tabs" class="sidebar_box">
	
		<!-- COMMENT TABS TITLES - START -->
		<div class="tab_box">
			<h4 class="tabs">Comments</h4>
			<ul class="nav">
				<li><a href="#recent_comments" class="current">Recent</a></li>
				<li class="last"><a href="#top_commenters">Top Commenters</a></li>
			</ul>
		</div>
		<!-- COMMENT TABS TITLES - END -->
	
		<!-- RECENT COMMENTS - START -->
		<?php if(function_exists("recent_comments")) { recent_comments(); } else { mp_options::display_recent_comments(); } ?>
		<!-- RECENT COMMENTS - END -->
		
		<!-- TOP COMMENTERS - START -->
		<?php mp_options::display_top_commenters(); ?>
		<!-- TOP COMMENTERS - END -->
	
	</div>
	<!-- COMMENT TABS - END -->
	
	<!-- ARCHIVES - START -->
	<div class="sidebar_box">
	
		<h4>Archives</h4>
		<form action="" method="post">
		<select name="archives" onchange="document.location.href=this.options[this.selectedIndex].value;"><option value="">Select Month</option><?php wp_get_archives("type=monthly&format=option"); ?></select>
		</form>
	
	</div>
	<!-- ARCHIVES - END -->
	<?php
	}	
	?>
			
	<!--- WIDGETS - START -->
	<?php
	#DISPLAY SIDEBAR WIDGETS
	if(!function_exists("dynamic_sidebar") || !dynamic_sidebar())
	{
	}
	?>
	<!--- WIDGETS - END -->

</div>
<!-- SIDEBAR - END -->
