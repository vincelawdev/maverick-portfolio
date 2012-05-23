<?php
#Template Name: Article Directory Template

get_header();
?>

		<!-- ARTICLE DIRECTORY - START -->
		<?php
		#ARTICLE DIRECTORY EXISTS
		if(have_posts())
		{
			the_post();
			?>		
			<h1 class="page_title"><?php $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy')); echo $term->name; ?></h1>
			<?php mp_options::mp_display_articles(get_query_var('term'), mp_options::mp_get_page()); ?>
		<?php
		}
		?>
		<!-- ARTICLE DIRECTORY - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>