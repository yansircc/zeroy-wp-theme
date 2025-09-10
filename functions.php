<?php

/**
 * Zeroy Theme Functions
 */

// 主题支持
function zeroy_theme_support()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'zeroy_theme_support');

// 注册菜单
function zeroy_register_menus()
{
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'zeroy'),
    ));
}
add_action('init', 'zeroy_register_menus');

// 移除 WordPress 默认样式和功能
function zeroy_remove_default_styles()
{
    // 移除区块库样式
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');

    // 移除 emoji 相关
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
}
add_action('wp_enqueue_scripts', 'zeroy_remove_default_styles', 100);
add_action('init', 'zeroy_remove_default_styles');

// 清理 wp_head 输出
function zeroy_clean_head()
{
    // 移除 RSD 链接
    remove_action('wp_head', 'rsd_link');

    // 移除 Windows Live Writer
    remove_action('wp_head', 'wlwmanifest_link');

    // 移除 WordPress 版本
    remove_action('wp_head', 'wp_generator');

    // 移除短链接
    remove_action('wp_head', 'wp_shortlink_wp_head');

    // 移除 REST API 链接
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // 移除 RSS feed 链接
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);

    // 移除相邻文章链接
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

    // 移除 wp-json 链接
    remove_action('template_redirect', 'rest_output_link_header', 11);

    // 移除 robots meta (我们自己控制)
    remove_filter('wp_robots', 'wp_robots_max_image_preview_large');

    // 移除 canonical 和 shortlink
    remove_action('wp_head', 'rel_canonical');
    remove_action('wp_head', 'wp_shortlink_wp_head');

    // 移除 oEmbed
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');

    // 移除 wp-block-library-css
    add_action('wp_enqueue_scripts', function () {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-blocks-style');
        wp_dequeue_style('global-styles');
        wp_dequeue_style('classic-theme-styles');
    }, 100);
}
add_action('init', 'zeroy_clean_head');

// 移除 speculation rules 脚本
add_filter('wp_speculation_rules_configuration', '__return_null');

// 移除所有不必要的 body class
function zeroy_clean_body_class($classes)
{
    // 只保留最基本的类
    $allowed = array('home', 'single', 'page', 'archive', 'error404');
    $clean_classes = array();

    foreach ($classes as $class) {
        if (in_array($class, $allowed)) {
            $clean_classes[] = $class;
        }
    }

    return $clean_classes;
}
add_filter('body_class', 'zeroy_clean_body_class', 999);

// 完全禁用 robots meta 标签
add_filter('wp_robots', '__return_empty_array', 999);

// 移除 DNS 预取
remove_action('wp_head', 'wp_resource_hints', 2);

// 禁用 XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// 移除查询字符串
function zeroy_remove_query_strings($src)
{
    $parts = explode('?', $src);
    return $parts[0];
}
add_filter('script_loader_src', 'zeroy_remove_query_strings', 15, 1);
add_filter('style_loader_src', 'zeroy_remove_query_strings', 15, 1);

// 移除 jQuery（如果前台不需要）
// function zeroy_remove_jquery()
// {
//     if (!is_admin()) {
//         wp_deregister_script('jquery');
//         wp_register_script('jquery', false);
//     }
// }
// add_action('init', 'zeroy_remove_jquery');

// 移除不必要的 meta 标签
remove_action('wp_head', 'wp_robots', 1);

// 简化语言属性
function zeroy_language_attributes($output)
{
    return 'lang="' . get_bloginfo('language') . '"';
}
add_filter('language_attributes', 'zeroy_language_attributes');

// 主题自动更新功能
// 可选：通过设置此常量为 true 来禁用更新检查（用于内网或演示环境）
if (!defined('ZEROY_DISABLE_UPDATE_CHECKS')) {
    define('ZEROY_DISABLE_UPDATE_CHECKS', false);
}
require_once get_template_directory() . '/inc/theme-updater.php';

// 处理主题更新时的备份权限问题
add_filter('upgrader_package_options', function($options) {
    if (isset($options['hook_extra']['theme']) && $options['hook_extra']['theme'] === get_template()) {
        // 禁用备份功能，避免权限问题
        $options['clear_destination'] = true;
        $options['overwrite_package'] = true;
    }
    return $options;
});

// 前台加载 htmx 脚本
function zeroy_enqueue_front_assets()
{
    if (is_admin()) {
        return;
    }

    $script_file = get_template_directory() . '/assets/js/htmx.min.js';
    $version = file_exists($script_file) ? filemtime($script_file) : null;

    wp_enqueue_script(
        'zeroy-htmx',
        get_template_directory_uri() . '/assets/js/htmx.min.js',
        array(),
        $version,
        true
    );
}
add_action('wp_enqueue_scripts', 'zeroy_enqueue_front_assets', 20);
