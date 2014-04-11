<?php
#Template Name: Portfolio Template

get_header();
?>
		
            <!-- PORTFOLIO - START -->
            <?php
            #PORTFOLIO EXISTS
            if(have_posts())
            {
                the_post();
                ?>
                <h1 class="page-title"><?php the_title(); ?></h1>
                <div class="page-content"><?php the_content(); ?></div>
                <?php mp_options::mp_display_projects('', mp_options::mp_get_page(), true, 'projects', '', '', '', true, 15); ?>
            <?php
            }
            ?>
            <!-- PORTFOLIO - END -->
	
		</div>
		<!-- CONTENT WRAPPER - END -->     
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- PAGE WRAPPER - END -->
	
<?php get_footer(); ?>