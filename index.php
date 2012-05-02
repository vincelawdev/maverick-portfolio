<?php get_header(); ?>

		<!-- INDEX - START -->
		<?php
		#RETRIEVE BLOG POSTS
		if(!is_search())
		{
			#RETRIEVE PAGE
			$page = (get_query_var("paged")) ? get_query_var("paged") : 1;
			
			#RETRIEVE POSTS
			query_posts("paged=$page");
		}
		#SEARCH RESULTS
		else
		{
			#RETRIEVE SEARCH TERM
			$search_term = trim($_REQUEST["s"]);
			?>
			<h1 class="post_title_underline">Search Results for &quot;<?php echo $search_term; ?>&quot;</h1>
			<?php
		}
		
		#POSTS EXISTS
		if(have_posts())
		{			
			#DISPLAY POSTS
			while(have_posts())
			{
				the_post();
				
				#INCLUDE BLOG POST TEMPLATE
				include(TEMPLATEPATH . "/includes/inc-blog-post.php");
			}
			
			#INCLUDE BLOG POST NAVIGATION TEMPLATE
			include(TEMPLATEPATH . "/includes/inc-blog-post-navigation.php");
		}
		#NO POSTS EXIST
		else
		{
		?>		
		<p>Sorry, no posts matched your criteria.</p>
		<?php
		}
		?>
		<!-- INDEX - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>