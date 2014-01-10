<?php
#Template Name: Testimonial Template

get_header();
?>

            <!-- TESTIMONIAL - START -->
            <?php
            #TESTIMONIAL EXISTS
            if(have_posts())
            {
                the_post();
                ?>		
                <h1 class="page-title"><?php the_title(); ?></h1>
                <?php mp_options::mp_display_testimonial(); ?>
                <?php
            }
            ?>
            <!-- TESTIMONIAL - END -->
	
		</div>
		<!-- CONTENT WRAPPER - END -->     
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- PAGE WRAPPER - END -->
	
<?php get_footer(); ?>