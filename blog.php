<?php
#Template Name: Blog Index Page Template

get_header();
?>

            <!-- BLOG POSTS - START -->
            <?php
            #BLOG POSTS EXISTS
            if(have_posts())
            {
                the_post();
                ?>
                <h1 class="page-title"><?php the_title(); ?></h1>
                <div class="page-content"><?php the_content(); ?></div>
                <?php mp_options::mp_display_blog_posts(mp_options::mp_get_page()); ?>
            <?php
            }
            ?>
            <!-- BLOG POSTS - END -->
	
		</div>
		<!-- CONTENT WRAPPER - END -->     
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- PAGE WRAPPER - END -->
	
<?php get_footer(); ?>