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

                #PORTFOLIO PROJECT IS PASSWORD PROTECTED
                if(post_password_required())
                {
                    the_content();
                }
                #PORTFOLIO PROJECT IS NOT PASSWORD PROTECTED
                else
                {
                ?>
                <h1 class="page-title"><?php the_title(); ?></h1>

                <?php
                #PORTFOLIO PROJECT HAS PROJECT IMAGES
                if(mp_options::mp_has_project_images())
                {
                ?>
                <!-- FLEXSLIDER - START -->
                <div class="project-slides flexslider"><?php mp_options::mp_display_project_images('image', true, 'image', '', ''); ?></div>
                <!-- FLEXSLIDER - END -->
                
                <!-- FLEXSLIDER CAROUSEL - START -->
                <div class="project-slides-carousel flexslider"><?php mp_options::mp_display_project_images('thumbnail', false, '', 'project-slides-carousel-item', 'project-slides-carousel-item-wrapper'); ?></div>
                <!-- FLEXSLIDER CAROUSEL - END -->
                <?php
                }
                ?>
                
                <!-- PROJECT DETAILS - START -->
                <div class="page-content"><?php mp_options::mp_display_project_details(); the_content(); ?></div>
                <!-- PROJECT DETAILS - END -->
                
                <!-- PROJECT TESTIMONIALS - START -->
                <?php mp_options::mp_display_testimonials('project', mp_options::mp_get_page()); ?>
                <!-- PROJECT TESTIMONIALS - END -->
                <?php
                }
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