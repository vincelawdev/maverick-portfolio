<?php
#Template Name: Articles Template

get_header();
?>
	
		<!-- ARTICLES - START -->
		<?php
		#ARTICLES EXISTS
		if(have_posts())
		{
			the_post();
			?>
			<h1 class="page_title"><?php the_title(); ?></h1>
			<div class="page_content"><?php the_content(); ?></div>
			<?php mp_options::mp_display_articles('', mp_options::mp_get_page()); ?>
		<?php
		}
		?>
		<!-- ARTICLES - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>