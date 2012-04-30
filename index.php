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
					<p class="post_info">Posted on <?php the_date(); ?> by <?php the_author(); ?> in <?php the_category(", ");?> | <a href="<?php the_permalink(); ?>#comments"><?php mp_options::display_comment_counter(get_the_ID(), "comment", "0 Comments", "1 Comment", "Comments"); ?></a></p>
					<?php include(TEMPLATEPATH . "/includes/inc-blog-social.php"); ?>
					<div class="post_line">
					<?php
					#DISPLAY POST THUMBNAIL
					if(has_post_thumbnail())
					{
						echo '<a href="' . get_permalink() . '" title="' . get_the_title() . '" class="post_thumbnail">' . get_the_post_thumbnail($blog_posts->ID, "thumbnail") . '</a>';
					}
					
					#DISPLAY POST EXCERPT
					the_excerpt();
					?>
					</div>
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