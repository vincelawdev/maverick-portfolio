<?php
#Template Name: Blog Index Page Template

get_header();
?>

		<!-- BLOG POSTS - START -->
		<?php mp_options::mp_display_blog_posts(get_query_var("paged")); ?>
		<!-- BLOG POSTS - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>