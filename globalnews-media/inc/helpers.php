<?php
/**
 * Helper Functions
 */

function globalnews_get_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(wp_strip_all_tags($content));
    $min = ceil($word_count / 200);
    return $min < 1 ? 1 : $min;
}

function globalnews_excerpt($length = 20, $more = '...') {
    add_filter('excerpt_length', function () use ($length) {
        return $length;
    }, 999);
    add_filter('excerpt_more', function () use ($more) {
        return $more;
    });
    $excerpt = get_the_excerpt();
    remove_filter('excerpt_length', function () use ($length) {
        return $length;
    }, 999);
    remove_filter('excerpt_more', function () use ($more) {
        return $more;
    });
    return $excerpt;
}

function globalnews_category_badge($category_id = null) {
    if (!$category_id) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $category_id = $categories[0]->term_id;
        }
    }
    if ($category_id) {
        $cat = get_category($category_id);
        $color = get_term_meta($cat->term_id, 'globalnews_category_color', true);
        $style = $color ? 'style="background-color:' . esc_attr($color) . '"' : '';
        return '<span class="category-badge" ' . $style . '><a href="' . esc_url(get_category_link($cat)) . '">' . esc_html($cat->name) . '</a></span>';
    }
    return '';
}

function globalnews_post_meta() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date('c')),
        esc_html(get_the_date())
    );
    return sprintf(
        '<div class="entry-meta">
            <span class="author-name">%1$s</span>
            <span class="meta-separator">·</span>
            <span class="posted-on">%2$s</span>
            <span class="meta-separator">·</span>
            <span class="reading-time">%3$s</span>
        </div>',
        esc_html(get_the_author()),
        $time_string,
        sprintf(esc_html__('%d min read', 'globalnews-media'), globalnews_get_reading_time())
    );
}

function globalnews_social_share($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $url  = urlencode(get_permalink($post_id));
    $title = urlencode(get_the_title($post_id));
    ?>
    <div class="social-share">
        <span class="share-label"><?php esc_html_e('Share', 'globalnews-media'); ?></span>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener noreferrer" class="share-btn facebook" aria-label="Share on Facebook">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        </a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener noreferrer" class="share-btn twitter" aria-label="Share on Twitter">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url; ?>&title=<?php echo $title; ?>" target="_blank" rel="noopener noreferrer" class="share-btn linkedin" aria-label="Share on LinkedIn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
        </a>
        <a href="https://api.whatsapp.com/send?text=<?php echo $title . '+' . $url; ?>" target="_blank" rel="noopener noreferrer" class="share-btn whatsapp" aria-label="Share on WhatsApp">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </a>
        <a href="mailto:?subject=<?php echo $title; ?>&body=<?php echo $url; ?>" class="share-btn email" aria-label="Share via Email">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </a>
    </div>
    <?php
}

function globalnews_breadcrumbs() {
    if (function_exists('rank_math_the_breadcrumbs')) {
        rank_math_the_breadcrumbs();
        return;
    }
    ?>
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <ol>
            <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'globalnews-media'); ?></a></li>
            <?php if (is_category() || is_single()) : ?>
                <?php $categories = get_the_category(); if (!empty($categories)) : ?>
                    <li><a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>"><?php echo esc_html($categories[0]->name); ?></a></li>
                <?php endif; ?>
                <?php if (is_single()) : ?>
                    <li><span><?php the_title(); ?></span></li>
                <?php endif; ?>
            <?php elseif (is_page()) : ?>
                <li><span><?php the_title(); ?></span></li>
            <?php elseif (is_search()) : ?>
                <li><span><?php printf(esc_html__('Search: %s', 'globalnews-media'), get_search_query()); ?></span></li>
            <?php elseif (is_author()) : ?>
                <li><span><?php printf(esc_html__('Author: %s', 'globalnews-media'), get_the_author()); ?></span></li>
            <?php elseif (is_tag()) : ?>
                <li><span><?php single_tag_title(); ?></span></li>
            <?php elseif (is_404()) : ?>
                <li><span><?php esc_html_e('404 Not Found', 'globalnews-media'); ?></span></li>
            <?php endif; ?>
        </ol>
    </nav>
    <?php
}

function globalnews_toc($content) {
    if (is_single()) {
        $toc = '';
        $pattern = '/<h([2-3])(.*?)>(.*?)<\/h[2-3]>/i';
        if (preg_match_all($pattern, $content, $matches)) {
            $toc .= '<div class="article-toc"><h4 class="toc-title">' . esc_html__('Table of Contents', 'globalnews-media') . '</h4><ul>';
            foreach ($matches[2] as $i => $attrs) {
                $title = strip_tags($matches[3][$i]);
                $id = sanitize_title($title);
                $toc .= '<li class="toc-level-' . $matches[1][$i] . '"><a href="#' . $id . '">' . $title . '</a></li>';
                $content = str_replace($matches[0][$i], '<h' . $matches[1][$i] . ' id="' . $id . '"' . $attrs . '>' . $matches[3][$i] . '</h' . $matches[1][$i] . '>', $content);
            }
            $toc .= '</ul></div>';
            $content = $toc . $content;
        }
    }
    return $content;
}
add_filter('the_content', 'globalnews_toc');

function globalnews_related_posts($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $categories = wp_get_post_categories($post_id);
    $args = array(
        'category__in' => $categories,
        'post__not_in' => array($post_id),
        'posts_per_page' => 4,
        'orderby' => 'rand',
    );
    $related = new WP_Query($args);
    if ($related->have_posts()) : ?>
        <div class="related-posts section">
            <h3 class="section-title"><span><?php esc_html_e('Related Articles', 'globalnews-media'); ?></span></h3>
            <div class="related-grid">
                <?php while ($related->have_posts()) : $related->the_post(); ?>
                    <article class="related-card">
                        <a href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="related-thumb">
                                    <?php the_post_thumbnail('globalnews-grid'); ?>
                                </div>
                            <?php endif; ?>
                            <h4 class="related-title"><?php the_title(); ?></h4>
                            <span class="related-date"><?php echo get_the_date(); ?></span>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif;
    wp_reset_postdata();
}
