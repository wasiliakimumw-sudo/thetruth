<?php
/**
 * Trending Sidebar (fallback if no widgets active)
 */

$trending_args = array(
    'posts_per_page'      => 5,
    'meta_key'            => 'globalnews_post_views',
    'orderby'             => 'meta_value_num',
    'order'               => 'DESC',
    'ignore_sticky_posts' => 1,
);
$trending = new WP_Query($trending_args);
?>

<div class="sidebar-widget widget-trending">
    <h3 class="widget-title"><span><?php echo esc_html(globalnews_get_landing_setting('globalnews_trending_title')); ?></span></h3>
    <?php if ($trending->have_posts()) : $i = 1; ?>
        <div class="trending-posts">
            <?php while ($trending->have_posts()) : $trending->the_post(); ?>
                <div class="trending-item">
                    <span class="trending-num"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></span>
                    <div class="trending-info">
                        <h4 class="trending-headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <span class="trending-date"><?php echo get_the_date(); ?></span>
                    </div>
                </div>
            <?php $i++; endwhile; ?>
        </div>
    <?php endif; wp_reset_postdata(); ?>
</div>

<div class="sidebar-widget widget-social-follow">
    <h3 class="widget-title"><span><?php echo esc_html(globalnews_get_landing_setting('globalnews_follow_title')); ?></span></h3>
    <div class="social-follow-grid">
        <a href="<?php echo esc_url(get_theme_mod('globalnews_social_facebook', '#')); ?>" class="sf-item sf-facebook" target="_blank" rel="noopener noreferrer">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            <span><?php esc_html_e('Facebook', 'globalnews-media'); ?></span>
        </a>
        <a href="<?php echo esc_url(get_theme_mod('globalnews_social_twitter', '#')); ?>" class="sf-item sf-twitter" target="_blank" rel="noopener noreferrer">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            <span><?php esc_html_e('X / Twitter', 'globalnews-media'); ?></span>
        </a>
        <a href="<?php echo esc_url(get_theme_mod('globalnews_social_instagram', '#')); ?>" class="sf-item sf-instagram" target="_blank" rel="noopener noreferrer">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069z"/></svg>
            <span><?php esc_html_e('Instagram', 'globalnews-media'); ?></span>
        </a>
        <a href="<?php echo esc_url(get_theme_mod('globalnews_social_youtube', '#')); ?>" class="sf-item sf-youtube" target="_blank" rel="noopener noreferrer">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814z"/></svg>
            <span><?php esc_html_e('YouTube', 'globalnews-media'); ?></span>
        </a>
    </div>
</div>

<div class="sidebar-widget widget-newsletter-mini">
    <h3 class="widget-title"><span><?php echo esc_html(globalnews_get_landing_setting('globalnews_sidebar_newsletter_title')); ?></span></h3>
    <div class="newsletter-mini">
        <p><?php echo esc_html(globalnews_get_landing_setting('globalnews_sidebar_newsletter_desc')); ?></p>
        <form action="#" method="post" class="newsletter-mini-form">
            <input type="email" name="nl_email" placeholder="<?php echo esc_attr(globalnews_get_landing_setting('globalnews_sidebar_newsletter_placeholder')); ?>" required>
            <button type="submit"><?php echo esc_html(globalnews_get_landing_setting('globalnews_sidebar_newsletter_button')); ?></button>
        </form>
    </div>
</div>

<div class="sidebar-widget widget-ad">
    <?php globalnews_sidebar_ad(); ?>
</div>
