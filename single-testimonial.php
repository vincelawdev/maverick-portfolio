<?php
#Template Name: Testimonial Template

get_header();
?>

		<!-- TESTIMONIAL - START -->
		<?php
		#TESTIMONIAL EXISTS
		if(have_posts())
		{
			#DISPLAY TESTIMONIAL
			while(have_posts())
			{
				the_post();
				?>		
				<h1 class="page_title"><?php the_title(); ?></h1>
				<?php mp_options::mp_display_testimonial(); ?>
			<?php
			}
		}
		?>
		<!-- TESTIMONIAL - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>