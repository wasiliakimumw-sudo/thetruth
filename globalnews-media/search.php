<?php
/**
 * Search Results Template
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="search-header-section">
        <div class="container">
            <div class="search-header-content">
                <h1 class="search-title">
                    <?php printf(esc_html__('Search Results for: %s', 'globalnews-media'), '<span>' . get_search_query() . '</span>'); ?>
                </h1>
                <form role="search" method="get" class="search-form-page" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="search" class="search-field-page" placeholder="<?php esc_attr_e('Search news...', 'globalnews-media'); ?>" value="<?php echo get_search_query(); ?>" name="s">
                    <button type="submit" class="search-submit-page">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="search-body-section">
        <div class="container">
            <div class="content-with-sidebar">
                <div class="content-area-main">
                    <?php if (have_posts()) : ?>
                        <div class="search-results-count">
                            <?php printf(esc_html__('%d results found', 'globalnews-media'), $wp_query->found_posts); ?>
                        </div>
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
                        <div class="no-results">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <h2><?php esc_html_e('No Results Found', 'globalnews-media'); ?></h2>
                            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'globalnews-media'); ?></p>
                            <form role="search" method="get" class="search-form-404" action="<?php echo esc_url(home_url('/')); ?>">
                                <input type="search" placeholder="<?php esc_attr_e('Search...', 'globalnews-media'); ?>" value="<?php echo get_search_query(); ?>" name="s">
                                <button type="submit"><?php esc_html_e('Search', 'globalnews-media'); ?></button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
