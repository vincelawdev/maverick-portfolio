<!-- FOOTER - ROW 1 - START -->
<div id="footer_row1">

	<!-- FOOTER - ROW 1 - WRAPPER - START -->
	<div class="footer_wrapper">
	
		<!-- ABOUT - START -->
		<div id="footer_column1">
		
			<h5>About <?php the_author_meta('first_name', mp_options::mp_get_author_id()); ?></h5>
			<?php echo wpautop(get_the_author_meta('user_description', mp_options::mp_get_author_id())); ?>
			
		</div>
		<!-- ABOUT - END -->
		
		<!-- TWITTER/DRIBBBLE - START -->
		<div id="footer_column2">
		
			<?php mp_options::mp_display_dribbble_or_twitter(); ?>
			
		</div>
		<!-- TWITTER/DRIBBBLE - END -->
		
		<!-- INSTAGRAM - START -->
		<div id="footer_column3">
		
			<h5>Instagram</h5>
			<?php mp_options::mp_display_instagram_thumbnails(); ?>
			
		</div>
		<!-- INSTAGRAM - END -->
	
	</div>
	<!-- FOOTER - ROW 1 - WRAPPER - END -->

</div>
<!-- FOOTER - ROW 1 - END -->

<!-- FOOTER - ROW 2 - START -->
<div id="footer_row2">

	<!-- FOOTER - ROW 2 - WRAPPER - START -->
	<div class="footer_wrapper"><?php wp_nav_menu(array('theme_location' => 'menu_footer', 'container' => 'none', 'menu_class' => '',  'fallback_cb' => '')); ?></div>
	<!-- FOOTER - ROW 2 - WRAPPER - END -->

</div>
<!-- FOOTER - ROW 2 - END -->

<?php wp_footer(); ?>

</body>

</html>