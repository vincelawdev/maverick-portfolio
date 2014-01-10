<?php
#Template Name: Page with Comments Template

get_header();
?>
	
            <!-- PAGE - START -->
            <?php
            #PAGE EXISTS
            if(have_posts())
            {
                the_post();
                ?>		
                <h1 class="page-title"><?php the_title(); ?></h1>
                <div class="page-content"><?php the_content(); ?></div>
                <?php comments_template(); ?>
                <?php
            }
            ?>
            <!-- PAGE - END -->
	
		</div>
		<!-- CONTENT WRAPPER - END -->     
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- PAGE WRAPPER - END -->
	
<?php get_footer(); ?>