<!-- FOOTER - ROW 1 - START -->
<footer id="footer-row1">

	<!-- FOOTER - ROW 1 - WRAPPER - START -->
	<div class="footer-wrapper wrapper">
	
		<!-- ABOUT - START -->
		<div id="footer-column1" class="footer-column col4">
		
        	<div class="footer-column-wrapper">
            
            	<h5>About <?php the_author_meta('first_name', mp_options::mp_get_author_id()); ?></h5>
				<?php echo wpautop(get_the_author_meta('user_description', mp_options::mp_get_author_id())); ?>
            
            </div>		
			
		</div>
		<!-- ABOUT - END -->
		
		<!-- TWITTER/DRIBBBLE - START -->
		<div id="footer-column2" class="footer-column col4">
		
        	<div class="footer-column-wrapper">
        
				<?php mp_options::mp_display_dribbble_or_twitter(); ?>
            
            </div>
			
		</div>
		<!-- TWITTER/DRIBBBLE - END -->
		
		<!-- INSTAGRAM - START -->
		<div id="footer-column3" class="footer-column col4">
		
        	<div class="footer-column-wrapper">
            
                <h5>Instagram</h5>
                <?php mp_options::mp_display_instagram_thumbnails(); ?>
            
            </div>
			
		</div>
		<!-- INSTAGRAM - END -->
	
	</div>
	<!-- FOOTER - ROW 1 - WRAPPER - END -->

</footer>
<!-- FOOTER - ROW 1 - END -->

<!-- FOOTER - ROW 2 - START -->
<footer id="footer-row2">

	<!-- FOOTER - ROW 2 - WRAPPER - START -->
	<div class="footer-wrapper wrapper"><?php wp_nav_menu(array('theme_location' => 'menu_footer', 'container' => 'none', 'menu_class' => '',  'fallback_cb' => '')); ?></div>
	<!-- FOOTER - ROW 2 - WRAPPER - END -->

</footer>
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
<script src="<?php bloginfo('template_directory'); ?>/js/vendor/min/jquery-organic-tabs.min.js"></script>
<?php
}
?>
<script src="<?php bloginfo('template_directory'); ?>/js/vendor/min/jquery-hover-intent.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/vendor/min/jquery-superfish.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/vendor/min/jquery-supersubs.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/vendor/min/jquery-meanmenu.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/vendor/min/jquery-colorbox.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/vendor/min/jquery-flexslider.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/vendor/min/jquery-easing.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/vendor/min/jquery-fitvid.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/vendor/min/jquery-mousewheel.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/modules/min/mp-module.min.js"></script>

</body>

</html>