<?php
/**
 * Archive Template
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="archive-header-section">
        <div class="container">
            <div class="archive-header-content">
                <?php globalnews_breadcrumbs(); ?>
                <h1 class="archive-title">
                    <?php
                    if (is_category()) {
                        single_cat_title();
                    } elseif (is_tag()) {
                        printf(esc_html__('Tag: %s', 'globalnews-media'), single_tag_title('', false));
                    } elseif (is_day()) {
                        printf(esc_html__('Day: %s', 'globalnews-media'), get_the_date());
                    } elseif (is_month()) {
                        printf(esc_html__('Month: %s', 'globalnews-media'), get_the_date('F Y'));
                    } elseif (is_year()) {
                        printf(esc_html__('Year: %s', 'globalnews-media'), get_the_date('Y'));
                    } elseif (is_author()) {
                        printf(esc_html__('Author: %s', 'globalnews-media'), get_the_author());
                    } else {
                        esc_html_e('Archives', 'globalnews-media');
                    }
                    ?>
                </h1>
                <?php if (is_category() && category_description()) : ?>
                    <p class="archive-description"><?php echo category_description(); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="archive-body-section">
        <div class="container">
            <div class="content-with-sidebar">
                <div class="content-area-main">
                    <?php if (have_posts()) : ?>
                        <div class="archive-grid">
                            <?php while (have_posts()) : the_post(); ?>
                                <article class="archive-card">
                                    <a href="<?php the_permalink(); ?>">
                                        <div class="archive-card-thumb">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('globalnews-grid', array('loading' => 'lazy')); ?>
                                            <?php else : ?>
                                                <img src="<?php echo esc_url(globalnews_fallback_thumbnail(get_the_ID(), '600x400')); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
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
                        <p class="no-posts"><?php esc_html_e('No posts found.', 'globalnews-media'); ?></p>
                    <?php endif; ?>
                </div>
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
