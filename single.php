<?php

/**
 * 单篇文章模板
 */
get_header();
?>

<style>
    .single-post-container {
        padding: 3rem 1.5rem;
    }
    
    .single-post-article {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .single-post-header {
        margin-bottom: 3rem;
    }
    
    .single-post-title {
        font-size: 2.25rem;
        line-height: 2.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #111827;
    }
    
    @media (min-width: 768px) {
        .single-post-title {
            font-size: 3rem;
            line-height: 1;
        }
    }
    
    .single-post-meta {
        font-size: 0.875rem;
        line-height: 1.25rem;
        color: #6B7280;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .single-post-meta-separator {
        color: #9CA3AF;
    }
    
    .single-post-thumbnail {
        margin-bottom: 3rem;
        margin-left: -1.5rem;
        margin-right: -1.5rem;
    }
    
    .single-post-thumbnail img {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .single-post-content {
        color: #374151;
        line-height: 1.75;
        font-size: 1.125rem;
    }
    
    .single-post-content p {
        margin-bottom: 1.5rem;
    }
    
    .single-post-content h1,
    .single-post-content h2,
    .single-post-content h3,
    .single-post-content h4,
    .single-post-content h5,
    .single-post-content h6 {
        color: #111827;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .single-post-content h1 { font-size: 2.25rem; }
    .single-post-content h2 { font-size: 1.875rem; }
    .single-post-content h3 { font-size: 1.5rem; }
    .single-post-content h4 { font-size: 1.25rem; }
    
    .single-post-content a {
        color: #111827;
        text-decoration: underline;
    }
    
    .single-post-content a:hover {
        color: #4B5563;
    }
    
    .single-post-content ul,
    .single-post-content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }
    
    .single-post-content li {
        margin-bottom: 0.5rem;
    }
    
    .single-post-content blockquote {
        border-left: 4px solid #E5E7EB;
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #4B5563;
    }
    
    .single-post-content pre {
        background-color: #F3F4F6;
        padding: 1rem;
        border-radius: 0.375rem;
        overflow-x: auto;
        margin-bottom: 1.5rem;
    }
    
    .single-post-content code {
        background-color: #F3F4F6;
        padding: 0.125rem 0.25rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
    
    .single-post-content pre code {
        background-color: transparent;
        padding: 0;
    }
    
    .single-post-content img {
        max-width: 100%;
        height: auto;
        margin: 2rem 0;
    }
    
    .single-post-footer {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #E5E7EB;
    }
    
    .single-post-tags-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .single-post-tags-label {
        font-size: 0.875rem;
        color: #6B7280;
    }
    
    .single-post-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .single-post-tags a {
        background-color: #F3F4F6;
        color: #374151;
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .single-post-tags a:hover {
        background-color: #E5E7EB;
        color: #111827;
    }
    
    .single-post-comments {
        margin-top: 4rem;
    }
</style>

<div class="single-post-container">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-post-article'); ?>>
            <header class="single-post-header">
                <h1 class="single-post-title"><?php the_title(); ?></h1>
                <div class="single-post-meta">
                    <time datetime="<?php echo get_the_date('c'); ?>">
                        <?php echo get_the_date(); ?>
                    </time>
                    <span class="single-post-meta-separator">•</span>
                    <span><?php the_author(); ?></span>
                    <?php if (has_category()) : ?>
                        <span class="single-post-meta-separator">•</span>
                        <span><?php the_category(', '); ?></span>
                    <?php endif; ?>
                </div>
            </header>

            <?php if (has_post_thumbnail()) : ?>
                <div class="single-post-thumbnail">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>

            <div class="single-post-content">
                <?php the_content(); ?>
            </div>

            <?php if (has_tag()) : ?>
                <footer class="single-post-footer">
                    <div class="single-post-tags-container">
                        <span class="single-post-tags-label"><?php _e('标签：', 'zeroy'); ?></span>
                        <?php the_tags('<div class="single-post-tags">', '', '</div>'); ?>
                    </div>
                </footer>
            <?php endif; ?>
        </article>

        <?php if (comments_open() || get_comments_number()) : ?>
            <div class="single-post-comments">
                <?php comments_template(); ?>
            </div>
        <?php endif; ?>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>