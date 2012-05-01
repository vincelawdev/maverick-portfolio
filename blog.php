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
			"posts_per_page" => get_option("posts_per_page"),
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