<?php
/**
 * Video News Section
 */

$video_args = array(
    'posts_per_page'      => 6,
    'tax_query' => array(
        array(
            'taxonomy' => 'post_format',
            'field'    => 'slug',
            'terms'    => array('post-format-video'),
        ),
    ),
    'ignore_sticky_posts' => 1,
);

$video_query = new WP_Query($video_args);

if ($video_query->have_posts()) :
?>
<section class="video-news-section section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                <?php esc_html_e('Video News', 'globalnews-media'); ?>
            </span></h2>
            <a href="#" class="section-link">
                <?php esc_html_e('More Videos', 'globalnews-media'); ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
        </div>
        <div class="video-carousel" id="videoCarousel">
            <div class="video-track">
                <?php while ($video_query->have_posts()) : $video_query->the_post(); ?>
                    <article class="video-card">
                        <a href="<?php the_permalink(); ?>">
                            <div class="video-thumb-wrapper">
                                <?php the_post_thumbnail('globalnews-featured', array('loading' => 'lazy')); ?>
                                <div class="video-play-icon">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                                <span class="video-duration"><?php esc_html_e('Watch', 'globalnews-media'); ?></span>
                            </div>
                            <h3 class="video-title"><?php the_title(); ?></h3>
                            <span class="video-date"><?php echo get_the_date(); ?></span>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
            <button class="carousel-nav carousel-prev" aria-label="<?php esc_attr_e('Previous', 'globalnews-media'); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <button class="carousel-nav carousel-next" aria-label="<?php esc_attr_e('Next', 'globalnews-media'); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
        </div>
    </div>
</section>
<?php
wp_reset_postdata();
endif;
?>
