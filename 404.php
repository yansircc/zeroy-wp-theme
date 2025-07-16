<?php

/**
 * 404 错误页面
 */
get_header();
?>

<div>
    <div>
        <h1>404</h1>
        <h2>
            <?php _e('页面未找到', 'zeroy'); ?>
        </h2>
        <p>
            <?php _e('抱歉，您访问的页面不存在。', 'zeroy'); ?>
        </p>
        <a href="<?php echo esc_url(home_url('/')); ?>">
            <?php _e('← 返回首页', 'zeroy'); ?>
        </a>
    </div>
</div>

<?php get_footer(); ?>