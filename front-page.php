<?php
/**
 * 首页模板
 * 
 * @package Zeroy
 */

// 这个文件存在是为了确保 WordPress 使用 frontpage_template 过滤器
// 实际内容由 zeroY 插件的动态模板系统处理

get_header();
?>

<div>
    <?php
    // 如果 zeroY 插件没有提供模板，则使用默认内容
    if (have_posts()):
        ?>
        <div>
            <?php while (have_posts()):
                the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('front-page-article'); ?>>
                    <header>
                        <h2>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        <div>
                            <time datetime="<?php echo get_the_date('c'); ?>">
                                <?php echo get_the_date(); ?>
                            </time>
                        </div>
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
        </div>

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

<?php
get_footer();