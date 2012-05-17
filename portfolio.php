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
			<h1 class="page_title"><?php the_title(); ?></h1>
			<?php the_content(); ?>
			<?php mp_options::mp_display_projects("", mp_options::mp_get_page()); ?>
		<?php
		}
		?>
		<!-- PORTFOLIO - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>