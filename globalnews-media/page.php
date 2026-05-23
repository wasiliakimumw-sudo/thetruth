<?php
/**
 * Page Template
 */

get_header();

while (have_posts()) :
    the_post();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="page-header-section">
        <div class="container">
            <div class="page-header-content">
                <?php globalnews_breadcrumbs(); ?>
                <h1 class="page-title"><?php the_title(); ?></h1>
            </div>
        </div>
    </div>

    <div class="page-body-section">
        <div class="container">
            <div class="page-layout">
                <div class="page-content-area">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="page-featured-image">
                            <?php the_post_thumbnail('full', array('class' => 'page-featured-img', 'loading' => 'lazy')); ?>
                        </div>
                    <?php endif; ?>
                    <div class="page-content entry-content">
                        <?php
                        the_content();
                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'globalnews-media'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>
                </div>
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</article>

<?php
endwhile;

get_footer();
