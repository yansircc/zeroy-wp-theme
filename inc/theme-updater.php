<?php

/**
 * Zeroy Theme Updater
 * 
 * Handles automatic theme updates from custom server
 */

if (!defined('ABSPATH')) {
    exit;
}

class Zeroy_Theme_Updater {
    
    private $theme_slug;
    private $version;
    private $author;
    private $update_path;
    private $theme_name;
    private $theme_file;
    
    public function __construct() {
        $this->theme_slug = get_template();
        $this->version = wp_get_theme()->get('Version');
        $this->author = wp_get_theme()->get('Author');
        $this->update_path = 'https://www.zeroy.app/api/wp-updates/themes/check';
        $this->theme_name = wp_get_theme()->get('Name');
        $this->theme_file = $this->theme_slug;
        
        // 添加钩子：只在 WP 执行检查并写入缓存时触发，避免每个后台页面都请求远程
        add_filter('pre_set_site_transient_update_themes', array($this, 'check_for_update'));
        add_filter('themes_api', array($this, 'theme_api_call'), 10, 3);
    }
    
    /**
     * 检查主题更新
     */
    public function check_for_update($transient) {
        // 允许通过常量禁用远程检查（例如内网或演示环境）
        if (defined('ZEROY_DISABLE_UPDATE_CHECKS') && ZEROY_DISABLE_UPDATE_CHECKS) {
            return $transient;
        }

        // 仅当 WP 正在检查更新时才继续
        if (empty($transient->checked)) {
            return $transient;
        }

        // 检查是否有新版本
        $remote_version = $this->get_remote_version();
        
        if (!$remote_version) {
            return $transient;
        }
        
        // 比较版本 - 使用 API 返回的 update_available 字段
        if ($remote_version['update_available'] === true) {
            $transient->response[$this->theme_file] = array(
                'theme' => $this->theme_slug,
                'new_version' => $remote_version['latest_version'],
                'url' => $remote_version['update_info']['url'] ?? '',
                'package' => $remote_version['update_info']['package'] ?? '',
                'requires' => $remote_version['update_info']['requires'] ?? '',
                'tested' => $remote_version['update_info']['tested'] ?? '',
                'requires_php' => $remote_version['update_info']['requires_php'] ?? '',
            );
        }
        
        return $transient;
    }
    
    /**
     * 获取远程版本信息
     */
    private function get_remote_version() {
        $cache_key = 'zeroy_theme_update_' . $this->theme_slug;
        $cached = get_site_transient($cache_key);

        if ($cached && is_array($cached)) {
            return $cached;
        }

        $url = $this->update_path . '?theme=' . $this->theme_slug . '&version=' . $this->version;

        // 在开发环境下添加 dev 参数
        if (defined('WP_DEBUG') && WP_DEBUG && defined('ZEROY_DEV_MODE') && ZEROY_DEV_MODE) {
            $url .= '&dev=1';
        }

        // 使用较短超时并带 UA，避免后台阻塞
        $args = array(
            'timeout' => 3,
            'redirection' => 2,
            'sslverify' => true,
            'headers' => array(
                'User-Agent' => 'zeroy-theme-updater/' . $this->version . '; ' . get_bloginfo('url'),
            ),
        );

        $request = wp_remote_get($url, $args);

        // 失败场景短期缓存空结果，防止频繁重试造成卡顿
        $failure_ttl = HOUR_IN_SECONDS; // 1 小时退避

        if (is_wp_error($request)) {
            set_site_transient($cache_key, false, $failure_ttl);
            return false;
        }

        $response_code = wp_remote_retrieve_response_code($request);

        if ($response_code === 200) {
            $body = wp_remote_retrieve_body($request);
            $data = json_decode($body, true);

            if (isset($data['update_available'])) {
                // 成功结果缓存更久；开发模式下缩短
                $success_ttl = (defined('ZEROY_DEV_MODE') && ZEROY_DEV_MODE) ? 5 * MINUTE_IN_SECONDS : 12 * HOUR_IN_SECONDS;
                set_site_transient($cache_key, $data, $success_ttl);
                return $data;
            }
        }

        set_site_transient($cache_key, false, $failure_ttl);
        return false;
    }
    
    /**
     * 主题 API 调用
     */
    public function theme_api_call($res, $action, $args) {
        if ($action !== 'theme_information') {
            return $res;
        }
        
        if ($args->slug !== $this->theme_slug) {
            return $res;
        }
        
        // 获取主题信息
        $remote_info = $this->get_remote_info();
        
        if (!$remote_info) {
            return $res;
        }
        
        $res = new stdClass();
        $res->name = $remote_info['name'];
        $res->slug = $this->theme_slug;
        $res->version = $remote_info['version'];
        $res->author = $remote_info['author'];
        $res->author_profile = $remote_info['author_homepage'];
        $res->contributors = array($remote_info['author']);
        $res->homepage = $remote_info['theme_uri'];
        $res->description = $remote_info['description'];
        $res->screenshot_url = get_template_directory_uri() . '/screenshot.png';
        $res->sections = array(
            'description' => $remote_info['description'],
            'changelog' => $this->get_changelog(),
        );
        $res->download_link = $remote_info['download_url'];
        $res->last_updated = $remote_info['last_updated'];
        $res->requires = $remote_info['requires'];
        $res->tested = $remote_info['tested'];
        $res->requires_php = $remote_info['requires_php'];
        
        return $res;
    }
    
    /**
     * 获取远程主题信息
     */
    private function get_remote_info() {
        $cache_key = 'zeroy_theme_info_' . $this->theme_slug;
        $cached = get_site_transient($cache_key);
        if ($cached) {
            return $cached;
        }

        $args = array(
            'timeout' => 3,
            'redirection' => 2,
            'sslverify' => true,
            'headers' => array(
                'User-Agent' => 'zeroy-theme-updater/' . $this->version . '; ' . get_bloginfo('url'),
            ),
        );
        $request = wp_remote_get('https://www.zeroy.app/api/wp-updates/themes/info/' . $this->theme_slug, $args);

        if (!is_wp_error($request) && wp_remote_retrieve_response_code($request) === 200) {
            $body = wp_remote_retrieve_body($request);
            $data = json_decode($body, true);
            set_site_transient($cache_key, $data, DAY_IN_SECONDS);
            return $data;
        }

        // 缓存失败状态以避免反复请求
        set_site_transient($cache_key, false, HOUR_IN_SECONDS);
        return false;
    }
    
    /**
     * 获取更新日志
     */
    private function get_changelog() {
        $cache_key = 'zeroy_theme_changelog_' . $this->theme_slug;
        $cached = get_site_transient($cache_key);
        if ($cached !== false) {
            return $cached;
        }

        $args = array(
            'timeout' => 3,
            'redirection' => 2,
            'sslverify' => true,
            'headers' => array(
                'User-Agent' => 'zeroy-theme-updater/' . $this->version . '; ' . get_bloginfo('url'),
            ),
        );
        $request = wp_remote_get('https://www.zeroy.app/api/wp-updates/themes/changelog/' . $this->theme_slug, $args);

        if (!is_wp_error($request) && wp_remote_retrieve_response_code($request) === 200) {
            $body = wp_remote_retrieve_body($request);
            $data = json_decode($body, true);

            if (isset($data['changelog']) && is_array($data['changelog'])) {
                $changelog = '';
                foreach ($data['changelog'] as $entry) {
                    $changelog .= '<h4>版本 ' . $entry['version'] . ' - ' . date('Y-m-d', strtotime($entry['date'])) . '</h4>';
                    $changelog .= '<ul>';
                    foreach ($entry['changes'] as $change) {
                        $changelog .= '<li>' . esc_html($change) . '</li>';
                    }
                    $changelog .= '</ul>';
                }
                // changelog 更新频率通常较低，缓存 1 天
                set_site_transient($cache_key, $changelog, DAY_IN_SECONDS);
                return $changelog;
            }
        }

        // 失败时短期缓存占位文本，避免反复请求
        set_site_transient($cache_key, '暂无更新日志', HOUR_IN_SECONDS);
        return '暂无更新日志';
    }
}

// 初始化主题更新器
new Zeroy_Theme_Updater();
