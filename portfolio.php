<?php
#Template Name: Portfolio Template

get_header();
?>
		
		<!-- PORTFOLIO - START -->
		<?php
		#PORTFOLIO EXISTS
		if(have_posts())
		{
			#DISPLAY PORTFOLIO
			while(have_posts())
			{
				the_post();
				?>		
				<h1 class="page_title"><?php the_title(); ?></h1>
				<?php mp_options::mp_display_projects("", get_query_var("paged")); ?>
			<?php
			}
		}
		?>
		<!-- PORTFOLIO - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>