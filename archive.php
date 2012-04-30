<?php


get_header();
?>

		<!-- ARCHIVES - START -->
		<?php
		#RETRIEVE POSTS
		query_posts($query_string . "&cat=");
	
		#POSTS EXIST
		if(have_posts())
		{
			#CATEGORY ARCHIVE
			if(is_category())
			{
			?>
			<h1 class="post_title_underline">Archive for the &quot;<?php echo single_cat_title(); ?>&quot; Category</h1>
			<?php
			}
			#TAG ARCHIVE
			elseif(is_tag())
			{
			?>
			<h1 class="post_title_underline">Archive for the &quot;<?php echo single_tag_title(); ?>&quot; Tag</h1>
			<?php
			}
			#DAILY ARCHIVE
			elseif(is_day())
			{
			?>
			<h1 class="post_title_underline">Archive for <?php the_time("jS F Y"); ?></h1>
			<?php
			}
			#MONTHLY ARCHIVE
			elseif(is_month())
			{
			?>
			<h1 class="post_title_underline">Archive for <?php the_time("F Y"); ?></h1>
			<?php
			}
			#YEARLY ARCHIVE
			elseif(is_year())
			{
			?>
			<h1 class="post_title_underline">Archive for <?php the_time("Y"); ?></h1>
			<?php
			}
			#AUTHOR ARCHIVES
			elseif(is_author())
			{
				#INITIALISE AUTHOR OBJECT
				$author = get_userdata(get_query_var("author"));			
			?>
			<h1 class="post_title_underline">Archive for <?php echo $author->display_name; ?></h1>
			<?php
			#PAGED ARCHIVE
			}
			elseif(isset($_GET["paged"]) && !empty($_GET["paged"]))
			{
			?>
			<h1 class="post_title">Blog Archives</h1>
			<?php
			}
			
			#DISPLAY POSTS
			while(have_posts())
			{
				the_post();
				?>		
					<h3 class="post_title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post_title_link"><?php the_title(); ?></a></h3>
					<p class="post_info">Posted on <?php the_date(); ?> by <?php the_author(); ?> in <?php the_category(", ");?> | <a href="<?php the_permalink(); ?>#comments"><?php mp_options::display_comment_counter(get_the_ID(), "comment", "0 Comments", "1 Comment", "Comments"); ?></a></p>			
					<?php include(TEMPLATEPATH . "/includes/inc-blog-social.php"); ?>
					<div class="post_line">
					<?php
					#DISPLAY POST THUMBNAIL
					if(has_post_thumbnail())
					{
						echo '<a href="' . get_permalink() . '" title="' . get_the_title() . '" class="post_thumbnail">' . get_the_post_thumbnail($blog_posts->ID, "thumbnail") . '</a>';
					}
					
					#DISPLAY POST EXCERPT
					the_excerpt();
					?>
					</div>
				<?php
			}

			#DISPLAY WP-PAGENAVI PAGING NAVIGATION LINKS
			if(function_exists("wp_pagenavi"))
			{
				wp_pagenavi();
			}
			#DISPLAY DEFAULT WORDPRESS PAGING NAVIGATION LINKS
			else
			{
			?>
				<p class="left"><?php next_posts_link("&laquo; Previous Entries"); ?></p>
				<p class="right"><?php previous_posts_link("Next Entries &raquo;"); ?></p>
			<?php
			}
		}
		#NO POSTS EXIST
		else
		{
		?>		
		<p>Sorry, no posts matched your criteria.</p>
		<?php
		}
		?>
		<!-- ARCHIVES - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>