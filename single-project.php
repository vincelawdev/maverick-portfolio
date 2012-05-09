<?php
#Template Name: Portfolio Project Template

get_header();
?>

		<!-- PORTFOLIO PROJECT - START -->
		<?php
		#PORTFOLIO PROJECT EXISTS
		if(have_posts())
		{
			#DISPLAY PORTFOLIO PROJECT
			while(have_posts())
			{
				the_post();
				?>		
				<h1 class="page_title"><?php the_title(); ?></h1>
				
				<?php mp_options::mp_display_project_thumbnails(); ?>
				
				<!-- PROJECT DETAILS - START -->
				<?php mp_options::mp_display_project_details(); the_content(); ?>
				<!-- PROJECT DETAILS - END -->
				
				<!-- PROJECT TESTIMONIALS - START -->
				<?php mp_options::mp_display_testimonials("project", mp_options::mp_get_page()); ?>
				<!-- PROJECT TESTIMONIALS - END -->
			<?php
			}
		}
		?>
		<!-- PORTFOLIO PROJECT - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>