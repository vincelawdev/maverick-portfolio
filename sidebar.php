<!-- SIDEBAR - START -->
<div id="sidebar">

	<?php
	#INITIALISE AUTHOR ID
	$mp_author = get_option("mp_author");
		
	#SET DEFAULT AUTHOR ID
	if(empty($mp_author))
	{
		$mp_author = 1;
	}
	
	#PAGE
	if(is_page())
	{	
		#SUB PAGES EXIST
		if(wp_list_pages("child_of=".$post->ID."&echo=0"))
		{
		?>
			<!-- SUB PAGES - START -->
			<div class="sidebar_box">
			
				<h4><?php wp_title(""); ?></h4>
				<ul class="categories"><?php wp_list_pages("child_of=".$post->ID."&sort_column=menu_order&sort_order=ASC&depth=1&title_li="); ?></ul>
				
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
					<ul class="categories"><?php echo $children; ?></ul>
					
				</div>
				<!-- SUB PAGES - END -->
			<?php
			}
		}
	}
	?>	
	
	<!-- SOCIAL - START -->
	<div class="sidebar_box">
	
		<h4>Connect With <?php the_author_meta("first_name", $mp_author); ?></h4>
		
		
	</div>
	<!-- SOCIAL - END -->
	
	<?php
	#DISPLAY CATEGORIES, POSTS, COMMENTS & ARCHIVES
	if(!is_page() || is_page("blog"))
	{
	?>
	<!-- CATEGORIES - START -->
	<div class="sidebar_box">
	
		<h4>Categories</h4>	
		<ul class="categories"><?php wp_list_cats("sort_column=name&optioncount=1&hierarchical=0"); ?></ul>
		
	</div>
	<!-- CATEGORIES - END -->
	
	<!-- ARCHIVES - START -->
	<div class="sidebar_box">
	
		<h4>Archives</h4>
		<form action="" method="post">
		<p><select name="archives" onchange="document.location.href=this.options[this.selectedIndex].value;"><option value="">Select Month</option><?php wp_get_archives("type=monthly&format=option"); ?></select></p>
		</form>
	
	</div>
	<!-- ARCHIVES - END -->
	
	<!-- POST TABS - START -->
	<div class="sidebar_box">
	
		<h4>Posts</h4>
		
		<!-- POST TABS TITLES - START -->
		<ul class="post_tabs">
			<li title="Latest Posts"><a href="javascript:void(0);" rel="#tab1">Latest Posts</a></li>
			<li title="Popular Posts"><a href="javascript:void(0);" rel="#tab2">Popular Posts</a></li>
			<li title="Most Commented Posts"><a href="javascript:void(0);" rel="#tab3">Most Comments</a></li>
		</ul>
		<!-- POST TABS TITLES - END -->
		
		<!-- POST TABS CONTENTS - START -->
		<div class="tab_container">
		
			<!-- LATEST POSTS - START -->
			<div id="tab1" class="tab_content">
				<?php if(function_exists('recent_posts')) { recent_posts(); } ?>
			</div>
			<!-- LATEST POSTS - END -->
			
			<!-- POPULAR POSTS - START -->
			<div id="tab2" class="tab_content">
				<?php if(function_exists('popular_posts')) { popular_posts(); } ?>
			</div>
			<!-- POPULAR POSTS - END -->
			
			<!-- MOST COMMENTED POSTS - START -->
			<div id="tab3" class="tab_content">
				<ul><?php if(function_exists('mdv_most_commented')) { mdv_most_commented(5); } ?></ul>
			</div>
			<!-- MOST COMMENTED POSTS - END -->
			
		</div>
		<!-- POST TABS CONTENTS - END -->
		
	</div>
	<!-- POST TABS - END -->
	
	<!-- COMMENT TABS - START -->
	<div class="sidebar_box">
	
		<h4>Comments</h4>
	
		<!-- COMMENT TABS TITLES - START -->
		<ul class="comment_tabs">
			<li title="Latest Comments"><a href="javascript:void(0);" rel="#tab4">Latest Comments</a></li>
			<li title="Top Commenters"><a href="javascript:void(0);" rel="#tab5">Top Commenters</a></li>
		</ul>
		<!-- COMMENT TABS TITLES - END -->
		
		<!-- COMMENT TABS CONTENTS - START -->
		<div class="tab_container">
		
			<!-- LATEST COMMENTS - START -->
			<div id="tab4" class="tab_content">
				<?php if(function_exists('recent_comments')) { recent_comments(); } ?>
			</div>
			<!-- LATEST COMMENTS - END -->
			
			<!-- TOP COMMENTERS - START -->
			<div id="tab5" class="tab_content">
				<?php if(function_exists('fp_get_topcommenters')) { echo fp_get_topcommenters(); } ?>
			</div>
			<!-- TOP COMMENTERS - END -->
			
		</div>
		<!-- COMMENT TABS CONTENTS - END -->
	
	</div>
	<!-- COMMENT TABS - END -->
		
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
