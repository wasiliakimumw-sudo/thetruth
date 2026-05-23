<?php
/**
 * Author Page Template
 */

get_header();

$author_id = get_queried_object_id();
$author_name = get_the_author_meta('display_name', $author_id);
$author_bio = get_the_author_meta('description', $author_id);
$author_email = get_the_author_meta('user_email', $author_id);
$author_posts_url = get_author_posts_url($author_id);
?>

<main id="primary" class="site-main">
    <div class="author-archive-header">
        <div class="container">
            <div class="author-archive-card">
                <div class="author-archive-avatar">
                    <?php echo get_avatar($author_id, 120, '', $author_name, array('class' => 'author-archive-img')); ?>
                </div>
                <div class="author-archive-info">
                    <h1 class="author-archive-name"><?php echo esc_html($author_name); ?></h1>
                    <?php if ($author_bio) : ?>
                        <p class="author-archive-bio"><?php echo esc_html($author_bio); ?></p>
                    <?php endif; ?>
                    <div class="author-archive-meta">
                        <span class="author-archive-articles">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                            <?php printf(esc_html__('%s articles', 'globalnews-media'), count_user_posts($author_id)); ?>
                        </span>
                        <span class="author-archive-joined">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <?php printf(esc_html__('Joined %s', 'globalnews-media'), date_i18n(get_option('date_format'), strtotime(get_the_author_meta('user_registered', $author_id)))); ?>
                        </span>
                    </div>
                    <div class="author-archive-social">
                        <a href="#" class="author-social-link" aria-label="Twitter">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="author-social-link" aria-label="LinkedIn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <a href="mailto:<?php echo esc_attr($author_email); ?>" class="author-social-link" aria-label="Email">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="archive-body-section">
        <div class="container">
            <div class="content-with-sidebar">
                <div class="content-area-main">
                    <?php if (have_posts()) : ?>
                        <h2 class="section-title"><span><?php printf(esc_html__('Articles by %s', 'globalnews-media'), $author_name); ?></span></h2>
                        <div class="archive-grid">
                            <?php while (have_posts()) : the_post(); ?>
                                <article class="archive-card">
                                    <a href="<?php the_permalink(); ?>">
                                        <div class="archive-card-thumb">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('globalnews-grid', array('loading' => 'lazy')); ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="archive-card-content">
                                            <?php echo globalnews_category_badge(); ?>
                                            <h2 class="archive-card-title"><?php the_title(); ?></h2>
                                            <p class="archive-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                                            <?php echo globalnews_post_meta(); ?>
                                        </div>
                                    </a>
                                </article>
                            <?php endwhile; ?>
                        </div>
                        <div class="pagination-wrap">
                            <?php
                            the_posts_pagination(array(
                                'mid_size'  => 2,
                                'prev_text' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>',
                                'next_text' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>',
                            ));
                            ?>
                        </div>
                    <?php else : ?>
                        <p class="no-posts"><?php esc_html_e('No articles published yet.', 'globalnews-media'); ?></p>
                    <?php endif; ?>
                </div>
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
