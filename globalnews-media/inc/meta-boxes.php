<?php
/**
 * Custom Meta Boxes for Posts
 */

function globalnews_add_meta_boxes() {
    add_meta_box(
        'globalnews_post_options',
        esc_html__('GlobalNews Post Options', 'globalnews-media'),
        'globalnews_post_options_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'globalnews_add_meta_boxes');

function globalnews_post_options_callback($post) {
    wp_nonce_field('globalnews_post_options', 'globalnews_post_options_nonce');

    $featured = get_post_meta($post->ID, 'globalnews_featured_post', true);
    $breaking = get_post_meta($post->ID, 'globalnews_breaking_news', true);
    ?>
    <div class="globalnews-meta-box">
        <p>
            <label>
                <input type="checkbox" name="globalnews_featured_post" value="1" <?php checked($featured, '1'); ?>>
                <?php esc_html_e('Mark as Featured Article', 'globalnews-media'); ?>
            </label>
        </p>
        <p>
            <label>
                <input type="checkbox" name="globalnews_breaking_news" value="1" <?php checked($breaking, '1'); ?>>
                <?php esc_html_e('Mark as Breaking News', 'globalnews-media'); ?>
            </label>
        </p>
        <p class="description"><?php esc_html_e('Featured articles appear in the hero section. Breaking news appears in the ticker.', 'globalnews-media'); ?></p>
    </div>
    <?php
}

function globalnews_save_meta_boxes($post_id) {
    if (!isset($_POST['globalnews_post_options_nonce']) ||
        !wp_verify_nonce($_POST['globalnews_post_options_nonce'], 'globalnews_post_options')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $featured = isset($_POST['globalnews_featured_post']) ? '1' : '0';
    $breaking = isset($_POST['globalnews_breaking_news']) ? '1' : '0';

    update_post_meta($post_id, 'globalnews_featured_post', $featured);
    update_post_meta($post_id, 'globalnews_breaking_news', $breaking);
}
add_action('save_post', 'globalnews_save_meta_boxes');

function globalnews_add_category_color_field() {
    ?>
    <div class="form-field">
        <label for="globalnews_category_color"><?php esc_html_e('Category Color', 'globalnews-media'); ?></label>
        <input type="color" name="globalnews_category_color" id="globalnews_category_color" value="#e50914">
        <p class="description"><?php esc_html_e('Choose a color for the category badge.', 'globalnews-media'); ?></p>
    </div>
    <?php
}
add_action('category_add_form_fields', 'globalnews_add_category_color_field', 10, 2);

function globalnews_edit_category_color_field($term) {
    $color = get_term_meta($term->term_id, 'globalnews_category_color', true);
    if (!$color) $color = '#e50914';
    ?>
    <tr class="form-field">
        <th scope="row"><label for="globalnews_category_color"><?php esc_html_e('Category Color', 'globalnews-media'); ?></label></th>
        <td>
            <input type="color" name="globalnews_category_color" id="globalnews_category_color" value="<?php echo esc_attr($color); ?>">
            <p class="description"><?php esc_html_e('Choose a color for the category badge.', 'globalnews-media'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('category_edit_form_fields', 'globalnews_edit_category_color_field', 10, 2);

function globalnews_save_category_color($term_id) {
    if (isset($_POST['globalnews_category_color'])) {
        update_term_meta($term_id, 'globalnews_category_color', sanitize_hex_color($_POST['globalnews_category_color']));
    }
}
add_action('created_category', 'globalnews_save_category_color', 10, 2);
add_action('edited_category', 'globalnews_save_category_color', 10, 2);
