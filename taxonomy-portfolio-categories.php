<?php
#Template Name: Portfolio Category Template

get_header();
?>

		<!-- PORTFOLIO CATEGORY - START -->
		<?php
		#PORTFOLIO CATEGORY EXISTS
		if(have_posts())
		{
			#DISPLAY PORTFOLIO CATEGORY
			while(have_posts())
			{
				the_post();
				?>		
				<h1 class="page_title"><?php the_title(); ?></h1>
				<?php mp_options::mp_display_projects(get_query_var("term"), mp_options::mp_get_page()); ?>
			<?php
			}
		}
		?>
		<!-- PORTFOLIO CATEGORY - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>