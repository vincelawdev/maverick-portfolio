<?php get_header(); ?>

		<!-- ARCHIVES - START -->
		<?php
		#RETRIEVE POSTS
		query_posts($query_string . '&cat=');
	
		#POSTS EXIST
		if(have_posts())
		{
			#CATEGORY ARCHIVE
			if(is_category())
			{
			?>
			<h1 class="page_title">Archive for the &quot;<?php echo single_cat_title(); ?>&quot; Category</h1>
			<?php
			}
			#TAG ARCHIVE
			elseif(is_tag())
			{
			?>
			<h1 class="page_title">Archive for the &quot;<?php echo single_tag_title(); ?>&quot; Tag</h1>
			<?php
			}
			#DAILY ARCHIVE
			elseif(is_day())
			{
			?>
			<h1 class="page_title">Archive for <?php the_time('jS F Y'); ?></h1>
			<?php
			}
			#MONTHLY ARCHIVE
			elseif(is_month())
			{
			?>
			<h1 class="page_title">Archive for <?php the_time('F Y'); ?></h1>
			<?php
			}
			#YEARLY ARCHIVE
			elseif(is_year())
			{
			?>
			<h1 class="page_title">Archive for <?php the_time('Y'); ?></h1>
			<?php
			}
			#AUTHOR ARCHIVES
			elseif(is_author())
			{
				#INITIALISE AUTHOR OBJECT
				$author = get_userdata(get_query_var('author'));			
			?>
			<h1 class="page_title">Archive for <?php echo $author->display_name; ?></h1>
			<?php
			#PAGED ARCHIVE
			}
			elseif(isset($_GET['paged']) && !empty($_GET['paged']))
			{
			?>
			<h1 class="post_title">Blog Archives</h1>
			<?php
			}
			
			#DISPLAY POSTS
			while(have_posts())
			{
				the_post();
				
				#INCLUDE BLOG POST TEMPLATE
				include(TEMPLATEPATH . '/includes/inc-blog-post.php');
			}

			#INCLUDE BLOG POST NAVIGATION TEMPLATE
			include(TEMPLATEPATH . '/includes/inc-blog-post-navigation.php');
		}
		#NO POSTS EXIST
		else
		{
		?>		
		<p>Sorry, no posts matched your criteria.</p>
		<?php
		}
		?>
		<!-- ARCHIVES - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>