<?php

function globalnews_register_post_types() {
    $labels_video = array(
        'name'                  => __('Videos', 'globalnews-media'),
        'singular_name'         => __('Video', 'globalnews-media'),
        'add_new'               => __('Add New Video', 'globalnews-media'),
        'add_new_item'          => __('Add New Video', 'globalnews-media'),
        'edit_item'             => __('Edit Video', 'globalnews-media'),
        'new_item'              => __('New Video', 'globalnews-media'),
        'view_item'             => __('View Video', 'globalnews-media'),
        'search_items'          => __('Search Videos', 'globalnews-media'),
        'not_found'             => __('No videos found', 'globalnews-media'),
        'not_found_in_trash'    => __('No videos found in trash', 'globalnews-media'),
        'all_items'             => __('All Videos', 'globalnews-media'),
        'menu_name'             => __('Videos', 'globalnews-media'),
    );

    register_post_type('video', array(
        'labels'       => $labels_video,
        'public'       => true,
        'menu_icon'    => 'dashicons-video-alt3',
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments'),
        'show_in_menu' => true,
        'menu_position' => 5,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'videos'),
        'taxonomies'   => array('category', 'post_tag'),
    ));

    $labels_audio = array(
        'name'                  => __('Audios', 'globalnews-media'),
        'singular_name'         => __('Audio', 'globalnews-media'),
        'add_new'               => __('Add New Audio', 'globalnews-media'),
        'add_new_item'          => __('Add New Audio', 'globalnews-media'),
        'edit_item'             => __('Edit Audio', 'globalnews-media'),
        'new_item'              => __('New Audio', 'globalnews-media'),
        'view_item'             => __('View Audio', 'globalnews-media'),
        'search_items'          => __('Audios', 'globalnews-media'),
        'not_found'             => __('No audios found', 'globalnews-media'),
        'not_found_in_trash'    => __('No audios found in trash', 'globalnews-media'),
        'all_items'             => __('All Audios', 'globalnews-media'),
        'menu_name'             => __('Audios', 'globalnews-media'),
    );

    register_post_type('audio', array(
        'labels'       => $labels_audio,
        'public'       => true,
        'menu_icon'    => 'dashicons-format-audio',
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments'),
        'show_in_menu' => true,
        'menu_position' => 6,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'audios'),
        'taxonomies'   => array('category', 'post_tag'),
    ));
}
add_action('init', 'globalnews_register_post_types');

function globalnews_flush_on_activation() {
    globalnews_register_post_types();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'globalnews_flush_on_activation');
