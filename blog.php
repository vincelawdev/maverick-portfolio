<?php
#Template Name: Blog Index Page Template

get_header();
?>

		<!-- BLOG POSTS - START -->
		<?php mp_options::mp_display_blog_posts(mp_options::mp_get_page()); ?>
		<!-- BLOG POSTS - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>