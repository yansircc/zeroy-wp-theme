<?php
/**
 * 主模板文件
 */
get_header();
?>

<style>
/* Index Page Styles */
.container{max-width:42rem;margin:0 auto;padding:3rem 1.5rem}
article{margin-bottom:4rem;padding-bottom:4rem;border-bottom:1px solid #e5e7eb}
article:last-child{border-bottom:none}
article h2{font-size:1.875rem;margin-bottom:0.5rem}
article h2 a{color:#111827;transition:color 0.15s}
article h2 a:hover{color:#4b5563}
article time{font-size:0.875rem;color:#6b7280}
.excerpt{margin:1rem 0;color:#374151;line-height:1.75}
.read-more{font-size:0.875rem;font-weight:500;color:#111827;transition:color 0.15s}
.read-more:hover{color:#4b5563}
.pagination{margin-top:4rem;padding-top:2rem;border-top:1px solid #e5e7eb;text-align:center}
.pagination a{color:#111827;padding:0.5rem 1rem;margin:0 0.25rem;transition:all 0.15s}
.pagination a:hover{background:#f3f4f6}
.pagination .current{font-weight:700}
.no-content{text-align:center;padding:4rem 1.5rem}
.no-content h2{font-size:1.5rem;margin-bottom:1rem}
.no-content p{color:#4b5563}
</style>

<div class="container">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
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

                <div class="excerpt">
                    <?php the_excerpt(); ?>
                </div>

                <footer>
                    <a href="<?php the_permalink(); ?>" class="read-more">
                        <?php _e('继续阅读', 'zeroy'); ?> →
                    </a>
                </footer>
            </article>
        <?php endwhile; ?>

        <nav class="pagination">
            <?php
            the_posts_pagination(array(
                'prev_text' => __('← 上一页', 'zeroy'),
                'next_text' => __('下一页 →', 'zeroy'),
                'before_page_number' => '<span class="screen-reader-text">' . __('页', 'zeroy') . ' </span>',
            ));
            ?>
        </nav>
    <?php else : ?>
        <div class="no-content">
            <h2><?php _e('暂无内容', 'zeroy'); ?></h2>
            <p><?php _e('抱歉，未找到任何内容。', 'zeroy'); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>