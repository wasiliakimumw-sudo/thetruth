<?php
/**
 * 404 Template
 */

get_header();
?>

<main id="primary" class="site-main">
    <section class="error-404-section">
        <div class="container">
            <div class="error-404-content">
                <span class="error-404-code">404</span>
                <h1 class="error-404-title"><?php esc_html_e('Page Not Found', 'globalnews-media'); ?></h1>
                <p class="error-404-desc"><?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'globalnews-media'); ?></p>
                <div class="error-404-actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <?php esc_html_e('Go Home', 'globalnews-media'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-outline"><?php esc_html_e('Contact Us', 'globalnews-media'); ?></a>
                </div>
                <div class="error-404-search">
                    <p><?php esc_html_e('Or try searching:', 'globalnews-media'); ?></p>
                    <form role="search" method="get" class="search-form-404" action="<?php echo esc_url(home_url('/')); ?>">
                        <input type="search" placeholder="<?php esc_attr_e('Search...', 'globalnews-media'); ?>" value="<?php echo get_search_query(); ?>" name="s">
                        <button type="submit"><?php esc_html_e('Search', 'globalnews-media'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
