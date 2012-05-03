<!-- COMMENTS, TRACKBACKS, PINGBACKS & COMMENT FORM - START -->
<a name="comments"></a>
	
<?php
#INVALID REQUEST
if(!empty($_SERVER["SCRIPT_FILENAME"]) && basename($_SERVER["SCRIPT_FILENAME"]) == "comments.php")
{
	die("Please do not load this page directly.");
}

#COMMENTS ENABLED
if(comments_open())
{
	#POST REQUIRES PASSWORD
	if(post_password_required())
	{
	?>
		<p>This post is password protected. Enter the password to view comments.</p>
		<?php
		return;
	}
		
	#COMMENTS EXIST
	if(mp_options::mp_get_comment_type_count(get_the_ID(), "comment") > 0)
	{
	?>
		<!-- COMMENTS - START -->
		<h3 class="sub_heading"><?php mp_options::mp_display_comment_counter(get_the_ID(), "comment", "0 Comments", "1 Comment", "Comments"); ?> On &#8220;<?php the_title(); ?>&#8221; <span class="rss"><a href="<?php echo get_post_comments_feed_link(); ?>" rel="nofollow"><img src="<?php echo get_bloginfo("template_directory"); ?>/images/icon-rss-small.png" alt="Subscribe to Comments via RSS" title="Subscribe to Comments via RSS" /></a></span></h3>
		<p>Trackback URL: <small><?php trackback_url(); ?></small></p>
		<ul class="comments"><?php wp_list_comments("style=ul&type=comment&callback=mp_options::mp_display_comment_list"); ?></ul>
		<!-- COMMENTS - END -->
	<?php
	}
	
	#DISPLAY WP-COMMENTNAVI PAGING NAVIGATION LINKS
	if(function_exists("wp_commentnavi"))
	{
		wp_commentnavi();
	} 
	#DISPLAY DEFAULT WORDPRESS PAGING NAVIGATION LINKS
	else
	{
		#COMMENT PAGING ENABLED
		if(get_comment_pages_count() > 1 && get_option("page_comments"))
		{
		?>
			<p class="left"><?php previous_comments_link("&laquo; Older Comments"); ?></p>
			<p class="right"><?php next_comments_link("Newer Comments &raquo;"); ?></p>
		<?php
		}
	}
}

#TRACKBACKS ENABLED
if(pings_open())
{	
	#TRACKBACKS EXIST
	if(mp_options::mp_get_comment_type_count(get_the_ID(), "trackback") > 0)
	{
	?>
		<!-- TRACKBACKS - START -->
		<h3 class="sub_heading"><?php mp_options::mp_display_comment_counter(get_the_ID(), "trackback", "0 Trackbacks", "1 Trackback", "Trackbacks"); ?> On &#8220;<?php the_title(); ?>&#8221;</h3>
		<ol class="pings"><?php wp_list_comments("type=trackback&callback=mp_options::mp_display_ping_list"); ?></ol>
		<!-- TRACKBACKS - END -->
	<?php
	}
	
	#PINGBACKS EXIST
	if(mp_options::mp_get_comment_type_count(get_the_ID(), "pingback") > 0)
	{
	?>
		<!-- PINGBACKS - START -->
		<h3 class="sub_heading"><?php mp_options::mp_display_comment_counter(get_the_ID(), "pingback", "0 Pingbacks", "1 Pingback", "Pingbacks"); ?> On &#8220;<?php the_title(); ?>&#8221;</h3>
		<ol class="pings"><?php wp_list_comments("type=pingback&callback=mp_options::mp_display_ping_list"); ?></ol>
		<!-- PINGBACKS - END -->
	<?php
	}
}

#COMMENTS ENABLED
if(comments_open())
{
?>
	<!-- COMMENT FORM - START -->
	<div id="respond">
	
		<h3 class="sub_heading"><?php comment_form_title("Post a Comment", "Post a Reply to %s / "); cancel_comment_reply_link("Cancel Reply"); ?></h3>
		
		<form action="<?php echo get_settings("siteurl"); ?>/wp-comments-post.php" method="post" id="comment_form">
			<?php comment_id_fields(); ?>
			<?php do_action("comment_form", $post->ID); ?>
			<input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" />
			<?php
			#USER NOT LOGGED IN
			if(!$user_ID)
			{
			?>	
			<p><label for="author">Name: (Required)</label><br /><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="40" /></p>
			<p><label for="email">Email: (Required)</label><br /><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="40" /></p>
			<p><label for="url">URL:</label><br /><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="40" /></p>
			<?php
			}
			?>
			<p><label for="comment">Comments: (Required)</label><br /><textarea rows="8" cols="20" name="comment" id="comment"></textarea></p>
			<p><input name="submit" type="submit" value="Submit" /></p>
		</form>
		
	</div>
	<!-- COMMENT FORM - END -->
<?php
}
?>
<!-- COMMENTS, TRACKBACKS, PINGBACKS & COMMENT FORM - END -->
