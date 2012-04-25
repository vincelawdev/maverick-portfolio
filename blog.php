<?php
#Template Name: Blog Index Page Template

get_header();
?>

		<!-- BLOG POSTS - START -->
		<?php	
		#RETRIEVE PAGE
		$page = get_query_var("paged");
		
		#INITIALISE BLOG POST ARGUMENTS
		$args = array
		(
			"posts_per_page" => 10,
			"post_type"  => "post",
			"post_status" => "publish",
			"paged" => $page,
			"order" => "DESC",
			"orderby" => "date"
		);
		
		#RETRIEVE BLOG POSTS
		$blog_posts = new WP_Query($args);
		
		#POSTS EXISTS
		if($blog_posts->have_posts())
		{			
			#DISPLAY POSTS
			while($blog_posts->have_posts())
			{
				$blog_posts->the_post();
				?>		
					<h3 class="post_title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post_title_link"><?php the_title(); ?></a></h3>
					<p class="post_info">Posted on <?php the_date(); ?> by <?php the_author(); ?> in <?php the_category(", ");?> | <a href="#comments"><?php comments_number(__("0 Comments"), __("1 Comment"), __("% Comments")); ?></a></p>				
					<div class="addthis_toolbox addthis_default_style" addthis:url="<?php the_permalink(); ?>" addthis:title="<?php the_title(); ?>">
						<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
						<a class="addthis_button_tweet"></a>
						<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
						<a class="addthis_counter addthis_pill_style"></a>
					</div>			
					<div class="post_body post_line"><?php the_excerpt(); ?></div>
				<?php
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
		#NO POSTS EXIST
		else
		{
		?>		
		<p>Sorry, no posts matched your criteria.</p>
		<?php
		}
		?>
		<!-- BLOG POSTS - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>