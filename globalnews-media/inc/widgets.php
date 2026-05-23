<?php
/**
 * Custom Widgets
 */

class GlobalNews_Trending_Posts_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct('globalnews_trending', esc_html__('GlobalNews: Trending Posts', 'globalnews-media'), array(
            'description' => esc_html__('Display trending/most viewed posts', 'globalnews-media'),
        ));
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Trending Now', 'globalnews-media');
        echo $args['before_title'] . '<span>' . esc_html($title) . '</span>' . $args['after_title'];
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $query_args = array(
            'posts_per_page' => $number,
            'meta_key'       => 'globalnews_post_views',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
            'ignore_sticky_posts' => 1,
        );
        $trending = new WP_Query($query_args);
        if ($trending->have_posts()) : ?>
            <div class="trending-posts-widget">
                <?php $i = 1; while ($trending->have_posts()) : $trending->the_post(); ?>
                    <article class="trending-item">
                        <span class="trending-number"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></span>
                        <div class="trending-content">
                            <h4 class="trending-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="trending-date"><?php echo get_the_date(); ?></span>
                        </div>
                    </article>
                <?php $i++; endwhile; ?>
            </div>
        <?php endif;
        wp_reset_postdata();
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $number = !empty($instance['number']) ? $instance['number'] : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'globalnews-media'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of posts:', 'globalnews-media'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" value="<?php echo esc_attr($number); ?>" min="1" max="20">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = absint($new_instance['number']);
        return $instance;
    }
}

class GlobalNews_Social_Follow_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct('globalnews_social', esc_html__('GlobalNews: Social Follow', 'globalnews-media'), array(
            'description' => esc_html__('Display social media follow icons', 'globalnews-media'),
        ));
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Follow Us', 'globalnews-media');
        echo $args['before_title'] . '<span>' . esc_html($title) . '</span>' . $args['after_title']; ?>
        <div class="social-follow-widget">
            <a href="#" class="social-icon facebook" target="_blank" rel="noopener noreferrer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                <span><?php esc_html_e('Facebook', 'globalnews-media'); ?></span>
            </a>
            <a href="#" class="social-icon twitter" target="_blank" rel="noopener noreferrer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                <span><?php esc_html_e('X / Twitter', 'globalnews-media'); ?></span>
            </a>
            <a href="#" class="social-icon instagram" target="_blank" rel="noopener noreferrer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                <span><?php esc_html_e('Instagram', 'globalnews-media'); ?></span>
            </a>
            <a href="#" class="social-icon youtube" target="_blank" rel="noopener noreferrer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                <span><?php esc_html_e('YouTube', 'globalnews-media'); ?></span>
            </a>
            <a href="#" class="social-icon linkedin" target="_blank" rel="noopener noreferrer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                <span><?php esc_html_e('LinkedIn', 'globalnews-media'); ?></span>
            </a>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'globalnews-media'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        return $instance;
    }
}

class GlobalNews_Newsletter_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct('globalnews_newsletter', esc_html__('GlobalNews: Newsletter', 'globalnews-media'), array(
            'description' => esc_html__('Display newsletter subscription form', 'globalnews-media'),
        ));
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Stay Informed', 'globalnews-media');
        $desc = !empty($instance['description']) ? $instance['description'] : esc_html__('Get the latest news delivered to your inbox.', 'globalnews-media');
        $placeholder = !empty($instance['placeholder']) ? $instance['placeholder'] : esc_html__('Your email address', 'globalnews-media');
        $btn_text = !empty($instance['btn_text']) ? $instance['btn_text'] : esc_html__('Subscribe', 'globalnews-media');
        ?>
        <div class="newsletter-widget">
            <h4 class="newsletter-widget-title"><?php echo esc_html($title); ?></h4>
            <p class="newsletter-widget-desc"><?php echo esc_html($desc); ?></p>
            <form class="newsletter-widget-form" action="#" method="post">
                <div class="newsletter-input-group">
                    <input type="email" name="newsletter_email" class="newsletter-input" placeholder="<?php echo esc_attr($placeholder); ?>" required>
                    <button type="submit" class="newsletter-btn"><?php echo esc_html($btn_text); ?></button>
                </div>
            </form>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $desc = !empty($instance['description']) ? $instance['description'] : '';
        $placeholder = !empty($instance['placeholder']) ? $instance['placeholder'] : '';
        $btn_text = !empty($instance['btn_text']) ? $instance['btn_text'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'globalnews-media'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('description')); ?>"><?php esc_html_e('Description:', 'globalnews-media'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('description')); ?>" name="<?php echo esc_attr($this->get_field_name('description')); ?>" rows="3"><?php echo esc_textarea($desc); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('placeholder')); ?>"><?php esc_html_e('Input Placeholder:', 'globalnews-media'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('placeholder')); ?>" name="<?php echo esc_attr($this->get_field_name('placeholder')); ?>" type="text" value="<?php echo esc_attr($placeholder); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('btn_text')); ?>"><?php esc_html_e('Button Text:', 'globalnews-media'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('btn_text')); ?>" name="<?php echo esc_attr($this->get_field_name('btn_text')); ?>" type="text" value="<?php echo esc_attr($btn_text); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['description'] = sanitize_textarea_field($new_instance['description']);
        $instance['placeholder'] = sanitize_text_field($new_instance['placeholder']);
        $instance['btn_text'] = sanitize_text_field($new_instance['btn_text']);
        return $instance;
    }
}

function globalnews_register_widgets() {
    register_widget('GlobalNews_Trending_Posts_Widget');
    register_widget('GlobalNews_Social_Follow_Widget');
    register_widget('GlobalNews_Newsletter_Widget');
}
add_action('widgets_init', 'globalnews_register_widgets');

function globalnews_set_post_views($post_id) {
    $count = (int) get_post_meta($post_id, 'globalnews_post_views', true);
    update_post_meta($post_id, 'globalnews_post_views', $count + 1);
}
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);

function globalnews_track_post_views($post_id) {
    if (!is_single()) return;
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    globalnews_set_post_views($post_id);
}
add_action('wp_head', 'globalnews_track_post_views');
