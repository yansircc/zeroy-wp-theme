<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <?php
    // 获取 meta title
    $meta_title = wp_get_document_title();

    // 获取 meta description
    $description = '';
    if (is_singular()) {
        // 单页面/文章：使用摘要或内容前160字符
        global $post;
        if (has_excerpt()) {
            $description = get_the_excerpt();
        } else {
            $description = wp_trim_words(strip_tags($post->post_content), 30, '');
        }
    } elseif (is_category() || is_tag()) {
        // 分类/标签页：使用描述
        $description = term_description();
    } elseif (is_home() || is_front_page()) {
        // 首页：使用网站描述
        $description = get_bloginfo('description');
    }
    ?>

    <meta name="title" content="<?php echo esc_attr($meta_title); ?>">
    <?php if ($description): ?>
        <meta name="description" content="<?php echo esc_attr($description); ?>">
    <?php endif; ?>

    <?php
    // 获取 canonical URL
    if (is_singular()) {
        $canonical_url = get_permalink();
    } elseif (is_home()) {
        $canonical_url = home_url('/');
    } elseif (is_404()) {
        $canonical_url = home_url('/404');
    } else {
        $term_link = get_term_link(get_queried_object());
        $canonical_url = !is_wp_error($term_link) ? $term_link : home_url('/');
    }
    ?>
    <link rel="canonical" href="<?php echo esc_url($canonical_url); ?>">

    <?php wp_head(); ?>
</head>

<body>
    <?php wp_body_open(); ?>

    <div id="page">
        <?php if (has_nav_menu('primary')): ?>
            <header>
                <div>
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php bloginfo('name'); ?>
                    </a>

                    <nav>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'container' => false,
                            'menu_class' => '',
                        ));
                        ?>
                    </nav>
                </div>
            </header>
        <?php endif; ?>

        <main><?php // 内容区域开始 
        ?>