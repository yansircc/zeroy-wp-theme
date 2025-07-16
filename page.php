<?php

/**
 * 页面模板
 */
get_header();
?>

<style>
    .page-container {
        padding: 3rem 1.5rem;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .page-content {
        color: #374151;
        line-height: 1.75;
        font-size: 1.125rem;
    }
    
    .page-content p {
        margin-bottom: 1.5rem;
    }
    
    .page-content h1,
    .page-content h2,
    .page-content h3,
    .page-content h4,
    .page-content h5,
    .page-content h6 {
        color: #111827;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .page-content h1 { font-size: 2.25rem; }
    .page-content h2 { font-size: 1.875rem; }
    .page-content h3 { font-size: 1.5rem; }
    .page-content h4 { font-size: 1.25rem; }
    
    .page-content a {
        color: #111827;
        text-decoration: underline;
    }
    
    .page-content a:hover {
        color: #4B5563;
    }
    
    .page-content ul,
    .page-content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }
    
    .page-content li {
        margin-bottom: 0.5rem;
    }
    
    .page-content blockquote {
        border-left: 4px solid #E5E7EB;
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #4B5563;
    }
    
    .page-content pre {
        background-color: #F3F4F6;
        padding: 1rem;
        border-radius: 0.375rem;
        overflow-x: auto;
        margin-bottom: 1.5rem;
    }
    
    .page-content code {
        background-color: #F3F4F6;
        padding: 0.125rem 0.25rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
    
    .page-content pre code {
        background-color: transparent;
        padding: 0;
    }
    
    .page-content img {
        max-width: 100%;
        height: auto;
        margin: 2rem 0;
    }
    
    .page-comments {
        margin-top: 4rem;
    }
</style>

<div class="page-container">
    <?php while (have_posts()) : the_post(); ?>
        <article id="page-<?php the_ID(); ?>" <?php post_class(''); ?>>
            <div class="page-content">
                <?php the_content(); ?>
            </div>
        </article>

        <?php if (comments_open() || get_comments_number()) : ?>
            <div class="page-comments">
                <?php comments_template(); ?>
            </div>
        <?php endif; ?>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>