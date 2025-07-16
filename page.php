<?php

/**
 * 页面模板
 */
get_header();
?>

<div>
    <?php while (have_posts()):
        the_post(); ?>
        <article id="page-<?php the_ID(); ?>" <?php post_class(''); ?>>
            <div>
                <?php the_content(); ?>
            </div>
        </article>

        <?php if (comments_open() || get_comments_number()): ?>
            <div>
                <?php comments_template(); ?>
            </div>
        <?php endif; ?>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>