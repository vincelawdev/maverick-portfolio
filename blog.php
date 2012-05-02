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
		<!-- BLOG POSTS - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>