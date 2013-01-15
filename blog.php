<?php
#Template Name: Blog Index Page Template

get_header();
?>

		<!-- BLOG POSTS - START -->
		<?php
		#BLOG POSTS EXISTS
		if(have_posts())
		{
			the_post();
			?>
			<h1 class="page_title"><?php the_title(); ?></h1>
			<div class="page_content"><?php the_content(); ?></div>
			<?php mp_options::mp_display_blog_posts(mp_options::mp_get_page()); ?>
		<?php
		}
		?>
		<!-- BLOG POSTS - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>