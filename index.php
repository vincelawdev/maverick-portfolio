<?php get_header(); ?>

		<!-- INDEX - START -->
		<?php
		#RETRIEVE BLOG POSTS
		if(!is_search())
		{
			#INITIALISE PAGE
			$page = mp_options::mp_get_page();
			
			#RETRIEVE POSTS
			query_posts("paged=$page");
		}
		#SEARCH RESULTS
		else
		{
			#RETRIEVE SEARCH TERM
			$search_term = trim($_REQUEST["s"]);
			?>
			<h1 class="page_title"><?php mp_options::mp_display_search_results_title(); ?> for &quot;<?php echo $search_term; ?>&quot;</h1>
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
			#NO BLOG POSTS
			if(!is_search())
			{
				echo "<p>Sorry, no posts matched your criteria.</p>";
			}
			#NO SEARCH RESULTS
			else
			{
				echo "<p>Sorry, no results matched your search criteria.</p>";
			}
		}
		?>
		<!-- INDEX - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>