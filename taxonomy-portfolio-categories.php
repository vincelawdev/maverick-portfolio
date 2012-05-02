<?php
#INITIALISE CATEGORY OBJECT
$category = get_term_by("slug", get_query_var("term"), get_query_var("taxonomy"));

print_r($category);

#INITIALISE PAGE
if(get_query_var("paged"))
{
	$page = get_query_var("paged");
}
elseif(get_query_var("page"))
{
	$page = get_query_var("page");
}
else
{
	$page = 1;
}

get_header();
?>

		<!-- PORTFOLIO - START -->
		<?php
		#INITIALISE PROJECTS
		/*$products = $pci->pci_get_category_products($post_type, $category, $current_sub_category, $page, $brand);
		
		#PRODUCTS EXIST
		if($products->have_posts())
		{
			#DISPLAY PRODUCTS
			while($products->have_posts())
			{
				$products->the_post();
				
				#DISPLAY PRODUCT INFO
				$pci->pci_display_category_product_info($post->ID);
			}

			#DISPLAY WP-PAGENAVI PAGING NAVIGATION LINKS
			if(function_exists("wp_pagenavi"))
			{
				wp_pagenavi(array("query" => $products));
			}
			#DISPLAY DEFAULT WORDPRESS PAGING NAVIGATION LINKS
			else
			{
			?>
				<p class="left"><?php next_posts_link("&laquo; Previous"); ?></p>
				<p class="right"><?php previous_posts_link("Next &raquo;"); ?></p>
			<?php
			}
		}*/
		
		#PORTFOLIO EXISTS
		if(have_posts())
		{
			#DISPLAY PORTFOLIO
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
		<!-- PORTFOLIO - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>