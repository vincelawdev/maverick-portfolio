<?php
#Template Name: Portfolio Project Template

echo "Portfolio Project Template";

#INITIALISE POST TYPE OBJECT
$post_type = get_post_type(get_the_ID());
$post_type_object = get_post_type_object($post_type);

#INITIALISE PRODUCT GALLERY
//$product_gallery = get_post_meta(get_the_ID(), "product_gallery", true);

get_header();
?>

		<!-- PORTFOLIO PROJECT - START -->
		<?php
		#PORTFOLIO PROJECT EXISTS
		if(have_posts())
		{
			#DISPLAY PORTFOLIO PROJECT
			while(have_posts())
			{
				the_post();
				?>		
				<h1 class="page_title"><?php the_title(); ?></h1>
				
			<?php
			}
		}
		?>
		<!-- PORTFOLIO PROJECT - END -->
	
	</div>
	<!-- CONTENT - END -->
	
</div>
<!-- CONTENT WRAPPER - END -->
	
<?php get_footer(); ?>