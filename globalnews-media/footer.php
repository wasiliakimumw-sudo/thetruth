<?php
/**
 * Site Footer
 */
?>
    <footer id="colophon" class="site-footer">
        <div class="footer-subscribe-bar">
            <div class="container">
                <div class="subscribe-bar-inner">
                    <div class="subscribe-text">
                        <h4><?php echo esc_html(globalnews_get_landing_setting('globalnews_footer_subscribe_title')); ?></h4>
                        <p><?php echo esc_html(globalnews_get_landing_setting('globalnews_footer_subscribe_desc')); ?></p>
                    </div>
                    <form class="subscribe-bar-form" action="#" method="post">
                        <input type="email" name="sub_email" placeholder="<?php echo esc_attr(globalnews_get_landing_setting('globalnews_footer_subscribe_placeholder')); ?>" required>
                        <button type="submit"><?php echo esc_html(globalnews_get_landing_setting('globalnews_footer_subscribe_button')); ?></button>
                    </form>
                </div>
            </div>
        </div>

        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-col footer-col-brand">
                        <div class="footer-logo">
                            <?php
                            $footer_logo_id = get_theme_mod('globalnews_footer_logo');
                            if ($footer_logo_id) :
                                echo wp_get_attachment_image($footer_logo_id, 'medium', false, array('loading' => 'lazy'));
                            elseif (has_custom_logo()) :
                                the_custom_logo();
                            else :
                                ?>
                                <span class="footer-site-name"><?php bloginfo('name'); ?></span>
                                <span class="footer-site-tagline"><?php bloginfo('description'); ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="footer-about"><?php echo make_clickable(sprintf(globalnews_get_landing_setting('globalnews_about_text'), '<strong>' . esc_html(get_bloginfo('name')) . '</strong>')); ?></p>
                        <div class="footer-social">
                            <a href="<?php echo esc_url(get_theme_mod('globalnews_social_facebook', '#')); ?>" class="footer-social-icon" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="<?php echo esc_url(get_theme_mod('globalnews_social_twitter', '#')); ?>" class="footer-social-icon" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            <a href="<?php echo esc_url(get_theme_mod('globalnews_social_instagram', '#')); ?>" class="footer-social-icon" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069z"/></svg>
                            </a>
                            <a href="<?php echo esc_url(get_theme_mod('globalnews_social_youtube', '#')); ?>" class="footer-social-icon" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814z"/></svg>
                            </a>
                            <a href="<?php echo esc_url(get_theme_mod('globalnews_social_linkedin', '#')); ?>" class="footer-social-icon" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                        </div>
                    </div>
                    <div class="footer-col">
                        <h4 class="footer-col-title"><?php esc_html_e('Quick Links', 'globalnews-media'); ?></h4>
                        <nav class="footer-nav" aria-label="<?php esc_attr_e('Footer Menu', 'globalnews-media'); ?>">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'footer',
                                'menu_class'     => 'footer-menu',
                                'container'      => false,
                                'fallback_cb'    => false,
                                'depth'          => 1,
                            ));
                            ?>
                        </nav>
                        <?php if (!has_nav_menu('footer')) : ?>
                            <ul class="footer-menu fallback-menu">
                                <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'globalnews-media'); ?></a></li>
                                <li><a href="<?php echo esc_url(home_url('/about')); ?>"><?php esc_html_e('About', 'globalnews-media'); ?></a></li>
                                <li><a href="<?php echo esc_url(home_url('/contact')); ?>"><?php esc_html_e('Contact', 'globalnews-media'); ?></a></li>
                                <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>"><?php esc_html_e('Privacy Policy', 'globalnews-media'); ?></a></li>
                                <li><a href="<?php echo esc_url(home_url('/terms-conditions')); ?>"><?php esc_html_e('Terms & Conditions', 'globalnews-media'); ?></a></li>
                                <li><a href="<?php echo esc_url(home_url('/advertise')); ?>"><?php esc_html_e('Advertise', 'globalnews-media'); ?></a></li>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <div class="footer-col">
                        <h4 class="footer-col-title"><?php esc_html_e('Categories', 'globalnews-media'); ?></h4>
                        <ul class="footer-categories">
                            <?php
                            $footer_cats = get_categories(array('hide_empty' => false, 'orderby' => 'name', 'number' => 8));
                            foreach ($footer_cats as $cat) : ?>
                                <li><a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"><?php echo esc_html($cat->name); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4 class="footer-col-title"><?php esc_html_e('Contact Info', 'globalnews-media'); ?></h4>
                        <ul class="footer-contact">
                            <li>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>Lilongwe City, Area 13, NOMN House</span>
                            </li>
                            <li>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                <a href="mailto:thetruthmw20@gmail.com">thetruthmw20@gmail.com</a>
                            </li>
                            <li>
                                <a href="#" class="gn-feedback-trigger" onclick="document.getElementById('gn-feedback-modal').style.display='flex';return false;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                                    <?php esc_html_e('Give Us Feedback', 'globalnews-media'); ?>
                                </a>
                            </li>
                        </ul>
                        <?php if (is_active_sidebar('footer-4')) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar('footer-4'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="gn-feedback-modal" class="gn-feedback-modal" style="display:none;">
            <div class="gn-feedback-modal-overlay" onclick="document.getElementById('gn-feedback-modal').style.display='none'"></div>
            <div class="gn-feedback-modal-content">
                <button class="gn-feedback-modal-close" onclick="document.getElementById('gn-feedback-modal').style.display='none'">&times;</button>
                <h3><?php esc_html_e('Give Us Feedback', 'globalnews-media'); ?></h3>
                <p><?php esc_html_e('Tell us how you feel about our news.', 'globalnews-media'); ?></p>
                <form id="gn-site-feedback-form" class="gn-site-feedback-form">
                    <textarea name="message" rows="4" placeholder="<?php esc_attr_e('Write your feedback here...', 'globalnews-media'); ?>" required></textarea>
                    <button type="submit"><?php esc_html_e('Send Feedback', 'globalnews-media'); ?></button>
                    <span class="gn-feedback-success" style="display:none;color:#46b450;font-weight:500;"><?php esc_html_e('Thank you for your feedback!', 'globalnews-media'); ?></span>
                </form>
            </div>
        </div>
        <style>
        .gn-feedback-modal { position:fixed;z-index:999999;top:0;left:0;right:0;bottom:0;display:flex;align-items:center;justify-content:center; }
        .gn-feedback-modal-overlay { position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.7); }
        .gn-feedback-modal-content { position:relative;background:#1d2327;border:1px solid #2c3338;border-radius:8px;padding:30px;max-width:440px;width:90%;box-shadow:0 10px 40px rgba(0,0,0,.5); }
        .gn-feedback-modal-close { position:absolute;top:10px;right:14px;background:none;border:none;color:#a0a5aa;font-size:24px;cursor:pointer;padding:0;line-height:1; }
        .gn-feedback-modal-close:hover { color:#fff; }
        .gn-feedback-modal-content h3 { color:#fff;margin:0 0 5px;font-size:17px; }
        .gn-feedback-modal-content p { color:#a0a5aa;margin:0 0 15px;font-size:13px; }
        .gn-site-feedback-form textarea { width:100%;padding:10px 12px;border:1px solid #2c3338;border-radius:4px;background:#0c0e10;color:#f0f0f1;font-size:13px;resize:vertical;box-sizing:border-box;font-family:inherit; }
        .gn-site-feedback-form textarea:focus { border-color:#2271b1;outline:none; }
        .gn-site-feedback-form button[type="submit"] { margin-top:10px;padding:8px 24px;background:#2271b1;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:13px;font-weight:500;display:inline-block; }
        .gn-site-feedback-form button[type="submit"]:hover { background:#135e96; }
        .gn-site-feedback-form button[type="submit"]:disabled { opacity:.6;cursor:default; }
        .gn-feedback-success { display:none;margin-left:10px; }
        .gn-feedback-trigger { display:inline-flex;align-items:center;gap:6px;color:#9ec2e6;text-decoration:none;font-size:13px;margin-top:4px; }
        .gn-feedback-trigger:hover { color:#72aee6;text-decoration:underline; }
        .gn-feedback-trigger svg { flex-shrink:0; }
        </style>
        <?php add_action('wp_footer', 'globalnews_site_feedback_js'); ?>

        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <div class="footer-copyright">
                        <?php echo wp_kses_post(get_theme_mod('globalnews_footer_text', sprintf(esc_html__('© %d Thetruth. All rights reserved.', 'globalnews-media'), date('Y')))); ?>
                    </div>
                    <div class="footer-legal">
                        <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>"><?php esc_html_e('Privacy Policy', 'globalnews-media'); ?></a>
                        <a href="<?php echo esc_url(home_url('/terms-conditions')); ?>"><?php esc_html_e('Terms of Service', 'globalnews-media'); ?></a>
                        <a href="<?php echo esc_url(home_url('/sitemap')); ?>"><?php esc_html_e('Sitemap', 'globalnews-media'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<?php do_action('globalnews_before_footer'); ?>
<?php wp_footer(); ?>
</body>
</html>
