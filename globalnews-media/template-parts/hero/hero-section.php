<?php
/**
 * Hero Section - Large featured + 4 secondary articles
 */

$hero_args = array(
    'posts_per_page'      => 11,
    'ignore_sticky_posts' => 1,
    'orderby'             => 'date',
    'order'               => 'DESC',
);

$hero_query = new WP_Query($hero_args);

if ($hero_query->have_posts()) :
    $all_posts = $hero_query->posts;
    $slider_posts = array_slice($all_posts, 0, 7);
    $secondary_posts = array_slice($all_posts, 7);
?>
<section class="hero-section section">
    <div class="container">
        <div class="hero-layout">
            <div class="hero-primary">
                <div class="hero-slider" id="heroSlider">
                    <div class="hero-slider-track" id="heroSliderTrack">
                        <?php foreach ($slider_posts as $slide) :
                            $categories = get_the_category($slide->ID);
                        ?>
                        <article class="hero-card hero-main-card hero-slide">
                            <a href="<?php echo esc_url(get_permalink($slide->ID)); ?>" class="hero-card-link">
                                <div class="hero-image-wrapper">
                                    <?php if (has_post_thumbnail($slide->ID)) : ?>
                                        <?php echo get_the_post_thumbnail($slide->ID, 'globalnews-hero', array('class' => 'hero-image', 'loading' => 'lazy')); ?>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url(globalnews_fallback_thumbnail($slide->ID, '1200x675')); ?>" alt="<?php echo esc_attr(get_the_title($slide->ID)); ?>" class="hero-image" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
                                    <?php endif; ?>
                                    <div class="hero-overlay"></div>
                                </div>
                                <div class="hero-content">
                                    <?php if (!empty($categories)) :
                                        echo globalnews_category_badge($categories[0]->term_id);
                                    endif; ?>
                                    <h2 class="hero-title"><?php echo esc_html(get_the_title($slide->ID)); ?></h2>
                                    <p class="hero-excerpt"><?php echo wp_trim_words(get_the_excerpt($slide->ID), 25); ?></p>
                                    <div class="hero-meta">
                                        <span class="hero-author"><?php echo esc_html(get_the_author_meta('display_name', $slide->post_author)); ?></span>
                                        <span class="hero-date"><?php echo get_the_date('', $slide->ID); ?></span>
                                    </div>
                                </div>
                            </a>
                        </article>
                        <?php endforeach; ?>
                    </div>
                    <div class="hero-slider-dots" id="heroSliderDots"></div>
                </div>
            </div>
            <div class="hero-secondary">
                <?php foreach ($secondary_posts as $post) : setup_postdata($post); ?>
                    <article class="hero-card hero-secondary-card">
                        <a href="<?php the_permalink(); ?>" class="hero-card-link">
                            <div class="hero-image-wrapper">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('globalnews-featured', array('class' => 'hero-image', 'loading' => 'lazy')); ?>
                                <?php else : ?>
                                    <img src="<?php echo esc_url(globalnews_fallback_thumbnail(get_the_ID(), '600x400')); ?>" alt="<?php the_title_attribute(); ?>" class="hero-image" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
                                <?php endif; ?>
                                <div class="hero-overlay"></div>
                            </div>
                            <div class="hero-content">
                                <?php echo globalnews_category_badge(); ?>
                                <h3 class="hero-title hero-title-sm"><?php the_title(); ?></h3>
                                <div class="hero-meta">
                                    <span class="hero-date"><?php echo get_the_date(); ?></span>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php
wp_reset_postdata();
endif;
?>
