<?php
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
?>