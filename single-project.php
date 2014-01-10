<?php
#Template Name: Portfolio Project Template

get_header();
?>

            <!-- PORTFOLIO PROJECT - START -->
            <?php
            #PORTFOLIO PROJECT EXISTS
            if(have_posts())
            {
                the_post();
                ?>		
                <h1 class="page-title"><?php the_title(); ?></h1>
                
                <!-- PROJECT THUMBNAILS - START -->
                <?php mp_options::mp_display_project_thumbnails(); ?>
                <!-- PROJECT THUMBNAILS - END -->
                
                <!-- PROJECT DETAILS - START -->
                <div class="page-content"><?php mp_options::mp_display_project_details(); the_content(); ?></div>
                <!-- PROJECT DETAILS - END -->
                
                <!-- PROJECT TESTIMONIALS - START -->
                <?php mp_options::mp_display_testimonials('project', mp_options::mp_get_page()); ?>
                <!-- PROJECT TESTIMONIALS - END -->
            <?php
            }
            ?>
            <!-- PORTFOLIO PROJECT - END -->
	
		</div>
		<!-- CONTENT WRAPPER - END -->     
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- PAGE WRAPPER - END -->
	
<?php get_footer(); ?>