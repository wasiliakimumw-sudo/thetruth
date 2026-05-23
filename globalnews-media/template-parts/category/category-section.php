<?php
/**
 * Category Section Template Part
 *
 * @param string $category_slug The category slug to display
 * @param string $section_title Optional custom section title
 */

$category_slug  = isset($args['category_slug']) ? $args['category_slug'] : '';
$section_title  = isset($args['section_title']) ? $args['section_title'] : '';

if (empty($category_slug)) return;

$category = get_category_by_slug($category_slug);
if (!$category) return;

$cat_link = get_category_link($category->term_id);

$featured_args = array(
    'posts_per_page'      => 1,
    'category_name'       => $category_slug,
    'ignore_sticky_posts' => 1,
);
$featured = new WP_Query($featured_args);

$list_args = array(
    'posts_per_page'      => 4,
    'category_name'       => $category_slug,
    'offset'              => 1,
    'ignore_sticky_posts' => 1,
);
$list_query = new WP_Query($list_args);

if ($featured->have_posts()) :
?>
<section class="category-section section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><span><?php echo esc_html($section_title ?: $category->name); ?></span></h2>
            <a href="<?php echo esc_url($cat_link); ?>" class="section-link">
                <?php esc_html_e('View More', 'globalnews-media'); ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
        </div>
        <div class="category-layout">
            <div class="category-featured">
                <?php while ($featured->have_posts()) : $featured->the_post(); ?>
                    <article class="category-featured-card">
                        <a href="<?php the_permalink(); ?>">
                            <div class="category-featured-thumb">
                                <?php the_post_thumbnail('globalnews-hero', array('loading' => 'lazy')); ?>
                            </div>
                            <div class="category-featured-content">
                                <?php echo globalnews_category_badge(); ?>
                                <h3 class="category-featured-title"><?php the_title(); ?></h3>
                                <p class="category-featured-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                                <?php echo globalnews_post_meta(); ?>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
            <div class="category-list">
                <?php if ($list_query->have_posts()) : while ($list_query->have_posts()) : $list_query->the_post(); ?>
                    <article class="category-list-item">
                        <a href="<?php the_permalink(); ?>" class="category-list-link">
                            <div class="category-list-thumb">
                                <?php the_post_thumbnail('globalnews-thumb', array('loading' => 'lazy')); ?>
                            </div>
                            <div class="category-list-content">
                                <h4 class="category-list-title"><?php the_title(); ?></h4>
                                <?php echo globalnews_post_meta(); ?>
                            </div>
                        </a>
                    </article>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </div>
</section>
<?php
wp_reset_postdata();
endif;
?>
