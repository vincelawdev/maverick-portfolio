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
				?>		
					<h3 class="post_title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post_title_link"><?php the_title(); ?></a></h3>
					<p class="post_info">Posted on <?php the_date(); ?> by <?php the_author(); ?> in <?php the_category(", ");?> | <a href="#comments"><?php comments_number(__("0 Comments"), __("1 Comment"), __("% Comments")); ?></a></p>
					<div class="addthis_toolbox addthis_default_style" addthis:url="<?php the_permalink(); ?>" addthis:title="<?php the_title(); ?>">
						<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
						<a class="addthis_button_tweet"></a>
						<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
						<a class="addthis_counter addthis_pill_style"></a>
					</div>			
					<div class="post_line"><?php the_excerpt(); ?></div>
				<?php
			}
			
			#DISPLAY WP-PAGENAVI PAGING NAVIGATION LINKS
			if(function_exists("wp_pagenavi"))
			{
				wp_pagenavi();
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