<?php
#Template Name: Home Page Template

get_header();
?>
		
            <!-- FLEXSLIDER - START -->
            <div class="home-slides flexslider"><?php mp_options::mp_display_slides(); ?></div>
            <!-- FLEXSLIDER - END -->
            
            <!-- FEATURED WORK SLIDER - START -->
            <h3 class="sub-heading">Featured Work</h3>
            <div class="home-slides-projects flexslider"><?php mp_options::mp_display_projects('', mp_options::mp_get_page(), false, '', 'slides', 'home-slides-projects-item', true, 15); ?></div>
            <!-- FEATURED WORK SLIDER - END -->
            
            <!-- TESTIMONIALS - START -->
            <?php mp_options::mp_display_testimonials('home', mp_options::mp_get_page(), false, 100); ?>		
            <!-- TESTIMONIALS - END -->
            
            <!-- LATEST BLOG POSTS - START -->
            <div class="home-column col6">
            
            	<div class="home-column-left">
                
                    <h3 class="sub-heading">Latest Blog Posts</h3>
                    <?php mp_options::mp_display_recent_posts_home(); ?>
                    
                </div>
                
            </div>
            <!-- LATEST BLOG POSTS - END -->
            
            <!-- LATEST ARTICLES - START -->
            <div class="home-column col6">
            	
                <div class="home-column-right">
                
                    <h3 class="sub-heading">Latest Articles</h3>
                    <?php mp_options::mp_display_recent_articles_home(); ?>
                
                </div>
                
            </div>
            <!-- LATEST ARTICLES - END -->

		</div>
		<!-- CONTENT WRAPPER - END -->     
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- PAGE WRAPPER - END -->
	
<?php get_footer(); ?>