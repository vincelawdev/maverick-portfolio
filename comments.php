<!-- COMMENTS, TRACKBACKS, COMMENT FORM - START -->
<a name="comments"></a>
	
<?php
#COMMENTS AND/OR TRACKBACKS EXIST
if($comments)
{
	#INITIALISE COMMENT & TRACKBACK STATUS
	$comment_status = false;
	$trackback_status = false;
		
	#INITIALISE COMMENTS COUNTER
	$comments_counter = 0;
	
	#INITIALISE TRACKBACK COUNTER
	$trackback_counter = 0;

	#DETERMINE COMMENT & TRACKBACK STATUS
	foreach($comments as $comment)
	{
		#RETRIEVE COMMENT TYPE
		$comment_type = get_comment_type();
	
		#COMMENT TYPE IS COMMENT
		if($comment_type == "comment")
		{
			#COMMENTS EXIST
			$comment_status = true;
			
			#INCREMENT COMMENTS COUNTER
			$comments_counter ++;
		}
		#COMMENT TYPE IS TRACKBACK
		else
		{
			#TRACKBACKS EXIST
			$trackback_status = true;
			
			#INCREMENT TRACKBACK COUNTER
			$trackback_counter ++;
		}
	}
}

#COMMENTS ENABLED
if(comments_open())
{
	#POST REQUIRES PASSWORD
	if(!empty($post->post_password) && $_COOKIE["wp-postpass_" . COOKIEHASH] != $post->post_password)
	{
	?>
		<div class="post_body"><p>Enter your password to view comments.</p></div>
		<?php
		return;
	}
		
	#DISPLAY COMMENTS
	if($comment_status)
	{
	?>
		<!-- COMMENTS - START -->
		<h3 class="comment_title"><?php if($comments_counter == 1) { echo "$comments_counter Comment"; } else { echo "$comments_counter Comments";  } ?> On &#8220;<?php the_title(); ?>&#8221;</h3>
		<div class="comment_body">
		<?php		
		#DISPLAY COMMENTS
		foreach($comments as $comment)
		{
			#RETRIEVE COMMENT TYPE
			$comment_type = get_comment_type();
			
			#COMMENT TYPE IS COMMENT
			if($comment_type == "comment")
			{
				#INITIALISE GRAVATAR DEFAULT AVATAR & AVATAR HASH			
				$gravatar_default = urlencode(get_bloginfo("template_directory") . "/images/icon-avatar.png");
				$gravatar_hash = md5(strtolower(trim(get_comment_author_email())));
				
				#INITIALISE AUTHOR ENTRIES
				if($comment->comment_author_email == get_the_author_email())
				{
					$comment_class = "author_entry";
				}
				#INITIALISE NON-AUTHOR ENTRIES
				else
				{
					$comment_class = "comment_entry";
				}
			?>
			
			<!-- COMMENT <?php comment_ID(); ?> - START -->
			<div id="comment<?php comment_ID(); ?>" class="comment_box">
				
				<!-- COMMENT AVATAR, AUTHOR & DATE - START -->
				<div class="comment_info">
					<p class="comment_author_avatar"><img src="http://www.gravatar.com/avatar/<?php echo $gravatar_hash; ?>?s=80&r=g&d=<?php echo $gravatar_default; ?>" alt="<?php comment_author(); ?>" title="<?php comment_author(); ?>" width="100" height="100" /></p>
					<p class="comment_author"><?php comment_author_link(); ?></p>
					<p class="comment_date"><?php comment_date("j F Y"); ?></p>
				</div>
				<!-- COMMENT AVATAR, AUTHOR & DATE - END -->				
			
				<!-- COMMENT TEXT - START -->
				<div class="<?php echo $comment_class; ?>"><a name="comment<?php comment_ID(); ?>"></a><?php comment_text(); ?></div>
				<!-- COMMENT TEXT - END -->
				
			</div>
			<!-- COMMENT <?php comment_ID(); ?> - END -->
			
			<?php
			}
		}
		?>
		</div>
		<!-- COMMENTS - END -->
	<?php
	}
}

#TRACKBACKS ENABLED
if(pings_open())
{	
	#TRACKBACKS EXIST
	if($trackback_status)
	{
	?>
		<!-- TRACKBACKS - START -->
		<h3 class="comment_title"><?php if($trackback_counter == 1) { echo "$trackback_counter Trackback"; } else { echo "$trackback_counter Trackbacks";  } ?> On &#8220;<?php the_title(); ?>&#8221;</h3>
		<div class="comment_body">
			<ol>
			<?php
			#DISPLAY COMMENTS
			foreach($comments as $comment)
			{
				#RETRIEVE COMMENT TYPE
				$comment_type = get_comment_type();
				
				#COMMENT TYPE IS TRACKBACK
				if($comment_type != "comment")
				{
				?>
				<li><?php comment_author_link(); ?></li>
				<?php
				}
			}		
			?>
			</ol>
		</div>
		<!-- TRACKBACKS - END -->
	<?php
	}
}
#COMMENTS ENABLED
if(comments_open())
{
?>
	<!-- COMMENT FORM - START -->
	<h3 class="comment_title">Post a Comment</h3>
	
	<form action="<?php echo get_settings("siteurl"); ?>/wp-comments-post.php" method="post" id="comment_form">
		<input type="hidden" name="comment_post_ID" value="<?php echo $post->ID; ?>" />
		<input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" />
		<?php
		#USER NOT LOGGED IN
		if(!$user_ID)
		{
		?>	
		<p>&nbsp;&nbsp;<label for="author">Name: (Required)</label><br /><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="40" /></p>
		<p>&nbsp;&nbsp;<label for="email">Email: (Required)</label><br /><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="40" /></p>
		<p>&nbsp;&nbsp;<label for="url">URL:</label><br /><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="40" /></p>
		<?php
		}
		?>
		<p>&nbsp;&nbsp;<label for="comment">Comments: (Required)</label><br /><textarea rows="8" cols="20" name="comment" id="comment"></textarea></p>
		<?php do_action("comment_form", $post->ID); ?>
		<p><input name="submit" id="submit" type="image" value="Submit" src="<?php bloginfo("template_directory"); ?>/images/button-submit.png" /></p>
	</form>
	<!-- COMMENT FORM - END -->
<?php
}
?>
<!-- COMMENTS, TRACKBACKS, COMMENT FORM - END -->
