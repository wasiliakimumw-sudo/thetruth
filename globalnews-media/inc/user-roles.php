<?php
/**
 * Custom User Roles for Journalist Workflow
 */

function globalnews_add_custom_roles() {
    if (!get_role('journalist')) {
        add_role('journalist', esc_html__('Journalist', 'globalnews-media'), array(
            'read'                      => true,
            'edit_posts'                => true,
            'edit_private_posts'        => false,
            'edit_published_posts'      => false,
            'edit_others_posts'         => false,
            'publish_posts'             => false,
            'delete_posts'              => true,
            'delete_private_posts'      => false,
            'delete_published_posts'    => false,
            'delete_others_posts'       => false,
            'upload_files'              => true,
            'unfiltered_html'           => false,
        ));
    }

    $editor_role = get_role('editor');
    if ($editor_role) {
        $editor_role->add_cap('edit_theme_options', false);
        $editor_role->add_cap('manage_options', false);
        $editor_role->add_cap('moderate_comments', true);
        $editor_role->add_cap('manage_categories', true);
    }

    $admin_role = get_role('administrator');
    if ($admin_role) {
        $admin_role->add_cap('manage_seo', true);
        $admin_role->add_cap('manage_ads', true);
        $admin_role->add_cap('manage_journalists', true);
    }
}
add_action('init', 'globalnews_add_custom_roles');

function globalnews_add_journalist_caps() {
    $admin = get_role('administrator');
    if ($admin) {
        $admin->add_cap('manage_journalists', true);
    }
}
add_action('admin_init', 'globalnews_add_journalist_caps');

function globalnews_restrict_journalist_admin_bar($wp_admin_bar) {
    if (current_user_can('journalist')) {
        $nodes_to_remove = array(
            'new-content',
            'comments',
            'appearance',
            'plugins',
            'users',
            'tools',
            'settings',
        );
        foreach ($nodes_to_remove as $node) {
            $wp_admin_bar->remove_node($node);
        }
    }
}
add_action('admin_bar_menu', 'globalnews_restrict_journalist_admin_bar', 999);

function globalnews_journalist_post_status($post_id, $post) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ($post->post_type !== 'post') return;

    if (current_user_can('journalist') && $post->post_status === 'publish') {
        remove_action('save_post', 'globalnews_journalist_post_status');
        wp_update_post(array(
            'ID'          => $post_id,
            'post_status' => 'pending',
        ));
        add_action('save_post', 'globalnews_journalist_post_status', 10, 2);
    }
}
add_action('save_post', 'globalnews_journalist_post_status', 10, 2);
