<?php
/**
 * Category Template
 */

get_header();

$category = get_queried_object();
$category_color = get_term_meta($category->term_id, 'globalnews_category_color', true);
?>

<main id="primary" class="site-main">
    <div class="archive-header-section"<?php echo $category_color ? ' style="border-bottom-color:' . esc_attr($category_color) . ';border-bottom-width:3px;"' : ''; ?>>
        <div class="container">
            <div class="archive-header-content">
                <?php globalnews_breadcrumbs(); ?>
                <h1 class="archive-title"<?php echo $category_color ? ' style="color:' . esc_attr($category_color) . ';"' : ''; ?>><?php single_cat_title(); ?></h1>
                <?php if (category_description()) : ?>
                    <p class="archive-description"><?php echo category_description(); ?></p>
                <?php endif; ?>
                <?php
                $subcategories = get_categories(array('parent' => $category->term_id, 'hide_empty' => false));
                if ($subcategories) : ?>
                    <div class="category-subnav">
                        <?php foreach ($subcategories as $subcat) : ?>
                            <a href="<?php echo esc_url(get_category_link($subcat->term_id)); ?>" class="category-subnav-link"><?php echo esc_html($subcat->name); ?></a>
                        <?php endforeach; ?>
                    </div>
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
                        <p class="no-posts"><?php esc_html_e('No news under this category.', 'globalnews-media'); ?></p>
                    <?php endif; ?>
                </div>
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
