<?php
#Template Name: Testimonials Template

get_header();
?>
	
		<!-- TESTIMONIALS - START -->
		<?php
		#TESTIMONIALS EXISTS
		if(have_posts())
		{
			the_post();
			?>		
			<h1 class="page_title"><?php the_title(); ?></h1>
			<?php mp_options::mp_display_testimonials('testimonials', mp_options::mp_get_page()); ?>
		<?php
		}
		?>
		<!-- TESTIMONIALS - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>