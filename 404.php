<?php

/**
 * 404 错误页面
 */
get_header();
?>

<style>
    .error-404-container {
        padding: 6rem 1.5rem;
    }
    
    .error-404-inner {
        text-align: center;
        max-width: 42rem;
        margin: 0 auto;
    }
    
    .error-404-code {
        font-size: 5rem;
        line-height: 1;
        font-weight: 700;
        color: #E5E7EB;
        margin-bottom: 1rem;
    }
    
    @media (min-width: 768px) {
        .error-404-code {
            font-size: 8rem;
        }
    }
    
    .error-404-title {
        font-size: 1.5rem;
        line-height: 2rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1rem;
    }
    
    @media (min-width: 768px) {
        .error-404-title {
            font-size: 1.875rem;
            line-height: 2.25rem;
        }
    }
    
    .error-404-message {
        color: #4B5563;
        margin-bottom: 2rem;
    }
    
    .error-404-link {
        display: inline-flex;
        align-items: center;
        color: #111827;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .error-404-link:hover {
        color: #4B5563;
    }
</style>

<div class="error-404-container">
    <div class="error-404-inner">
        <h1 class="error-404-code">404</h1>
        <h2 class="error-404-title">
            <?php _e('页面未找到', 'zeroy'); ?>
        </h2>
        <p class="error-404-message">
            <?php _e('抱歉，您访问的页面不存在。', 'zeroy'); ?>
        </p>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="error-404-link">
            <?php _e('← 返回首页', 'zeroy'); ?>
        </a>
    </div>
</div>

<?php get_footer(); ?>