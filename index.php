<?php
/**
 * 主模板文件
 */
get_header();
?>

<div>
    <?php if (have_posts()): ?>
        <?php while (have_posts()):
            the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header>
                    <h2>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>
                    <time datetime="<?php echo get_the_date('c'); ?>">
                        <?php echo get_the_date(); ?>
                    </time>
                </header>

                <div>
                    <?php the_excerpt(); ?>
                </div>

                <footer>
                    <a href="<?php the_permalink(); ?>">
                        <?php _e('继续阅读', 'zeroy'); ?> →
                    </a>
                </footer>
            </article>
        <?php endwhile; ?>

        <nav>
            <?php
            the_posts_pagination(array(
                'prev_text' => __('← 上一页', 'zeroy'),
                'next_text' => __('下一页 →', 'zeroy'),
                'before_page_number' => '<span>' . __('页', 'zeroy') . ' </span>',
            ));
            ?>
        </nav>
    <?php else: ?>
        <div>
            <h2><?php _e('暂无内容', 'zeroy'); ?></h2>
            <p><?php _e('抱歉，未找到任何内容。', 'zeroy'); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>