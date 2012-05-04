<h3 class="post_title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post_title_link"><?php the_title(); ?></a></h3>
<p class="post_info">Posted on <?php the_time(get_option("date_format") . " " . get_option("time_format")); ?> by <?php the_author(); ?> in <?php the_category(", ");?> | <a href="<?php the_permalink(); ?>#comments"><?php mp_options::mp_display_comment_counter(get_the_ID(), "comment", "0 Comments", "1 Comment", "Comments"); ?></a></p>
<?php include(TEMPLATEPATH . "/includes/inc-blog-post-social.php"); ?>
<div class="post_line">
<?php
#DISPLAY POST THUMBNAIL
if(has_post_thumbnail())
{
	echo '<a href="' . get_permalink() . '" title="' . get_the_title() . '" class="post_thumbnail">' . get_the_post_thumbnail($post->ID, "thumbnail") . '</a>';
}

#DISPLAY EXCERPT VIA THE ADVANCED EXCERPT PLUGIN
if(function_exists("the_advanced_excerpt"))
{
	the_excerpt();
}
#DISPLAY EXCERPT VIA THE CLASS FUNCTION
else
{
	mp_options::mp_display_blog_post_excerpt(100);
}
?>
</div>