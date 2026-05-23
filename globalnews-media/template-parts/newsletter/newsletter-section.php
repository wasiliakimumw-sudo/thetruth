<?php
/**
 * Newsletter Subscription Section
 */
?>
<section class="newsletter-section section">
    <div class="container">
        <div class="newsletter-wrapper">
            <div class="newsletter-content">
                <span class="newsletter-badge"><?php esc_html_e('Stay Informed', 'globalnews-media'); ?></span>
                <h2 class="newsletter-title"><?php esc_html_e('Get the Latest News Delivered to Your Inbox', 'globalnews-media'); ?></h2>
                <p class="newsletter-desc"><?php esc_html_e('Join thousands of subscribers. Get breaking news, exclusive stories, and expert analysis straight to your inbox every morning.', 'globalnews-media'); ?></p>
                <form class="newsletter-form" action="#" method="post">
                    <div class="newsletter-input-row">
                        <input type="text" name="newsletter_name" class="newsletter-input" placeholder="<?php esc_attr_e('Your name', 'globalnews-media'); ?>">
                        <input type="email" name="newsletter_email" class="newsletter-input" placeholder="<?php esc_attr_e('Your email address', 'globalnews-media'); ?>" required>
                    </div>
                    <button type="submit" class="newsletter-submit">
                        <?php esc_html_e('Subscribe Now', 'globalnews-media'); ?>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                    <p class="newsletter-disclaimer"><?php esc_html_e('No spam. Unsubscribe anytime.', 'globalnews-media'); ?></p>
                </form>
            </div>
        </div>
    </div>
</section>
