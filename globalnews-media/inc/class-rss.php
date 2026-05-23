<?php

class GlobalNews_RSS {
    private static $instance = null;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_filter('the_excerpt_rss', array($this, 'add_featured_image_to_rss'));
        add_filter('the_content_feed', array($this, 'add_featured_image_to_rss'));
        add_filter('the_title_rss', array($this, 'clean_rss_title'));
        add_action('rss2_item', array($this, 'add_rss_media_content'));
        add_action('rss2_item', array($this, 'add_rss_extra_metadata'));
        add_action('rss_head', array($this, 'add_rss_image_namespace'));
        add_filter('feed_link', array($this, 'add_category_feed_slug'), 10, 2);
        add_action('init', array($this, 'add_custom_feeds'));
    }

    public function add_custom_feeds() {
        $categories = get_categories(array('hide_empty' => true, 'number' => 50));
        foreach ($categories as $cat) {
            add_feed('category/' . $cat->slug, function () use ($cat) {
                $this->render_category_feed($cat);
            });
        }
    }

    public function add_rss_image_namespace() {
        echo "\t" . '<media:xmlns:media="http://search.yahoo.com/mrss/">' . "\n";
    }

    public function add_featured_image_to_rss($content) {
        if (!is_feed()) {
            return $content;
        }
        if (has_post_thumbnail()) {
            $image = get_the_post_thumbnail_url(null, 'full');
            $caption = get_the_post_thumbnail_caption();
            $img_html = '<p><a href="' . get_permalink() . '"><img src="' . esc_url($image) . '" alt="' . esc_attr(get_the_title()) . '" /></a></p>';
            if ($caption) {
                $img_html .= '<p><em>' . esc_html($caption) . '</em></p>';
            }
            $content = $img_html . $content;
        }
        return $content;
    }

    public function add_rss_media_content() {
        if (!has_post_thumbnail()) {
            return;
        }
        $image = get_the_post_thumbnail_url(null, 'full');
        $width = 1200;
        $height = 675;
        $thumbnail_id = get_post_thumbnail_id();
        $meta = wp_get_attachment_metadata($thumbnail_id);
        if ($meta && isset($meta['width'], $meta['height'])) {
            $width = $meta['width'];
            $height = $meta['height'];
        }
        ?>
        <media:content url="<?php echo esc_url($image); ?>" type="image/jpeg" medium="image" width="<?php echo esc_attr($width); ?>" height="<?php echo esc_attr($height); ?>">
            <media:title type="html"><?php echo esc_html(get_the_title()); ?></media:title>
            <media:description type="html"><?php echo esc_html(get_the_excerpt()); ?></media:description>
            <media:credit role="author"><?php the_author(); ?></media:credit>
        </media:content>
        <?php
    }

    public function add_rss_extra_metadata() {
        $post_id = get_the_ID();
        $categories = get_the_category();
        $tags = get_the_tags();
        ?>
        <dc:creator><?php the_author(); ?></dc:creator>
        <?php if (function_exists('get_the_author_meta')) : ?>
            <author><?php echo esc_html(get_the_author_meta('display_name')); ?> (<?php echo esc_html(get_the_author_meta('user_email')); ?>)</author>
        <?php endif; ?>
        <?php foreach ($categories as $cat) : ?>
            <category domain="category"><?php echo esc_html($cat->name); ?></category>
        <?php endforeach; ?>
        <?php if ($tags) : foreach ($tags as $tag) : ?>
            <category domain="tag"><?php echo esc_html($tag->name); ?></category>
        <?php endforeach; endif; ?>
        <comments><?php comments_link_feed(); ?></comments>
        <?php
        $reading_time = globalnews_get_reading_time($post_id);
        ?>
        <readTime><?php echo $reading_time; ?></readTime>
        <?php
        $featured = get_post_meta($post_id, 'globalnews_featured_post', true);
        if ($featured) : ?>
            <featured>true</featured>
        <?php endif;
        $breaking = get_post_meta($post_id, 'globalnews_breaking_news', true);
        if ($breaking) : ?>
            <breaking>true</breaking>
        <?php endif;
    }

    public function clean_rss_title($title) {
        return html_entity_decode($title, ENT_QUOTES, 'UTF-8');
    }

    public function add_category_feed_slug($feed_url, $feed_type) {
        if (is_category() && $feed_type === 'rss2') {
            $cat = get_queried_object();
            if ($cat && isset($cat->slug)) {
                return home_url('/feed/category/' . $cat->slug . '/');
            }
        }
        return $feed_url;
    }

    public function render_category_feed($category) {
        header('Content-Type: application/rss+xml; charset=UTF-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        ?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <title><?php echo esc_html($category->name); ?> | <?php bloginfo('name'); ?></title>
        <atom:link href="<?php echo esc_url(home_url('/feed/category/' . $category->slug . '/')); ?>" rel="self" type="application/rss+xml"/>
        <link><?php echo esc_url(get_category_link($category->term_id)); ?></link>
        <description><?php echo esc_html($category->description ?: sprintf(__('Latest %s news and updates', 'globalnews-media'), $category->name)); ?></description>
        <lastBuildDate><?php echo mysql2date('r', get_lastpostdate('blog')); ?></lastBuildDate>
        <language><?php echo get_locale(); ?></language>
        <sy:updatePeriod>hourly</sy:updatePeriod>
        <sy:updateFrequency>1</sy:updateFrequency>
        <media:xmlns:media="http://search.yahoo.com/mrss/"/>
        <?php
        $posts = get_posts(array(
            'category' => $category->term_id,
            'numberposts' => 50,
            'post_status' => 'publish',
        ));
        foreach ($posts as $post) {
            setup_postdata($post);
            ?>
            <item>
                <title><?php the_title_rss(); ?></title>
                <link><?php the_permalink_rss(); ?></link>
                <pubDate><?php echo mysql2date('r', get_post_time('Y-m-d H:i:s', true)); ?></pubDate>
                <dc:creator><?php the_author(); ?></dc:creator>
                <guid isPermaLink="false"><?php the_guid(); ?></guid>
                <description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
                <content:encoded><![CDATA[<?php the_content_feed('rss2'); ?>]]></content:encoded>
                <?php $this->add_rss_media_content(); ?>
            </item>
            <?php
        }
        wp_reset_postdata();
        ?>
    </channel>
</rss>
<?php
        exit;
    }

    public static function activate() {
        $instance = self::instance();
        $instance->add_custom_feeds();
        flush_rewrite_rules();
    }
}

GlobalNews_RSS::instance();
