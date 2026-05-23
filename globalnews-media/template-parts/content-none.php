<?php
/**
 * Template part for no content
 */
?>
<section class="no-content-section">
    <div class="no-content-inner">
        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
        <h2><?php esc_html_e('Nothing Found', 'globalnews-media'); ?></h2>
        <?php if (is_home() && current_user_can('publish_posts')) : ?>
            <p><?php printf(wp_kses(__('Ready to publish your first post? <a href="%s">Get started here</a>.', 'globalnews-media'), array('a' => array('href' => array()))), esc_url(admin_url('post-new.php'))); ?></p>
        <?php elseif (is_search()) : ?>
            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'globalnews-media'); ?></p>
            <?php get_search_form(); ?>
        <?php else : ?>
            <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'globalnews-media'); ?></p>
            <?php get_search_form(); ?>
        <?php endif; ?>
    </div>
</section>
