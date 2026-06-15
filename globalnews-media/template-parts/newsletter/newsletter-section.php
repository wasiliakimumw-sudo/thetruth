<?php
/**
 * Newsletter Subscription Section
 */
?>
<section class="newsletter-section section">
    <div class="container">
        <div class="newsletter-wrapper">
            <div class="newsletter-content">
                <span class="newsletter-badge"><?php echo esc_html(globalnews_get_landing_setting('globalnews_newsletter_badge')); ?></span>
                <h2 class="newsletter-title"><?php echo esc_html(globalnews_get_landing_setting('globalnews_newsletter_title')); ?></h2>
                <p class="newsletter-desc"><?php echo esc_html(globalnews_get_landing_setting('globalnews_newsletter_desc')); ?></p>
                <form class="newsletter-form" action="#" method="post">
                    <div class="newsletter-input-row">
                        <input type="text" name="newsletter_name" class="newsletter-input" placeholder="<?php echo esc_attr(globalnews_get_landing_setting('globalnews_newsletter_name_placeholder')); ?>">
                        <input type="email" name="newsletter_email" class="newsletter-input" placeholder="<?php echo esc_attr(globalnews_get_landing_setting('globalnews_newsletter_email_placeholder')); ?>" required>
                    </div>
                    <button type="submit" class="newsletter-submit">
                        <?php echo esc_html(globalnews_get_landing_setting('globalnews_newsletter_button')); ?>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                    <p class="newsletter-disclaimer"><?php echo esc_html(globalnews_get_landing_setting('globalnews_newsletter_disclaimer')); ?></p>
                </form>
            </div>
        </div>
    </div>
</section>
