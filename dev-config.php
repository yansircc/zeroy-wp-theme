<?php
/**
 * zeroY 开发模式配置
 * 
 * 将以下代码添加到您的 wp-config.php 文件中，
 * 以启用 zeroY 主题和插件的开发模式自动更新功能。
 * 
 * 在开发模式下，即使版本号没有改变，只要有新的构建上传，
 * 系统也会提示更新（24小时内的上传都会被检测到）。
 */

// 启用 WordPress 调试模式（如果尚未启用）
if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', true);
}

// 启用 zeroY 开发模式
define('ZEROY_DEV_MODE', true);

// 可选：强制检查更新的时间间隔（秒）
// 默认 WordPress 每12小时检查一次更新
// 开发时可以设置为更短的时间间隔
add_filter('wp_update_themes', function($update) {
    if (defined('ZEROY_DEV_MODE') && ZEROY_DEV_MODE) {
        $last_checked = get_site_transient('update_themes');
        if ($last_checked && isset($last_checked->last_checked)) {
            // 强制每5分钟检查一次更新
            if (time() - $last_checked->last_checked > 300) {
                delete_site_transient('update_themes');
            }
        }
    }
    return $update;
});

add_filter('wp_update_plugins', function($update) {
    if (defined('ZEROY_DEV_MODE') && ZEROY_DEV_MODE) {
        $last_checked = get_site_transient('update_plugins');
        if ($last_checked && isset($last_checked->last_checked)) {
            // 强制每5分钟检查一次更新
            if (time() - $last_checked->last_checked > 300) {
                delete_site_transient('update_plugins');
            }
        }
    }
    return $update;
});