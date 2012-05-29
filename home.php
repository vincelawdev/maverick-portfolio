<?php
#Template Name: Home Page Template

get_header();
?>
		
		<!-- ANYTHING SLIDER - START -->
		<div id="anythingslider_wrapper"><?php mp_options::mp_display_slides(); ?></div>
		<!-- ANYTHING SLIDER - END -->
		
		<!-- FEATURED WORK SLIDER - START -->
		<h3 class="sub_heading">Featured Work</h3>
		<div class="home_previous" title="Previous"></div>
		<div id="featured_work_wrapper"><?php mp_options::mp_display_projects('', mp_options::mp_get_page(), false, 'featured_work', true, 15); ?></div>
		<div class="home_next" title="Next"></div>
		<!-- FEATURED WORK SLIDER - END -->
		
		<!-- TESTIMONIALS - START -->
		<?php mp_options::mp_display_testimonials('home', mp_options::mp_get_page(), false, 100); ?>		
		<!-- TESTIMONIALS - END -->
		
		<!-- LATEST BLOG POSTS - START -->
		<div id="home_column1">
			<h3 class="sub_heading">Latest Blog Posts</h3>
			<?php mp_options::mp_display_recent_posts_home(); ?>
		</div>
		<!-- LATEST BLOG POSTS - END -->
		
		<!-- LATEST ARTICLES - START -->
		<div id="home_column2">
			<h3 class="sub_heading">Latest Articles</h3>
			<?php mp_options::mp_display_recent_articles_home(); ?>
		</div>
		<!-- LATEST ARTICLES - END -->

	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>