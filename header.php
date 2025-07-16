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
    <?php if ($description) : ?>
    <meta name="description" content="<?php echo esc_attr($description); ?>">
    <?php endif; ?>
    
    <link rel="canonical" href="<?php echo esc_url(is_singular() ? get_permalink() : (is_home() ? home_url('/') : get_term_link(get_queried_object()))); ?>">
    
    <style>
    /* Reset & Base */
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:system-ui,-apple-system,sans-serif;line-height:1.5;color:#111;background:#fff}
    a{color:inherit;text-decoration:none}
    img{max-width:100%;height:auto}
    
    /* Typography */
    h1,h2,h3,h4{font-weight:700;line-height:1.2}
    
    /* Header Styles */
    #page{min-height:100vh}
    header{border-bottom:1px solid #e5e7eb}
    header .container{padding:1rem 1.5rem;display:flex;align-items:center;justify-content:space-between}
    header a{font-size:1.25rem;font-weight:700;color:#111827;transition:color 0.15s}
    header a:hover{color:#4b5563}
    header nav ul{list-style:none;display:flex;gap:2rem}
    header nav a{font-weight:400;color:#374151}
    header nav a:hover{color:#111827}
    .screen-reader-text{position:absolute;left:-9999px}
    
    @media(max-width:767px){
        header nav{display:none}
    }
    </style>
    
    <?php wp_head(); ?>
</head>

<body>
    <?php wp_body_open(); ?>

    <div id="page">
        <?php if (has_nav_menu('primary')) : ?>
            <header>
                <div class="container">
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