<?php
#INITIALISE CLASS OBJECT
$mp_options = new mp_options();
?>

<!-- FOOTER - ROW 1 - START -->
<div id="footer_row1">

	<!-- FOOTER - ROW 1 - WRAPPER - START -->
	<div class="footer_wrapper">
	
		<!-- ABOUT - START -->
		<div id="footer_column1">
		
			<h5>About <?php the_author_meta("first_name", $mp_author); ?></h5>			
			<?php echo wpautop(get_the_author_meta("user_description", $mp_author)); ?>
			
		</div>
		<!-- ABOUT - END -->
		
		<!-- DRIBBBLE - START -->
		<div id="footer_column2">
		
			<h5>Dribbble</h5>
			<?php mp_options::display_dribbble_thumbnails(); ?>
			
		</div>
		<!-- DRIBBBLE - END -->
		
		<!-- INSTAGRAM - START -->
		<div id="footer_column3">
		
			<h5>Instagram</h5>
			<?php mp_options::display_instagram_thumbnails(); ?>
			
		</div>
		<!-- INSTAGRAM - END -->
	
	</div>
	<!-- FOOTER - ROW 1 - WRAPPER - END -->

</div>
<!-- FOOTER - ROW 1 - END -->

<!-- FOOTER - ROW 2 - START -->
<div id="footer_row2">

	<!-- FOOTER - ROW 2 - WRAPPER - START -->
	<div class="footer_wrapper">
	
		<?php wp_nav_menu(array("theme_location" => "menu_footer", "container" => "none", "menu_class" => "",  "fallback_cb" => "")); ?><a href="<?php bloginfo("rss2_url"); ?>" rel="nofollow"><img src="<?php bloginfo("template_directory"); ?>/images/icon-rss.png" id="footer_rss" alt="RSS Feed" /></a>
	
	</div>
	<!-- FOOTER - ROW 2 - WRAPPER - END -->

</div>
<!-- FOOTER - ROW 2 - END -->

<?php wp_footer(); ?>

<!-- SOURCE CODE - START -->
<div id="source-code"><a href="#" id="x"><img src="<?php bloginfo("template_directory"); ?>/images/button-close.png" alt="Close" /></a></div>
<!-- SOURCE CODE - END -->

</body>

</html>