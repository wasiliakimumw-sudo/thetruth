<?php

class GlobalNews_Sitemap {
    private static $instance = null;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('template_redirect', array($this, 'handle_sitemap_requests'), 0);
        add_action('save_post', array($this, 'ping_search_engines'), 10, 3);
        add_action('publish_post', array($this, 'ping_google_news'), 10, 2);
    }

    public function handle_sitemap_requests() {
        $uri = isset($_SERVER['REQUEST_URI']) ? urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) : '';
        $uri = trim($uri, '/');
        $site_url = trim(parse_url(home_url(), PHP_URL_PATH) ?: '', '/');
        $uri = preg_replace('#^' . preg_quote($site_url, '#') . '/?#', '', $uri);

        $sitemap_types = array(
            'sitemap.xml'             => 'main',
            'sitemap-posts.xml'       => 'posts',
            'sitemap-pages.xml'       => 'pages',
            'sitemap-categories.xml'  => 'categories',
            'sitemap-tags.xml'        => 'tags',
            'sitemap-authors.xml'     => 'authors',
            'sitemap-news.xml'        => 'news',
        );

        if (isset($sitemap_types[$uri])) {
            $this->render($sitemap_types[$uri]);
            exit;
        }
    }

    private function render($type) {
        if (function_exists('wp_ob_end_flush_all')) {
            while (ob_get_level()) {
                ob_end_clean();
            }
        }
        status_header(200);
        header('Content-Type: application/xml; charset=UTF-8');
        header('X-Robots-Tag: noindex, follow');
        header('Cache-Control: public, max-age=3600');

        $method = 'render_' . $type;
        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    public function render_main() {
        $lastmod = get_lastpostdate('blog');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $sitemaps = array(
            'sitemap-posts.xml'     => __('Posts', 'globalnews-media'),
            'sitemap-pages.xml'     => __('Pages', 'globalnews-media'),
            'sitemap-categories.xml'=> __('Categories', 'globalnews-media'),
            'sitemap-tags.xml'      => __('Tags', 'globalnews-media'),
            'sitemap-authors.xml'   => __('Authors', 'globalnews-media'),
            'sitemap-news.xml'      => __('Google News', 'globalnews-media'),
        );
        foreach ($sitemaps as $file => $name) {
            echo "\t" . '<sitemap>' . "\n";
            echo "\t\t" . '<loc>' . esc_url(home_url('/' . $file)) . '</loc>' . "\n";
            echo "\t\t" . '<lastmod>' . $lastmod . 'T00:00:00+00:00</lastmod>' . "\n";
            echo "\t" . '</sitemap>' . "\n";
        }
        echo '</sitemapindex>' . "\n";
    }

    public function render_posts() {
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 50000,
            'orderby' => 'modified',
            'order' => 'DESC',
            'no_found_rows' => true,
        );
        $query = new WP_Query($args);
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
        while ($query->have_posts()) {
            $query->the_post();
            $thumbnail = get_the_post_thumbnail_url(null, 'full');
            echo "\t" . '<url>' . "\n";
            echo "\t\t" . '<loc>' . esc_url(get_permalink()) . '</loc>' . "\n";
            echo "\t\t" . '<lastmod>' . get_the_modified_date('c') . '</lastmod>' . "\n";
            echo "\t\t" . '<changefreq>hourly</changefreq>' . "\n";
            echo "\t\t" . '<priority>0.8</priority>' . "\n";
            if ($thumbnail) {
                echo "\t\t" . '<image:image>' . "\n";
                echo "\t\t\t" . '<image:loc>' . esc_url($thumbnail) . '</image:loc>' . "\n";
                echo "\t\t\t" . '<image:title>' . esc_xml(get_the_title()) . '</image:title>' . "\n";
                echo "\t\t" . '</image:image>' . "\n";
            }
            echo "\t" . '</url>' . "\n";
        }
        wp_reset_postdata();
        echo '</urlset>' . "\n";
    }

    public function render_pages() {
        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => 50000,
            'orderby' => 'modified',
            'order' => 'DESC',
            'no_found_rows' => true,
        );
        $query = new WP_Query($args);
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        while ($query->have_posts()) {
            $query->the_post();
            echo "\t" . '<url>' . "\n";
            echo "\t\t" . '<loc>' . esc_url(get_permalink()) . '</loc>' . "\n";
            echo "\t\t" . '<lastmod>' . get_the_modified_date('c') . '</lastmod>' . "\n";
            echo "\t\t" . '<changefreq>weekly</changefreq>' . "\n";
            echo "\t\t" . '<priority>0.5</priority>' . "\n";
            echo "\t" . '</url>' . "\n";
        }
        wp_reset_postdata();
        echo '</urlset>' . "\n";
    }

    public function render_news() {
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 1000,
            'orderby' => 'date',
            'order' => 'DESC',
            'date_query' => array(
                'after' => date('Y-m-d', strtotime('-48 hours')),
            ),
            'no_found_rows' => true,
        );
        $query = new WP_Query($args);
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . "\n";
        while ($query->have_posts()) {
            $query->the_post();
            $categories = get_the_category();
            $cat_names = array();
            foreach ($categories as $cat) {
                $cat_names[] = $cat->name;
            }
            $keywords = array();
            $tags = get_the_tags();
            if ($tags) {
                foreach ($tags as $tag) {
                    $keywords[] = $tag->name;
                }
            }
            echo "\t" . '<url>' . "\n";
            echo "\t\t" . '<loc>' . esc_url(get_permalink()) . '</loc>' . "\n";
            echo "\t\t" . '<news:news>' . "\n";
            echo "\t\t\t" . '<news:publication>' . "\n";
            echo "\t\t\t\t" . '<news:name>' . esc_xml(get_bloginfo('name')) . '</news:name>' . "\n";
            echo "\t\t\t\t" . '<news:language>' . get_locale() . '</news:language>' . "\n";
            echo "\t\t\t" . '</news:publication>' . "\n";
            echo "\t\t\t" . '<news:publication_date>' . get_the_date('Y-m-d\TH:i:s\Z') . '</news:publication_date>' . "\n";
            echo "\t\t\t" . '<news:title>' . esc_xml(get_the_title()) . '</news:title>' . "\n";
            if (!empty($keywords)) {
                echo "\t\t\t" . '<news:keywords>' . esc_xml(implode(', ', $keywords)) . '</news:keywords>' . "\n";
            }
            echo "\t\t\t" . '<news:stock_tickers>' . esc_xml(implode(', ', $cat_names)) . '</news:stock_tickers>' . "\n";
            echo "\t\t" . '</news:news>' . "\n";
            echo "\t" . '</url>' . "\n";
        }
        wp_reset_postdata();
        echo '</urlset>' . "\n";
    }

    public function render_categories() {
        $terms = get_terms(array(
            'taxonomy' => 'category',
            'hide_empty' => true,
            'orderby' => 'count',
            'order' => 'DESC',
        ));
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($terms as $term) {
            $last_post = get_posts(array('numberposts' => 1, 'category' => $term->term_id, 'orderby' => 'modified', 'order' => 'DESC'));
            $lastmod = !empty($last_post) ? $last_post[0]->post_modified : current_time('mysql');
            echo "\t" . '<url>' . "\n";
            echo "\t\t" . '<loc>' . esc_url(get_category_link($term->term_id)) . '</loc>' . "\n";
            echo "\t\t" . '<lastmod>' . mysql2date('c', $lastmod) . '</lastmod>' . "\n";
            echo "\t\t" . '<changefreq>daily</changefreq>' . "\n";
            echo "\t\t" . '<priority>0.6</priority>' . "\n";
            echo "\t" . '</url>' . "\n";
        }
        echo '</urlset>' . "\n";
    }

    public function render_tags() {
        $terms = get_terms(array(
            'taxonomy' => 'post_tag',
            'hide_empty' => true,
            'orderby' => 'count',
            'order' => 'DESC',
            'number' => 2000,
        ));
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($terms as $term) {
            echo "\t" . '<url>' . "\n";
            echo "\t\t" . '<loc>' . esc_url(get_tag_link($term->term_id)) . '</loc>' . "\n";
            echo "\t\t" . '<changefreq>weekly</changefreq>' . "\n";
            echo "\t\t" . '<priority>0.3</priority>' . "\n";
            echo "\t" . '</url>' . "\n";
        }
        echo '</urlset>' . "\n";
    }

    public function render_authors() {
        $authors = get_users(array(
            'who' => 'authors',
            'orderby' => 'post_count',
            'order' => 'DESC',
            'has_published_posts' => true,
        ));
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($authors as $author) {
            $last_post = get_posts(array('author' => $author->ID, 'numberposts' => 1, 'orderby' => 'modified', 'order' => 'DESC'));
            $lastmod = !empty($last_post) ? $last_post[0]->post_modified : current_time('mysql');
            echo "\t" . '<url>' . "\n";
            echo "\t\t" . '<loc>' . esc_url(get_author_posts_url($author->ID)) . '</loc>' . "\n";
            echo "\t\t" . '<lastmod>' . mysql2date('c', $lastmod) . '</lastmod>' . "\n";
            echo "\t\t" . '<changefreq>weekly</changefreq>' . "\n";
            echo "\t\t" . '<priority>0.4</priority>' . "\n";
            echo "\t" . '</url>' . "\n";
        }
        echo '</urlset>' . "\n";
    }

    public function ping_search_engines($post_id, $post, $update) {
        if ($post->post_status !== 'publish' || $post->post_type !== 'post') {
            return;
        }
        $engines = array(
            'google' => "https://www.google.com/ping?sitemap=" . urlencode(home_url('/sitemap.xml')),
            'bing'   => "https://www.bing.com/ping?sitemap=" . urlencode(home_url('/sitemap.xml')),
        );
        if (get_option('globalnews_indexnow_enabled', false)) {
            $api_key = get_option('globalnews_indexnow_api_key', '');
            if ($api_key) {
                $engines['indexnow'] = "https://api.indexnow.org/indexnow?url=" . urlencode(get_permalink($post_id)) . "&key=" . $api_key . "&keyLocation=" . urlencode(home_url('/' . $api_key . '.txt'));
            }
        }
        foreach ($engines as $name => $url) {
            wp_remote_get($url, array('timeout' => 5, 'blocking' => false, 'httpversion' => '1.1'));
        }
    }

    public function ping_google_news($post_id, $post) {
        if ($post->post_status !== 'publish') {
            return;
        }
        wp_remote_get("https://www.google.com/ping?sitemap=" . urlencode(home_url('/sitemap-news.xml')), array(
            'timeout' => 5, 'blocking' => false, 'httpversion' => '1.1',
        ));
    }

    public static function activate() {
        flush_rewrite_rules();
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }
}

GlobalNews_Sitemap::instance();
