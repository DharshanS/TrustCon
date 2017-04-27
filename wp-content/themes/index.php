<?php get_header(); ?>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
Posted on: <?php the_date(); ?> at <?php the_time(); ?>
Posted in: <?php the_category(','); ?>
<?php the_content('Read More..'); ?>
Posted by: <?php the_author(); ?> | <?php comments_popup_link('No
Comments', '1 Comment', '% Comments'); ?>
</div>
<?php endwhile; ?>
<?php next_posts_link('&laquo; Previous Entries') ?>
<?php previous_posts_link('Next Entries &raquo;') ?>
<?php else : ?>
Not Found
Sorry, but you are looking for something that isn’t here.
<?php endif; ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>