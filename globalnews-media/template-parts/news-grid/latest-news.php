<?php
/**
 * Latest News Grid Section
 */

$latest_args = array(
    'posts_per_page'      => 3,
    'ignore_sticky_posts' => 1,
    'orderby'             => 'date',
    'order'               => 'DESC',
);

$latest_query = new WP_Query($latest_args);

if ($latest_query->have_posts()) :
?>
<section class="latest-news-section section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><span><?php esc_html_e('Latest News', 'globalnews-media'); ?></span></h2>
        </div>
        <div class="news-grid">
            <?php while ($latest_query->have_posts()) : $latest_query->the_post(); ?>
                <article class="news-card">
                    <a href="<?php the_permalink(); ?>" class="news-card-link">
                        <div class="news-card-thumb">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('globalnews-grid', array('loading' => 'lazy')); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url(globalnews_fallback_thumbnail(get_the_ID(), '600x400')); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
                            <?php endif; ?>
                        </div>
                        <div class="news-card-body">
                            <?php echo globalnews_category_badge(); ?>
                            <h3 class="news-card-title"><?php the_title(); ?></h3>
                            <p class="news-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                            <div class="news-card-footer">
                                <?php echo globalnews_post_meta(); ?>
                            </div>
                        </div>
                    </a>
                </article>
            <?php endwhile; ?>
        </div>
        <div class="latest-news-footer">
            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="view-all-btn">
                <?php esc_html_e('View All', 'globalnews-media'); ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
        </div>
    </div>
</section>
<?php
wp_reset_postdata();
endif;
?>
