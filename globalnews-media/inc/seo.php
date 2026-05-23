<?php

function globalnews_seo_meta_tags() {
    if (function_exists('rank_math_the_breadcrumbs')) {
        return;
    }
    global $post;
    $desc = get_bloginfo('description');
    ?>
    <meta name="description" content="<?php echo esc_attr(wp_strip_all_tags($desc, true)); ?>">
    <link rel="canonical" href="<?php echo esc_url(wp_get_canonical_url() ?: home_url('/')); ?>">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="<?php echo get_locale(); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@<?php bloginfo('name'); ?>">
    <?php
    if (is_single() || is_page()) {
        setup_postdata($post);
        $excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 30);
        $thumbnail_url = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : '';
        $thumbnail_id = get_post_thumbnail_id();
        $image_meta = $thumbnail_id ? wp_get_attachment_metadata($thumbnail_id) : array();
        ?>
        <link rel="canonical" href="<?php echo esc_url(get_permalink()); ?>">
        <meta property="og:title" content="<?php echo esc_attr(get_the_title()); ?>">
        <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
        <meta property="og:description" content="<?php echo esc_attr($excerpt); ?>">
        <meta name="description" content="<?php echo esc_attr($excerpt); ?>">
        <?php if ($thumbnail_url) : ?>
            <meta property="og:image" content="<?php echo esc_url($thumbnail_url); ?>">
            <meta property="og:image:width" content="<?php echo esc_attr($image_meta['width'] ?? 1200); ?>">
            <meta property="og:image:height" content="<?php echo esc_attr($image_meta['height'] ?? 675); ?>">
        <?php endif; ?>
        <meta property="article:published_time" content="<?php echo get_the_date('c'); ?>">
        <meta property="article:modified_time" content="<?php echo get_the_modified_date('c'); ?>">
        <meta property="article:author" content="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
        <?php
        $categories = get_the_category();
        foreach ($categories as $cat) {
            echo "\t\t" . '<meta property="article:section" content="' . esc_attr($cat->name) . '">' . "\n";
        }
        if (get_post_meta(get_the_ID(), 'globalnews_breaking_news', true)) {
            echo "\t\t" . '<meta name="googlebot-news" content="index">' . "\n";
        }
        wp_reset_postdata();
    } elseif (is_home() || is_front_page()) {
        ?>
        <meta property="og:title" content="<?php bloginfo('name'); ?> <?php echo $desc ? '— ' . esc_attr($desc) : ''; ?>">
        <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>">
        <meta property="og:description" content="<?php bloginfo('description'); ?>">
        <?php
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
            if ($logo) {
                echo "\t\t" . '<meta property="og:image" content="' . esc_url($logo[0]) . '">' . "\n";
            }
        }
    } elseif (is_category()) {
        $cat = get_queried_object();
        $cat_desc = category_description();
        ?>
        <link rel="canonical" href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
        <meta property="og:title" content="<?php echo esc_attr(single_cat_title('', false)); ?>">
        <meta property="og:url" content="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
        <meta property="og:description" content="<?php echo esc_attr(wp_strip_all_tags($cat_desc)); ?>">
        <meta name="description" content="<?php echo esc_attr(wp_strip_all_tags($cat_desc)); ?>">
    <?php
    } elseif (is_tag()) {
        $tag = get_queried_object();
        $tag_desc = tag_description();
        ?>
        <link rel="canonical" href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>">
        <meta property="og:title" content="<?php echo esc_attr(single_tag_title('', false)); ?>">
        <meta name="description" content="<?php echo esc_attr(wp_strip_all_tags($tag_desc ?: sprintf('Articles tagged: %s', $tag->name))); ?>">
    <?php
    } elseif (is_author()) {
        $author = get_queried_object();
        ?>
        <link rel="canonical" href="<?php echo esc_url(get_author_posts_url($author->ID)); ?>">
        <meta property="og:title" content="<?php echo esc_attr(get_the_author_meta('display_name', $author->ID)); ?>">
        <meta property="og:url" content="<?php echo esc_url(get_author_posts_url($author->ID)); ?>">
        <meta name="description" content="<?php echo esc_attr(get_the_author_meta('description', $author->ID) ?: sprintf('Articles by %s', get_the_author_meta('display_name', $author->ID))); ?>">
    <?php
    } elseif (is_search()) {
        ?>
        <meta name="robots" content="noindex, follow">
        <meta name="description" content="<?php printf(esc_attr__('Search results for: %s', 'globalnews-media'), get_search_query()); ?>">
    <?php
    } elseif (is_404()) {
        ?>
        <meta name="robots" content="noindex, follow">
    <?php
    } elseif (is_date()) {
        ?>
        <meta name="robots" content="noindex, follow">
    <?php
    }
}
add_action('wp_head', 'globalnews_seo_meta_tags', 1);

function globalnews_schema_breadcrumbs() {
    if (function_exists('rank_math_the_breadcrumbs')) {
        return;
    }
    if (is_front_page() || is_404()) {
        return;
    }
    $crumbs = array();
    $crumbs[] = array(
        '@type' => 'ListItem',
        'position' => 1,
        'name' => get_bloginfo('name'),
        'item' => home_url('/'),
    );
    if (is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $crumbs[] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => esc_js($categories[0]->name),
                'item' => esc_url(get_category_link($categories[0]->term_id)),
            );
        }
        $crumbs[] = array(
            '@type' => 'ListItem',
            'position' => count($crumbs) + 1,
            'name' => esc_js(get_the_title()),
            'item' => esc_url(get_permalink()),
        );
    } elseif (is_page()) {
        $crumbs[] = array(
            '@type' => 'ListItem',
            'position' => 2,
            'name' => esc_js(get_the_title()),
            'item' => esc_url(get_permalink()),
        );
    } elseif (is_category()) {
        $cat = get_queried_object();
        $crumbs[] = array(
            '@type' => 'ListItem',
            'position' => 2,
            'name' => esc_js($cat->name),
            'item' => esc_url(get_category_link($cat->term_id)),
        );
    } elseif (is_tag()) {
        $tag = get_queried_object();
        $crumbs[] = array(
            '@type' => 'ListItem',
            'position' => 2,
            'name' => esc_js($tag->name),
            'item' => esc_url(get_tag_link($tag->term_id)),
        );
    } elseif (is_author()) {
        $crumbs[] = array(
            '@type' => 'ListItem',
            'position' => 2,
            'name' => esc_js(get_the_author_meta('display_name', get_queried_object_id())),
            'item' => esc_url(get_author_posts_url(get_queried_object_id())),
        );
    }
    ?>
    <script type="application/ld+json">
    {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":<?php echo json_encode($crumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>}
    </script>
    <?php
}
add_action('wp_footer', 'globalnews_schema_breadcrumbs', 4);

function globalnews_schema_article() {
    if (!is_single()) {
        return;
    }
    global $post;
    setup_postdata($post);
    $categories = get_the_category();
    $cat_names = array();
    foreach ($categories as $cat) {
        $cat_names[] = $cat->name;
    }
    $image = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : get_theme_mod('globalnews_default_image', '');
    $author_id = get_the_author_meta('ID');
    $author_image = get_avatar_url($author_id, array('size' => 96));
    $excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 30);
    $word_count = str_word_count(wp_strip_all_tags($post->post_content));
    $reading_time = $word_count ? max(1, ceil($word_count / 200)) : 1;
    $logo = get_theme_mod('custom_logo') ? wp_get_attachment_url(get_theme_mod('custom_logo')) : '';
    $is_breaking = get_post_meta(get_the_ID(), 'globalnews_breaking_news', true);
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
        "description": "<?php echo esc_js($excerpt); ?>",
        "articleSection": "<?php echo esc_js(implode(', ', $cat_names)); ?>",
        "wordCount": "<?php echo esc_js((string) $word_count); ?>",
        "inLanguage": "<?php echo get_locale(); ?>",
        "isAccessibleForFree": true,
        "hasPart": {
            "@type": "WebPageElement",
            "isAccessibleForFree": true,
            "cssSelector": ".article-content"
        },
        "speakable": {
            "@type": "SpeakableSpecification",
            "cssSelector": [".article-title", ".article-content p:first-of-type"]
        },
        "author": {
            "@type": "Person",
            "name": "<?php echo esc_js(get_the_author()); ?>",
            "url": "<?php echo esc_js(get_author_posts_url($author_id)); ?>",
            "image": "<?php echo esc_js($author_image); ?>"
        },
        "publisher": {
            "@type": "Organization",
            "name": "<?php bloginfo('name'); ?>",
            "logo": {
                "@type": "ImageObject",
                "url": "<?php echo esc_js($logo); ?>",
                "width": 240,
                "height": 60
            }
        },
        "image": {
            "@type": "ImageObject",
            "url": "<?php echo esc_js($image); ?>"
        },
        "timeRequired": "PT<?php echo $reading_time; ?>M",
        "backstory": "<?php echo esc_js($excerpt); ?>"
        <?php if ($is_breaking) : ?>,
        "priority": "Breaking",
        "contentLocation": "Global"
        <?php endif; ?>
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
        ],
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+1-234-567-890",
            "contactType": "newsroom"
        },
        "foundingDate": "2024",
        "numberOfEmployees": {
            "@type": "QuantitativeValue",
            "minValue": 10,
            "maxValue": 50
        }
    }
    </script>
    <?php
}
add_action('wp_footer', 'globalnews_schema_organization', 5);

function globalnews_schema_webpage() {
    if (is_front_page()) {
        return;
    }
    ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "@id": "<?php echo esc_js(wp_get_canonical_url() ?: home_url('/')); ?>",
        "url": "<?php echo esc_js(wp_get_canonical_url() ?: home_url('/')); ?>",
        "name": "<?php echo esc_js(wp_get_document_title()); ?>",
        "description": "<?php echo esc_js(get_bloginfo('description')); ?>",
        "inLanguage": "<?php echo get_locale(); ?>",
        "isPartOf": {
            "@id": "<?php echo esc_js(home_url('/')); ?>"
        }
    }
    </script>
    <?php
}
add_action('wp_footer', 'globalnews_schema_webpage', 6);

function globalnews_schema_faq_add($content) {
    if (!is_single()) {
        return $content;
    }
    global $post;
    $has_faq = get_post_meta($post->ID, 'globalnews_has_faq', true);
    if ($has_faq) {
        $faq_items = get_post_meta($post->ID, 'globalnews_faq_items', true) ?: array();
        if (!empty($faq_items)) {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => array(),
            );
            foreach ($faq_items as $item) {
                $schema['mainEntity'][] = array(
                    '@type' => 'Question',
                    'name' => $item['question'],
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => $item['answer'],
                    ),
                );
            }
            $content .= '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
        }
    }
    return $content;
}
add_filter('the_content', 'globalnews_schema_faq_add', 999);

function globalnews_google_news_keywords() {
    if (!is_single()) {
        return;
    }
    $tags = get_the_tags();
    if ($tags) {
        $tag_names = array();
        foreach ($tags as $tag) {
            $tag_names[] = $tag->name;
        }
        echo "\t" . '<meta name="news_keywords" content="' . esc_attr(implode(', ', $tag_names)) . '">' . "\n";
    }
}
add_action('wp_head', 'globalnews_google_news_keywords', 2);

function globalnews_search_console_verification() {
    $verification = get_theme_mod('globalnews_google_verification', '');
    if ($verification) {
        echo "\t" . '<meta name="google-site-verification" content="' . esc_attr($verification) . '">' . "\n";
    }
    $bing_verification = get_theme_mod('globalnews_bing_verification', '');
    if ($bing_verification) {
        echo "\t" . '<meta name="msvalidate.01" content="' . esc_attr($bing_verification) . '">' . "\n";
    }
    $yandex_verification = get_theme_mod('globalnews_yandex_verification', '');
    if ($yandex_verification) {
        echo "\t" . '<meta name="yandex-verification" content="' . esc_attr($yandex_verification) . '">' . "\n";
    }
}
add_action('wp_head', 'globalnews_search_console_verification', 0);
