<?php
#Template Name: Page Template

get_header();
?>
	
		<!-- PAGE - START -->
		<?php
		#PAGE EXISTS
		if(have_posts())
		{
			#DISPLAY PAGE
			while(have_posts())
			{
				the_post();
				?>		
				<h1 class="page_title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
			<?php
			}
		}
		?>
		<!-- PAGE - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>