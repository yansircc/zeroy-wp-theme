<?php
/**
 * Template Name: 着陆页（无页眉页脚）
 * Template Post Type: page
 * 
 * 这是一个没有页眉页脚的页面模板，适用于制作着陆页
 */
?>
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
    if (has_excerpt()) {
        $description = get_the_excerpt();
    } else {
        global $post;
        $description = wp_trim_words(strip_tags($post->post_content), 30, '');
    }
    ?>

    <meta name="title" content="<?php echo esc_attr($meta_title); ?>">
    <?php if ($description): ?>
        <meta name="description" content="<?php echo esc_attr($description); ?>">
    <?php endif; ?>

    <link rel="canonical" href="<?php echo esc_url(get_permalink()); ?>">

    <?php wp_head(); ?>
</head>

<body <?php body_class('landing-page'); ?>>
    <?php wp_body_open(); ?>

    <div id="landing-page">
        <?php while (have_posts()): the_post(); ?>
            <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php the_content(); ?>
            </article>
        <?php endwhile; ?>
    </div>

    <?php wp_footer(); ?>
</body>

</html>