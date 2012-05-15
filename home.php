<?php
#Template Name: Home Page Template

get_header();
?>
		
		<!-- ANYTHING SLIDER - START -->
		<div id="slider1_wrapper"><?php mp_options::mp_display_slides(); ?></div>
		<!-- ANYTHING SLIDER - END -->
		
		<!-- FEATURED WORK SLIDER - START -->
		<h3 class="sub_heading">Featured Work</h3>
		<div class="previous" title="Previous"></div><div id="featured_work_wrapper"><?php mp_options::mp_display_projects("", mp_options::mp_get_page(), false, "featured_work", 17); ?></div><div class="next" title="Next"></div>
		<!-- FEATURED WORK SLIDER - END -->
		
		<!-- TESTIMONIALS - START -->
		<?php mp_options::mp_display_testimonials("home", mp_options::mp_get_page(), false, 100); ?>		
		<!-- TESTIMONIALS - END -->		

	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>