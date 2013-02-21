<?php get_header(); ?>

		<!-- INDEX - START -->
		<?php
		#SEARCH
		if(is_search())
		{
			mp_options::mp_display_blog_posts(mp_options::mp_get_page(), '', trim($_REQUEST['s']));
		}
		#CATEGORY
		elseif(is_category())
		{
		?>
		<h1 class="page_title"><?php echo single_cat_title(); ?></h1>
		<?php mp_options::mp_display_blog_posts(mp_options::mp_get_page(), get_query_var('cat')); ?>
		<?php
		}
		#MONTHLY ARCHIVE
		elseif(is_month())
		{
		?>
		<h1 class="page_title">Archive for <?php the_time('F Y'); ?></h1>
		<?php mp_options::mp_display_blog_posts(mp_options::mp_get_page(), '', '', get_query_var('year'), get_query_var('monthnum')); ?>
		<?php
		}
		#AUTHOR
		elseif(is_author())
		{
			#INITIALISE AUTHOR OBJECT
			$author = get_userdata(get_query_var('author'));
		?>
		<h1 class="page_title">Archive for <?php echo $author->display_name; ?></h1>
		<?php mp_options::mp_display_blog_posts(mp_options::mp_get_page(), '', '', '', '', $author->ID); ?>
		<?php
		}
		?>
		<!-- INDEX - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>