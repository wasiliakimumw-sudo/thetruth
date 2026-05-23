<?php
/**
 * Front Page / Homepage Template
 *
 * Builds a professional news homepage with:
 * - Hero section (1 large + 4 secondary)
 * - Latest news grid
 * - Category sections (Politics, Business, Technology, Sports, Entertainment, Health, World, Economy)
 * - Video news section
 * - Newsletter section
 * - Sidebar with trending posts
 */

get_header();
?>

<main id="primary" class="site-main site-main-home">

    <?php
    get_template_part('template-parts/hero/hero-section');
    ?>

    <div class="home-content-layout">
        <div class="container">
            <div class="content-with-sidebar">
                <div class="content-area-main">
                    <?php
                    get_template_part('template-parts/news-grid/latest-news');

                    $category_sections = array(
                        'politics'     => esc_html__('Politics', 'globalnews-media'),
                        'business'     => esc_html__('Business', 'globalnews-media'),
                        'technology'   => esc_html__('Technology', 'globalnews-media'),
                        'sports'       => esc_html__('Sports', 'globalnews-media'),
                        'entertainment' => esc_html__('Entertainment', 'globalnews-media'),
                        'health'       => esc_html__('Health', 'globalnews-media'),
                        'world'        => esc_html__('World', 'globalnews-media'),
                        'economy'      => esc_html__('Economy', 'globalnews-media'),
                    );

                    foreach ($category_sections as $slug => $title) :
                        get_template_part('template-parts/category/category-section', null, array(
                            'category_slug' => $slug,
                            'section_title' => $title,
                        ));
                    endforeach;

                    get_template_part('template-parts/video/video-news');
                    ?>
                </div>
                <aside class="sidebar-area" id="sidebarArea">
                    <div class="sidebar-inner">
                        <?php
                        if (is_active_sidebar('sidebar-sticky')) :
                            dynamic_sidebar('sidebar-sticky');
                        else :
                            get_template_part('template-parts/trending/trending-sidebar');
                        endif;
                        ?>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <?php
    get_template_part('template-parts/newsletter/newsletter-section');
    ?>

</main>

<?php
get_footer();
