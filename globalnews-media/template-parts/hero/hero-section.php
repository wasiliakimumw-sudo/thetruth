<?php
/**
 * Hero Section - Large featured + 4 secondary articles
 */

$hero_args = array(
    'posts_per_page'      => 5,
    'ignore_sticky_posts' => 1,
    'meta_key'            => 'globalnews_featured_post',
    'meta_value'          => '1',
    'orderby'             => 'date',
    'order'               => 'DESC',
);

$hero_query = new WP_Query($hero_args);

if ($hero_query->have_posts()) :
    $posts = $hero_query->posts;
    $primary = array_shift($posts);
?>
<section class="hero-section section">
    <div class="container">
        <div class="hero-layout">
            <div class="hero-primary">
                <article class="hero-card hero-main-card">
                    <a href="<?php echo esc_url(get_permalink($primary->ID)); ?>" class="hero-card-link">
                        <div class="hero-image-wrapper">
                            <?php echo get_the_post_thumbnail($primary->ID, 'globalnews-hero', array('class' => 'hero-image', 'loading' => 'lazy')); ?>
                            <div class="hero-overlay"></div>
                        </div>
                        <div class="hero-content">
                            <?php
                            $categories = get_the_category($primary->ID);
                            if (!empty($categories)) :
                                echo globalnews_category_badge($categories[0]->term_id);
                            endif;
                            ?>
                            <h2 class="hero-title"><?php echo esc_html(get_the_title($primary->ID)); ?></h2>
                            <p class="hero-excerpt"><?php echo wp_trim_words(get_the_excerpt($primary->ID), 25); ?></p>
                            <div class="hero-meta">
                                <span class="hero-author"><?php echo esc_html(get_the_author_meta('display_name', $primary->post_author)); ?></span>
                                <span class="hero-date"><?php echo get_the_date('', $primary->ID); ?></span>
                            </div>
                        </div>
                    </a>
                </article>
            </div>
            <div class="hero-secondary">
                <?php foreach ($posts as $post) : setup_postdata($post); ?>
                    <article class="hero-card hero-secondary-card">
                        <a href="<?php the_permalink(); ?>" class="hero-card-link">
                            <div class="hero-image-wrapper">
                                <?php the_post_thumbnail('globalnews-featured', array('class' => 'hero-image', 'loading' => 'lazy')); ?>
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
