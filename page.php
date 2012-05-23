<?php
#Template Name: Page Template

get_header();
?>
	
		<!-- PAGE - START -->
		<?php
		#PAGE EXISTS
		if(have_posts())
		{
			the_post();
			?>		
			<h1 class="page_title"><?php the_title(); ?></h1>
			<div class="page_content"><?php the_content(); ?></div>
			<?php
		}
		?>
		<!-- PAGE - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>