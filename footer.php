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

<?php
#LOAD THREADED COMMENTS
if(is_single())
{
	wp_enqueue_script('comment-reply');
}
#LOAD ORGANIC TABS
if((!is_page() || is_page('blog')) && !is_tax() && !is_singular(array('project', 'testimonial', 'article')))
{
?>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-organic-tabs.js"></script>
<?php
}
#LOAD GALLERIFIC
if(is_singular('project'))
{
?>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-galleriffic.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-history.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-opacityrollover.js"></script>
<?php
}
#LOAD ANYTHING SLIDER
if(is_front_page())
{
?>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-anythingslider-min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-anythingslider-fx-min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-anythingslider-video-min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-easing1.2.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-jcarousel-lite-min.js"></script>
<?php
}
?>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-hover-intent.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-superfish.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-supersubs.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-colorbox-min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery-tinynav-min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/mp-module.php"></script>

</body>

</html>