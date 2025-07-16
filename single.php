<?php

/**
 * 单篇文章模板
 */
get_header();
?>

<div>
    <?php while (have_posts()):
        the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-post-article'); ?>>
            <header>
                <h1><?php the_title(); ?></h1>
                <div>
                    <time datetime="<?php echo get_the_date('c'); ?>">
                        <?php echo get_the_date(); ?>
                    </time>
                    <span>•</span>
                    <span><?php the_author(); ?></span>
                    <?php if (has_category()): ?>
                        <span>•</span>
                        <span><?php the_category(', '); ?></span>
                    <?php endif; ?>
                </div>
            </header>

            <?php if (has_post_thumbnail()): ?>
                <div>
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>

            <div>
                <?php the_content(); ?>
            </div>

            <?php if (has_tag()): ?>
                <footer>
                    <div>
                        <span><?php _e('标签：', 'zeroy'); ?></span>
                        <?php the_tags('<div>', '', '</div>'); ?>
                    </div>
                </footer>
            <?php endif; ?>
        </article>

        <?php if (comments_open() || get_comments_number()): ?>
            <div>
                <?php comments_template(); ?>
            </div>
        <?php endif; ?>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>