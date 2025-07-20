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
        
        // 添加钩子
        add_filter('site_transient_update_themes', array($this, 'check_for_update'));
        add_filter('themes_api', array($this, 'theme_api_call'), 10, 3);
    }
    
    /**
     * 检查主题更新
     */
    public function check_for_update($transient) {
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
        $url = $this->update_path . '?theme=' . $this->theme_slug . '&version=' . $this->version;
        
        // 在开发环境下添加 dev 参数
        if (defined('WP_DEBUG') && WP_DEBUG && defined('ZEROY_DEV_MODE') && ZEROY_DEV_MODE) {
            $url .= '&dev=1';
        }
        
        $request = wp_remote_get($url);
        
        if (is_wp_error($request)) {
            return false;
        }
        
        $response_code = wp_remote_retrieve_response_code($request);
        
        if ($response_code === 200) {
            $body = wp_remote_retrieve_body($request);
            $data = json_decode($body, true);
            
            if (isset($data['update_available'])) {
                return $data;
            }
        }
        
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
        $request = wp_remote_get('https://www.zeroy.app/api/wp-updates/themes/info/' . $this->theme_slug);
        
        if (!is_wp_error($request) && wp_remote_retrieve_response_code($request) === 200) {
            $body = wp_remote_retrieve_body($request);
            return json_decode($body, true);
        }
        
        return false;
    }
    
    /**
     * 获取更新日志
     */
    private function get_changelog() {
        $request = wp_remote_get('https://www.zeroy.app/api/wp-updates/themes/changelog/' . $this->theme_slug);
        
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
                return $changelog;
            }
        }
        
        return '暂无更新日志';
    }
}

// 初始化主题更新器
new Zeroy_Theme_Updater();