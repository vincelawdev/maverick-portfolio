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
				<p class="post_info">Posted on <?php the_date(); ?> by <?php the_author(); ?> in <?php the_category(", ");?> | <a href="#comments"><?php comments_number(__("0 Comments"), __("1 Comment"), __("% Comments")); ?></a></p>
				<div class="addthis_toolbox addthis_default_style" addthis:url="<?php the_permalink(); ?>" addthis:title="<?php the_title(); ?>">
					<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
					<a class="addthis_button_tweet"></a>
					<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
					<a class="addthis_counter addthis_pill_style"></a>
				</div>		
				<?php the_content(); ?>
									
				<!-- SIMILAR POSTS - START -->
				<?php
				#DISPLAY SIMILAR POSTS
				if(function_exists("similar_posts"))
				{
				?>
				<h3 class="comment_title">Similar Posts</h3>
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