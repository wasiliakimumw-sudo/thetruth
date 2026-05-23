<?php
/**
 * Breaking News Ticker
 */

$breaking_label = get_theme_mod('globalnews_breaking_news_text', esc_html__('Breaking News', 'globalnews-media'));
$ticker_speed   = get_theme_mod('globalnews_ticker_speed', 4000);

$breaking_args = array(
    'posts_per_page'      => 8,
    'meta_key'            => 'globalnews_breaking_news',
    'meta_value'          => '1',
    'ignore_sticky_posts' => 1,
);

$breaking_query = new WP_Query($breaking_args);

if ($breaking_query->have_posts()) :
?>
<div class="breaking-news-bar">
    <div class="container">
        <div class="breaking-news-inner">
            <div class="breaking-label">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L1 21h22L12 2zm0 3.5L18.5 19h-13L12 5.5zM11 10v4h2v-4h-2zm0 6v2h2v-2h-2z"/>
                </svg>
                <span><?php echo esc_html($breaking_label); ?></span>
            </div>
            <div class="breaking-ticker" data-speed="<?php echo esc_attr($ticker_speed); ?>">
                <div class="ticker-track" id="tickerTrack">
                    <?php while ($breaking_query->have_posts()) : $breaking_query->the_post(); ?>
                        <div class="ticker-item">
                            <a href="<?php the_permalink(); ?>">
                                <span class="ticker-time"><?php echo get_the_date('H:i'); ?></span>
                                <span class="ticker-headline"><?php the_title(); ?></span>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <button class="breaking-close" id="breakingClose" aria-label="<?php esc_attr_e('Close breaking news', 'globalnews-media'); ?>">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>
</div>
<?php
endif;
wp_reset_postdata();
?>
