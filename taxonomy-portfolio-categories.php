<?php
#Template Name: Portfolio Category Template

get_header();
?>

		<!-- PORTFOLIO CATEGORY - START -->
		<?php
		#PORTFOLIO CATEGORY EXISTS
		if(have_posts())
		{
			the_post();
			?>		
			<h1 class="page_title"><?php $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy')); echo $term->name; ?></h1>
			<?php mp_options::mp_display_projects(get_query_var('term'), mp_options::mp_get_page(), true, 'projects', true, 15); ?>
		<?php
		}
		?>
		<!-- PORTFOLIO CATEGORY - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>