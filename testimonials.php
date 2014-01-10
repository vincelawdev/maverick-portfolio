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
                <h1 class="page-title"><?php the_title(); ?></h1>
                <?php mp_options::mp_display_testimonials('testimonials', mp_options::mp_get_page()); ?>
            <?php
            }
            ?>
            <!-- TESTIMONIALS - END -->
	
		</div>
		<!-- CONTENT WRAPPER - END -->     
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- PAGE WRAPPER - END -->
	
<?php get_footer(); ?>