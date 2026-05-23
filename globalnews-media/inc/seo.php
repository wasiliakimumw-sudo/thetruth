<?php
/**
 * SEO Functions
 */

function globalnews_seo_meta_tags() {
    if (function_exists('rank_math_the_breadcrumbs')) {
        return;
    }
    ?>
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="<?php echo get_locale(); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@<?php bloginfo('name'); ?>">
    <?php
    if (is_single() || is_page()) {
        global $post;
        setup_postdata($post);
        $excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 30);
        ?>
        <meta property="og:title" content="<?php echo esc_attr(get_the_title()); ?>">
        <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
        <meta property="og:description" content="<?php echo esc_attr($excerpt); ?>">
        <meta name="description" content="<?php echo esc_attr($excerpt); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url(null, 'full')); ?>">
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="675">
        <?php endif; ?>
        <meta property="article:published_time" content="<?php echo get_the_date('c'); ?>">
        <meta property="article:author" content="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
        <?php
        $categories = get_the_category();
        foreach ($categories as $cat) {
            echo '<meta property="article:section" content="' . esc_attr($cat->name) . '">' . "\n";
        }
        wp_reset_postdata();
    } elseif (is_home() || is_front_page()) {
        ?>
        <meta property="og:title" content="<?php bloginfo('name'); ?>">
        <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>">
        <meta property="og:description" content="<?php bloginfo('description'); ?>">
        <meta name="description" content="<?php bloginfo('description'); ?>">
        <?php
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
            if ($logo) {
                echo '<meta property="og:image" content="' . esc_url($logo[0]) . '">' . "\n";
            }
        }
    } elseif (is_category()) {
        $cat = get_queried_object();
        ?>
        <meta property="og:title" content="<?php echo esc_attr(single_cat_title('', false)); ?>">
        <meta property="og:url" content="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
        <meta property="og:description" content="<?php echo esc_attr(category_description()); ?>">
        <meta name="description" content="<?php echo esc_attr(category_description()); ?>">
    <?php
    } elseif (is_author()) {
        $author = get_queried_object();
        ?>
        <meta property="og:title" content="<?php echo esc_attr(get_the_author_meta('display_name', $author->ID)); ?>">
        <meta property="og:url" content="<?php echo esc_url(get_author_posts_url($author->ID)); ?>">
        <meta name="description" content="<?php echo esc_attr(get_the_author_meta('description', $author->ID)); ?>">
    <?php
    } elseif (is_search()) {
        ?>
        <meta name="description" content="<?php printf(esc_attr__('Search results for: %s', 'globalnews-media'), get_search_query()); ?>">
    <?php
    }
}
add_action('wp_head', 'globalnews_seo_meta_tags', 1);

function globalnews_schema_article() {
    if (!is_single()) return;
    global $post;
    setup_postdata($post);
    $categories = get_the_category();
    $cat_names = array();
    foreach ($categories as $cat) {
        $cat_names[] = $cat->name;
    }
    $image = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : get_theme_mod('globalnews_default_image', '');
    ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NewsArticle",
        "headline": "<?php echo esc_js(get_the_title()); ?>",
        "url": "<?php echo esc_js(get_permalink()); ?>",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "<?php echo esc_js(get_permalink()); ?>"
        },
        "datePublished": "<?php echo get_the_date('c'); ?>",
        "dateModified": "<?php echo get_the_modified_date('c'); ?>",
        "description": "<?php echo esc_js(has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 30)); ?>",
        "author": {
            "@type": "Person",
            "name": "<?php echo esc_js(get_the_author()); ?>",
            "url": "<?php echo esc_js(get_author_posts_url(get_the_author_meta('ID'))); ?>"
        },
        "publisher": {
            "@type": "Organization",
            "name": "<?php bloginfo('name'); ?>",
            "logo": {
                "@type": "ImageObject",
                "url": "<?php echo esc_js(get_theme_mod('custom_logo') ? wp_get_attachment_url(get_theme_mod('custom_logo')) : ''); ?>"
            }
        },
        "image": {
            "@type": "ImageObject",
            "url": "<?php echo esc_js($image); ?>"
        },
        "articleSection": "<?php echo esc_js(implode(', ', $cat_names)); ?>",
        "wordCount": "<?php echo str_word_count(wp_strip_all_tags($post->post_content)); ?>"
    }
    </script>
    <?php
    wp_reset_postdata();
}
add_action('wp_footer', 'globalnews_schema_article');

function globalnews_schema_organization() {
    $logo = get_theme_mod('custom_logo') ? wp_get_attachment_url(get_theme_mod('custom_logo')) : '';
    ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NewsMediaOrganization",
        "name": "<?php bloginfo('name'); ?>",
        "url": "<?php echo esc_js(home_url('/')); ?>",
        "logo": "<?php echo esc_js($logo); ?>",
        "sameAs": [
            "<?php echo esc_js(get_theme_mod('globalnews_social_facebook', '')); ?>",
            "<?php echo esc_js(get_theme_mod('globalnews_social_twitter', '')); ?>",
            "<?php echo esc_js(get_theme_mod('globalnews_social_instagram', '')); ?>",
            "<?php echo esc_js(get_theme_mod('globalnews_social_youtube', '')); ?>",
            "<?php echo esc_js(get_theme_mod('globalnews_social_linkedin', '')); ?>"
        ]
    }
    </script>
    <?php
}
add_action('wp_footer', 'globalnews_schema_organization', 5);
