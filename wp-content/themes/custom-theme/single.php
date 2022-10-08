<?php

use repository\PostRepository;

/**
 * @var \entity\Post $wp_post
 */
$wp_post = PostRepository::find( get_the_ID() );
get_header();
?>
<section>
	<?php echo $wp_post->content; ?>
</section>
<?php get_footer();