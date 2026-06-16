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

<div class="sidebar-widget widget-ad">
    <?php globalnews_sidebar_ad(); ?>
</div>
