<?php 
#Template Name: Blog Post Template

get_header();
?>

            <!-- POST - START -->
            <?php
            #POST EXISTS
            if(have_posts())
            {
                the_post();
                ?>
                <h1 class="post-title"><?php the_title(); ?></h1>
                <p class="post-info">Posted on <?php the_date(); ?> <?php the_time(); ?> by <?php the_author(); ?> in <?php the_category(', ');?> | <a href="#comments"><?php mp_options::mp_display_comment_counter(get_the_ID(), 'comment', '0 Comments', '1 Comment', 'Comments'); ?></a></p>
                <?php include(TEMPLATEPATH . '/includes/inc-blog-post-social.php'); ?>
                <div class="post-content"><?php the_content(); ?></div>
                                    
                <!-- SIMILAR POSTS - START -->
                <?php
                #DISPLAY SIMILAR POSTS
                if(function_exists('similar_posts'))
                {
                ?>
                <h3 class="sub-heading">Similar Posts</h3>
                <?php similar_posts(); ?>
                <?php
                }
                ?>
                <!-- SIMILAR POSTS - END -->
                
                <?php comments_template(); ?>
            <?php
            }
            #NO POST EXIST
            else
            {
            ?>
            <p>Sorry, no posts matched your criteria.</p>
            <?php
            }
            ?>
            <!-- POST - END -->
	
		</div>
		<!-- CONTENT WRAPPER - END -->     
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- PAGE WRAPPER - END -->
	
<?php get_footer(); ?>