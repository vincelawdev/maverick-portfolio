<?php
#Template Name: Home Page Template

get_header();
?>
		
		<!-- ANYTHING SLIDER - START -->
		<div id="anything_slider"><?php mp_options::mp_display_slides(); ?></div>
		<!-- ANYTHING SLIDER - END -->
		
		<?php
		#PAGE EXISTS
		if(have_posts())
		{
			#DISPLAY PAGE
			while(have_posts())
			{
				the_post();
			}
		}
		?>	

	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>