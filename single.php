<?php 
#Template Name: Blog Post Template

get_header();
?>

		<!-- POST - START -->
		<?php
		#POST EXISTS
		if(have_posts())
		{			
			#DISPLAY POST
			while(have_posts())
			{
				the_post();
				?>
				<h1 class="post_title"><?php the_title(); ?></h1>
				<p class="post_info">Posted on <?php the_date(); ?> by <?php the_author(); ?> in <?php the_category(", ");?> | <a href="#comments"><?php mp_options::mp_display_comment_counter(get_the_ID(), "comment", "0 Comments", "1 Comment", "Comments"); ?></a></p>
				<?php include(TEMPLATEPATH . "/includes/inc-blog-post-social.php"); ?>
				<?php
				#DISPLAY POST THUMBNAIL
				if(has_post_thumbnail())
				{
					the_post_thumbnail("medium");
				}
				
				#DISPLAY POST
				the_content();
				?>
									
				<!-- SIMILAR POSTS - START -->
				<?php
				#DISPLAY SIMILAR POSTS
				if(function_exists("similar_posts"))
				{
				?>
				<h3 class="sub_heading">Similar Posts</h3>
				<?php similar_posts(); ?>
				<?php
				}
				?>
				<!-- SIMILAR POSTS - END -->
				
				<?php comments_template();
			}
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
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>