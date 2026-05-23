<?php

class GlobalNews_Workflow {
    private static $instance = null;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', array($this, 'register_post_statuses'));
        add_filter('display_post_states', array($this, 'display_post_states'), 10, 2);
        add_action('admin_footer-edit.php', array($this, 'add_status_dropdown'));
        add_action('admin_footer-post.php', array($this, 'add_status_js'));
        add_action('admin_footer-post-new.php', array($this, 'add_status_js'));
        add_action('transition_post_status', array($this, 'notify_editor'), 10, 3);
        add_filter('wp_insert_post_data', array($this, 'check_editor_approval'), 10, 2);
        add_action('save_post', array($this, 'save_editor_notes'), 10, 3);
        add_action('add_meta_boxes', array($this, 'add_workflow_metabox'));
        add_action('admin_menu', array($this, 'add_pending_queue_page'));
        add_filter('pre_get_posts', array($this, 'journalist_filter_posts'));
    }

    public function register_post_statuses() {
        register_post_status('pending_review', array(
            'label' => _x('Pending Review', 'post status', 'globalnews-media'),
            'public' => false,
            'internal' => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list' => true,
            'label_count' => _n_noop('Pending Review <span class="count">(%s)</span>', 'Pending Review <span class="count">(%s)</span>', 'globalnews-media'),
        ));
        register_post_status('draft_review', array(
            'label' => _x('Draft Review', 'post status', 'globalnews-media'),
            'public' => false,
            'internal' => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list' => true,
            'label_count' => _n_noop('Draft Review <span class="count">(%s)</span>', 'Draft Review <span class="count">(%s)</span>', 'globalnews-media'),
        ));
        register_post_status('scheduled_breaking', array(
            'label' => _x('Scheduled Breaking', 'post status', 'globalnews-media'),
            'public' => false,
            'internal' => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list' => true,
            'label_count' => _n_noop('Scheduled Breaking <span class="count">(%s)</span>', 'Scheduled Breaking <span class="count">(%s)</span>', 'globalnews-media'),
        ));
    }

    public function display_post_states($states, $post) {
        if ($post->post_status === 'pending_review') {
            $states[] = __('Pending Review', 'globalnews-media');
        }
        if ($post->post_status === 'draft_review') {
            $states[] = __('Draft Review', 'globalnews-media');
        }
        if ($post->post_status === 'scheduled_breaking') {
            $states[] = __('Scheduled Breaking', 'globalnews-media');
        }
        return $states;
    }

    public function add_status_dropdown() {
        global $post_type;
        if ($post_type !== 'post') {
            return;
        }
        ?>
        <script>
        jQuery(function($) {
            $('select[name="_status"]').append([
                '<option value="pending_review"><?php echo esc_js(__('Pending Review', 'globalnews-media')); ?></option>',
                '<option value="draft_review"><?php echo esc_js(__('Draft Review', 'globalnews-media')); ?></option>',
                '<option value="scheduled_breaking"><?php echo esc_js(__('Scheduled Breaking', 'globalnews-media')); ?></option>',
            ].join(''));
        });
        </script>
        <?php
    }

    public function add_status_js() {
        global $post;
        if (!$post || $post->post_type !== 'post') {
            return;
        }
        ?>
        <script>
        jQuery(function($) {
            $('.edit-post-status .edit-post-status').on('click', function() {
                setTimeout(function() {
                    var select = $('#post_status');
                    if (select.find('option[value="pending_review"]').length === 0) {
                        select.append('<option value="pending_review"><?php echo esc_js(__('Pending Review', 'globalnews-media')); ?></option>');
                    }
                    if (select.find('option[value="draft_review"]').length === 0) {
                        select.append('<option value="draft_review"><?php echo esc_js(__('Draft Review', 'globalnews-media')); ?></option>');
                    }
                    if (select.find('option[value="scheduled_breaking"]').length === 0) {
                        select.append('<option value="scheduled_breaking"><?php echo esc_js(__('Scheduled Breaking', 'globalnews-media')); ?></option>');
                    }
                }, 100);
            });
        });
        </script>
        <?php
    }

    public function add_workflow_metabox() {
        add_meta_box(
            'globalnews_workflow',
            __('Editorial Workflow', 'globalnews-media'),
            array($this, 'render_workflow_metabox'),
            'post',
            'side',
            'high'
        );
    }

    public function render_workflow_metabox($post) {
        wp_nonce_field('globalnews_workflow', 'globalnews_workflow_nonce');
        $editor_notes = get_post_meta($post->ID, 'globalnews_editor_notes', true);
        $assigned_editor = get_post_meta($post->ID, 'globalnews_assigned_editor', true);
        $breaking_scheduled = get_post_meta($post->ID, 'globalnews_breaking_scheduled_time', true);
        $editors = get_users(array('role__in' => array('editor', 'administrator')));
        ?>
        <p>
            <label for="globalnews_assigned_editor"><?php esc_html_e('Assign Editor:', 'globalnews-media'); ?></label>
            <select name="globalnews_assigned_editor" id="globalnews_assigned_editor" style="width:100%">
                <option value=""><?php esc_html_e('— Select —', 'globalnews-media'); ?></option>
                <?php foreach ($editors as $editor) : ?>
                    <option value="<?php echo esc_attr($editor->ID); ?>" <?php selected($assigned_editor, $editor->ID); ?>><?php echo esc_html($editor->display_name); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="globalnews_editor_notes"><?php esc_html_e('Editor Notes:', 'globalnews-media'); ?></label>
            <textarea name="globalnews_editor_notes" id="globalnews_editor_notes" rows="3" style="width:100%"><?php echo esc_textarea($editor_notes); ?></textarea>
        </p>
        <p>
            <label>
                <input type="checkbox" name="globalnews_request_review" value="1">
                <?php esc_html_e('Request Review from Editor', 'globalnews-media'); ?>
            </label>
        </p>
        <?php if (current_user_can('editor') || current_user_can('administrator')) : ?>
            <p>
                <label for="globalnews_breaking_scheduled_time"><?php esc_html_e('Schedule Breaking:', 'globalnews-media'); ?></label>
                <input type="datetime-local" name="globalnews_breaking_scheduled_time" id="globalnews_breaking_scheduled_time" value="<?php echo esc_attr($breaking_scheduled); ?>" style="width:100%">
            </p>
        <?php endif; ?>
        <?php
    }

    public function save_editor_notes($post_id, $post, $update) {
        if (!isset($_POST['globalnews_workflow_nonce']) || !wp_verify_nonce($_POST['globalnews_workflow_nonce'], 'globalnews_workflow')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (isset($_POST['globalnews_editor_notes'])) {
            update_post_meta($post_id, 'globalnews_editor_notes', sanitize_textarea_field($_POST['globalnews_editor_notes']));
        }
        if (isset($_POST['globalnews_assigned_editor'])) {
            update_post_meta($post_id, 'globalnews_assigned_editor', intval($_POST['globalnews_assigned_editor']));
        }
        if (isset($_POST['globalnews_breaking_scheduled_time'])) {
            update_post_meta($post_id, 'globalnews_breaking_scheduled_time', sanitize_text_field($_POST['globalnews_breaking_scheduled_time']));
        }
        if (isset($_POST['globalnews_request_review']) && $_POST['globalnews_request_review']) {
            remove_action('save_post', array($this, 'save_editor_notes'), 10, 3);
            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'pending_review',
            ));
            add_action('save_post', array($this, 'save_editor_notes'), 10, 3);
        }
    }

    public function notify_editor($new_status, $old_status, $post) {
        if ($post->post_type !== 'post') {
            return;
        }
        if ($new_status === 'pending_review') {
            $assigned_editor = get_post_meta($post->ID, 'globalnews_assigned_editor', true);
            if ($assigned_editor) {
                $editor_user = get_userdata($assigned_editor);
                if ($editor_user) {
                    $subject = sprintf(__('[Review Needed] %s', 'globalnews-media'), $post->post_title);
                    $message = sprintf(
                        __('A new article is pending review: %s' . "\n\n" . 'Author: %s' . "\n" . 'URL: %s' . "\n\n" . 'Please review and approve or request changes.', 'globalnews-media'),
                        $post->post_title,
                        get_the_author_meta('display_name', $post->post_author),
                        get_permalink($post->ID)
                    );
                    wp_mail($editor_user->user_email, $subject, $message, array('Content-Type: text/plain; charset=UTF-8'));
                }
            }
        }
        if ($new_status === 'publish' && $old_status !== 'publish') {
            do_action('globalnews_article_published', $post->ID);
        }
    }

    public function check_editor_approval($data, $postarr) {
        if ($data['post_type'] !== 'post') {
            return $data;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $data;
        }
        if (current_user_can('journalist') && isset($data['post_status']) && $data['post_status'] === 'publish') {
            if (!isset($postarr['ID']) || !get_post_meta($postarr['ID'], 'globalnews_editor_approved', true)) {
                $data['post_status'] = 'pending_review';
            }
        }
        return $data;
    }

    public function add_pending_queue_page() {
        add_dashboard_page(
            __('Pending Review Queue', 'globalnews-media'),
            __('Pending Queue', 'globalnews-media'),
            'edit_others_posts',
            'globalnews-pending-queue',
            array($this, 'render_pending_queue')
        );
    }

    public function render_pending_queue() {
        if (!current_user_can('edit_others_posts')) {
            wp_die(__('You do not have permission to access this page.', 'globalnews-media'));
        }
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Pending Review Queue', 'globalnews-media'); ?></h1>
            <?php
            $statuses = array('pending_review', 'draft_review', 'pending');
            $args = array(
                'post_type' => 'post',
                'post_status__in' => $statuses,
                'posts_per_page' => 50,
                'orderby' => 'date',
                'order' => 'DESC',
            );
            $query = new WP_Query($args);
            if ($query->have_posts()) : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Title', 'globalnews-media'); ?></th>
                            <th><?php esc_html_e('Author', 'globalnews-media'); ?></th>
                            <th><?php esc_html_e('Status', 'globalnews-media'); ?></th>
                            <th><?php esc_html_e('Submitted', 'globalnews-media'); ?></th>
                            <th><?php esc_html_e('Actions', 'globalnews-media'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <tr>
                                <td><strong><?php the_title(); ?></strong></td>
                                <td><?php the_author(); ?></td>
                                <td><?php echo esc_html(get_post_status_object(get_post_status())->label); ?></td>
                                <td><?php echo get_the_date(); ?></td>
                                <td>
                                    <a href="<?php echo get_edit_post_link(); ?>" class="button button-small"><?php esc_html_e('Edit', 'globalnews-media'); ?></a>
                                    <a href="<?php the_permalink(); ?>" class="button button-small" target="_blank"><?php esc_html_e('Preview', 'globalnews-media'); ?></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p><?php esc_html_e('No articles pending review.', 'globalnews-media'); ?></p>
            <?php endif;
            wp_reset_postdata(); ?>
        </div>
        <?php
    }

    public function journalist_filter_posts($query) {
        if (is_admin() && $query->is_main_query() && $query->get('post_type') === 'post') {
            if (current_user_can('journalist')) {
                $query->set('author', get_current_user_id());
            }
        }
        return $query;
    }

    public static function add_editor_approved_meta($post_id) {
        if (current_user_can('editor') || current_user_can('administrator')) {
            update_post_meta($post_id, 'globalnews_editor_approved', true);
        }
    }
}

GlobalNews_Workflow::instance();
