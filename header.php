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

    <link rel="canonical"
        href="<?php echo esc_url(is_singular() ? get_permalink() : (is_home() ? home_url('/') : get_term_link(get_queried_object()))); ?>">

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