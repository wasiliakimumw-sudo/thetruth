<?php
/**
 * Theme Setup
 */

function globalnews_theme_setup() {
    load_theme_textdomain('globalnews-media', GLOBALNEWS_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets'
    ));
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('wp-block-styles');
    add_theme_support('automatic-feed-links');
    add_theme_support('post-formats', array('video', 'gallery', 'audio', 'quote'));

    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor.css');

    set_post_thumbnail_size(800, 500, true);
    add_image_size('globalnews-hero', 1200, 675, true);
    add_image_size('globalnews-featured', 800, 500, true);
    add_image_size('globalnews-grid', 600, 400, true);
    add_image_size('globalnews-thumb', 150, 100, true);
    add_image_size('globalnews-author', 120, 120, true);

    register_nav_menus(array(
        'primary'     => esc_html__('Main Navigation', 'globalnews-media'),
        'secondary'   => esc_html__('Top Bar Menu', 'globalnews-media'),
        'footer'      => esc_html__('Footer Menu', 'globalnews-media'),
        'mobile'      => esc_html__('Mobile Menu', 'globalnews-media'),
        'social'      => esc_html__('Social Menu', 'globalnews-media'),
    ));
}
add_action('after_setup_theme', 'globalnews_theme_setup');

function globalnews_content_width() {
    $GLOBALS['content_width'] = apply_filters('globalnews_content_width', 1200);
}
add_action('after_setup_theme', 'globalnews_content_width', 0);

function globalnews_create_default_categories() {
    $default_cats = array(
        'politics'     => 'Politics',
        'business'     => 'Business',
        'technology'   => 'Technology',
        'sports'       => 'Sports',
        'entertainment' => 'Entertainment',
        'health'       => 'Health',
        'world'        => 'World',
        'economy'      => 'Economy',
    );
    foreach ($default_cats as $slug => $name) {
        if (!get_category_by_slug($slug)) {
            wp_insert_term(
                $name,
                'category',
                array(
                    'slug'        => $slug,
                    'description' => sprintf('Latest %s news and updates', $name),
                )
            );
        }
    }
    update_option('globalnews_categories_created', true);
}
add_action('after_switch_theme', 'globalnews_create_default_categories');

function globalnews_create_sample_posts() {
    if (get_option('globalnews_sample_content_created')) {
        return;
    }

    globalnews_create_default_categories();

    $samples = array(
        array(
            'title'   => 'Global Markets Surge as Tech Giants Report Strong Quarterly Earnings',
            'content' => 'Global financial markets experienced a significant uptick today as major technology companies reported better-than-expected quarterly earnings. The positive sentiment was driven by strong performance in cloud computing and artificial intelligence sectors, with several companies surpassing analyst projections. Market analysts attribute the growth to increased digital transformation efforts across industries, coupled with robust consumer spending in the tech sector. The rally was led by semiconductor companies and cloud service providers, which saw their stocks reach new highs. Investors remain cautiously optimistic about the sustainability of this growth trajectory, citing potential regulatory challenges and geopolitical uncertainties ahead.',
            'category' => 'business',
        ),
        array(
            'title'   => 'Parliament Passes Landmark Climate Legislation in Historic Session',
            'content' => 'In a historic session that lasted through the night, parliament passed comprehensive climate legislation aimed at reducing carbon emissions by 50% by 2035. The bill, which received bipartisan support, includes provisions for renewable energy expansion, electric vehicle infrastructure, and carbon capture technology. Environmental groups have praised the legislation as a crucial step toward meeting international climate commitments. The new law will establish a carbon trading system, provide tax incentives for green energy adoption, and create thousands of jobs in the renewable energy sector. Implementation will begin next quarter, with full enforcement expected within two years.',
            'category' => 'politics',
        ),
        array(
            'title'   => 'Revolutionary AI Model Promises Breakthrough in Medical Diagnostics',
            'content' => 'Scientists at leading research institutions have unveiled a groundbreaking artificial intelligence model that demonstrates unprecedented accuracy in medical diagnostics. The AI system, trained on millions of medical images and patient records, can detect early signs of diseases including cancer, cardiovascular conditions, and neurological disorders with over 95% accuracy. Clinical trials are scheduled to begin next month across major hospitals worldwide. The technology represents a significant leap forward in preventive medicine and could potentially save millions of lives through early detection. Researchers emphasize that the AI is designed to assist, not replace, healthcare professionals.',
            'category' => 'technology',
        ),
        array(
            'title'   => 'Championship Finals Set to Break Viewership Records Worldwide',
            'content' => 'The upcoming championship finals are expected to draw unprecedented global audiences, with broadcasters reporting record advertising sales. The event, featuring top-ranked teams from around the world, has generated immense excitement among fans. Stadium capacity has been expanded, and thousands of viewing parties are being organized across major cities. Analysts project that the finals could surpass previous viewership records by a significant margin, driven by increased streaming accessibility and global interest. The economic impact on the host city is estimated to exceed one billion dollars, providing a substantial boost to local businesses and tourism.',
            'category' => 'sports',
        ),
        array(
            'title'   => 'Award-Winning Director Announces Upcoming Blockbuster Film Series',
            'content' => 'Academy Award-winning director announced an ambitious new film series that promises to redefine cinematic storytelling. The multi-film project, backed by a major studio with a budget exceeding $500 million, will combine cutting-edge visual effects with compelling narrative arcs. Casting rumors have sparked intense speculation across entertainment media, with several A-list actors reportedly in negotiations. The first installment is scheduled for release during the holiday season, with subsequent films planned over the next three years. Industry insiders predict the series could become one of the highest-grossing franchises in cinema history.',
            'category' => 'entertainment',
        ),
        array(
            'title'   => 'New Study Reveals Key Factors for Longevity and Healthy Aging',
            'content' => 'A comprehensive long-term study published in a leading medical journal has identified five key lifestyle factors that significantly contribute to healthy aging and longevity. Researchers tracked over 100,000 participants for three decades, finding that regular physical activity, balanced nutrition, adequate sleep, stress management, and strong social connections can extend life expectancy by up to 14 years. The study provides compelling evidence that lifestyle modifications at any age can produce meaningful health benefits. Public health officials are incorporating these findings into new guidelines for preventive care and wellness programs.',
            'category' => 'health',
        ),
        array(
            'title'   => 'International Summit Addresses Global Economic Cooperation Framework',
            'content' => 'World leaders gathered at the annual international summit to discuss a new framework for global economic cooperation. The summit, attended by representatives from over 60 nations, focused on addressing pressing challenges including supply chain resilience, digital trade regulations, and sustainable development goals. A joint declaration was issued outlining commitments to reduce trade barriers, enhance technology transfer, and coordinate monetary policies. The agreement is expected to boost global GDP growth and foster innovation across emerging economies. Implementation will be monitored by a newly established international task force.',
            'category' => 'world',
        ),
        array(
            'title'   => 'Digital Currency Adoption Accelerates as Central Banks Launch Pilot Programs',
            'content' => 'Central banks across major economies are accelerating their digital currency initiatives, with several launching pilot programs this quarter. The move toward central bank digital currencies aims to modernize payment systems, improve financial inclusion, and enhance monetary policy effectiveness. Early results from pilot programs show promising improvements in transaction efficiency and reduced costs for cross-border payments. Financial experts predict that widespread adoption of digital currencies could fundamentally transform the global financial landscape, though concerns about privacy and cybersecurity remain topics of active discussion among regulators and industry stakeholders.',
            'category' => 'economy',
        ),
    );

    foreach ($samples as $sample) {
        $category = get_category_by_slug($sample['category']);
        if (!$category) continue;

        $post_data = array(
            'post_title'    => $sample['title'],
            'post_content'  => $sample['content'],
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_category' => array($category->term_id),
        );
        wp_insert_post($post_data);
    }

    update_option('globalnews_sample_content_created', true);
}
add_action('after_switch_theme', 'globalnews_create_sample_posts');
if (!get_option('globalnews_sample_content_created')) {
    add_action('init', 'globalnews_create_sample_posts');
}

function globalnews_register_sidebars() {
    register_sidebar(array(
        'name'          => esc_html__('Main Sidebar', 'globalnews-media'),
        'id'            => 'sidebar-main',
        'description'   => esc_html__('Main sidebar widget area', 'globalnews-media'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title"><span>',
        'after_title'   => '</span></h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Sticky Sidebar', 'globalnews-media'),
        'id'            => 'sidebar-sticky',
        'description'   => esc_html__('Sticky sidebar for trending posts', 'globalnews-media'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title"><span>',
        'after_title'   => '</span></h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column 1', 'globalnews-media'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Footer first column', 'globalnews-media'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column 2', 'globalnews-media'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Footer second column', 'globalnews-media'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column 3', 'globalnews-media'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Footer third column', 'globalnews-media'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column 4', 'globalnews-media'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Footer fourth column', 'globalnews-media'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'globalnews_register_sidebars');
