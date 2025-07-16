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

<style>
    .front-page-container {
        padding: 3rem 1.5rem;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .front-page-articles {
        margin-bottom: 4rem;
    }
    
    .front-page-article {
        margin-bottom: 4rem;
    }
    
    .front-page-article-header {
        margin-bottom: 1rem;
    }
    
    .front-page-article-title {
        font-size: 1.875rem;
        line-height: 2.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .front-page-article-title a {
        color: #111827;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .front-page-article-title a:hover {
        color: #4B5563;
    }
    
    .front-page-article-meta {
        font-size: 0.875rem;
        line-height: 1.25rem;
        color: #6B7280;
    }
    
    .front-page-article-content {
        color: #374151;
        line-height: 1.75;
    }
    
    .front-page-article-content p {
        margin-bottom: 1rem;
    }
    
    .front-page-article-footer {
        margin-top: 1rem;
    }
    
    .front-page-article-link {
        color: #111827;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .front-page-article-link:hover {
        color: #4B5563;
    }
    
    .front-page-pagination {
        margin-top: 4rem;
        padding-top: 2rem;
        border-top: 1px solid #E5E7EB;
    }
    
    .front-page-pagination .nav-links {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }
    
    .front-page-pagination .page-numbers {
        padding: 0.5rem 1rem;
        text-decoration: none;
        color: #374151;
        border: 1px solid #E5E7EB;
        border-radius: 0.375rem;
        transition: all 0.2s;
    }
    
    .front-page-pagination .page-numbers:hover {
        background-color: #F3F4F6;
        border-color: #D1D5DB;
    }
    
    .front-page-pagination .page-numbers.current {
        background-color: #111827;
        color: white;
        border-color: #111827;
    }
    
    .front-page-pagination .page-numbers.dots {
        border: none;
        padding: 0.5rem;
    }
    
    .front-page-pagination .page-numbers.prev,
    .front-page-pagination .page-numbers.next {
        font-weight: 500;
    }
    
    .front-page-empty {
        text-align: center;
        padding: 4rem 0;
    }
    
    .front-page-empty-title {
        font-size: 1.5rem;
        line-height: 2rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1rem;
    }
    
    .front-page-empty-message {
        color: #4B5563;
    }
</style>

<div class="front-page-container">
    <?php
    // 如果 zeroY 插件没有提供模板，则使用默认内容
    if ( have_posts() ) :
        ?>
        <div class="front-page-articles">
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('front-page-article'); ?>>
                    <header class="front-page-article-header">
                        <h2 class="front-page-article-title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        <div class="front-page-article-meta">
                            <time datetime="<?php echo get_the_date('c'); ?>">
                                <?php echo get_the_date(); ?>
                            </time>
                        </div>
                    </header>

                    <div class="front-page-article-content">
                        <?php the_excerpt(); ?>
                    </div>

                    <footer class="front-page-article-footer">
                        <a href="<?php the_permalink(); ?>" class="front-page-article-link">
                            <?php _e('继续阅读', 'zeroy'); ?> →
                        </a>
                    </footer>
                </article>
            <?php endwhile; ?>
        </div>

        <nav class="front-page-pagination">
            <?php
            the_posts_pagination(array(
                'prev_text' => __('← 上一页', 'zeroy'),
                'next_text' => __('下一页 →', 'zeroy'),
                'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('页', 'zeroy') . ' </span>',
            ));
            ?>
        </nav>
    <?php else : ?>
        <div class="front-page-empty">
            <h2 class="front-page-empty-title"><?php _e('暂无内容', 'zeroy'); ?></h2>
            <p class="front-page-empty-message"><?php _e('抱歉，未找到任何内容。', 'zeroy'); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php
get_footer();