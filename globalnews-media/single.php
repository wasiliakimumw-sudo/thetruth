<?php
/**
 * Single Article Template
 */

get_header();

while (have_posts()) :
    the_post();

    $categories = get_the_category();
    $author_id = get_the_author_meta('ID');
    globalnews_set_post_views(get_the_ID());
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-article'); ?>>
    <div class="article-header-section">
        <div class="container">
            <div class="article-header-content">
                <?php globalnews_breadcrumbs(); ?>
                <div class="article-categories">
                    <?php foreach ($categories as $cat) : ?>
                        <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="article-category"><?php echo esc_html($cat->name); ?></a>
                    <?php endforeach; ?>
                </div>
                <h1 class="article-title"><?php the_title(); ?></h1>
                <div class="article-meta-top">
                    <div class="article-author">
                        <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>" class="article-author-link">
                            <div class="article-author-avatar">
                                <?php echo get_avatar($author_id, 48, '', get_the_author(), array('class' => 'avatar')); ?>
                            </div>
                            <div class="article-author-info">
                                <span class="article-author-name"><?php the_author(); ?></span>
                                <span class="article-author-title"><?php esc_html_e('Staff Writer', 'globalnews-media'); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="article-meta-right">
                        <span class="article-date">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <?php echo get_the_date(); ?>
                        </span>
                        <span class="article-reading-time">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <?php printf(esc_html__('%d min read', 'globalnews-media'), globalnews_get_reading_time()); ?>
                        </span>
                        <span class="article-views">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <?php $views = get_post_meta(get_the_ID(), 'globalnews_post_views', true); echo absint($views ?: 0) . ' ' . esc_html__('views', 'globalnews-media'); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="article-featured-image">
        <div class="container container-narrow">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('full', array('class' => 'article-featured-img', 'loading' => 'eager')); ?>
                <?php if (get_the_post_thumbnail_caption()) : ?>
                    <p class="article-image-caption"><?php the_post_thumbnail_caption(); ?></p>
                <?php endif; ?>
            <?php else : ?>
                <img src="<?php echo esc_url(globalnews_fallback_thumbnail(get_the_ID(), '1200x675')); ?>" alt="<?php the_title_attribute(); ?>" class="article-featured-img" loading="eager" style="width:100%;border-radius:var(--gm-radius-lg);">
            <?php endif; ?>
        </div>
    </div>

    <div class="article-body-section">
        <div class="container">
            <div class="article-layout">
                <div class="article-content-area">
                    <div class="article-share-sticky">
                        <?php globalnews_social_share(); ?>
                    </div>

                    <div class="article-content entry-content">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'globalnews-media'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>

                    <div class="article-tags">
                        <?php
                        $tags = get_the_tags();
                        if ($tags) :
                            foreach ($tags as $tag) : ?>
                                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="article-tag">#<?php echo esc_html($tag->name); ?></a>
                            <?php endforeach;
                        endif;
                        ?>
                    </div>

                    <?php get_template_part('template-parts/feedback-widget'); ?>

                    <div class="article-author-box">
                        <div class="author-box-avatar">
                            <?php echo get_avatar($author_id, 100, '', get_the_author()); ?>
                        </div>
                        <div class="author-box-info">
                            <h4 class="author-box-name">
                                <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>"><?php the_author(); ?></a>
                            </h4>
                            <p class="author-box-bio"><?php echo get_the_author_meta('description'); ?></p>
                            <div class="author-box-social">
                                <a href="#" class="author-social-link" aria-label="Twitter">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                                <a href="#" class="author-social-link" aria-label="LinkedIn">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="article-bottom-share">
                        <?php esc_html_e('Share this article:', 'globalnews-media'); ?>
                        <?php globalnews_social_share(); ?>
                    </div>

                    <?php globalnews_related_posts(); ?>

                    <?php
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </div>
                <aside class="article-sidebar">
                    <?php
                    if (is_active_sidebar('sidebar-sticky')) :
                        dynamic_sidebar('sidebar-sticky');
                    elseif (is_active_sidebar('sidebar-main')) :
                        dynamic_sidebar('sidebar-main');
                    endif;
                    ?>
                </aside>
            </div>
        </div>
    </div>
</article>

<?php
endwhile;

get_footer();
