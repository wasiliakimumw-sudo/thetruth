<?php
/**
 * Dark Mode Functions
 */

function globalnews_dark_mode_body_class($classes) {
    if (isset($_COOKIE['globalnews_dark_mode']) && 'dark' === $_COOKIE['globalnews_dark_mode']) {
        $classes[] = 'dark-mode';
    } elseif (!isset($_COOKIE['globalnews_dark_mode'])) {
        $classes[] = 'dark-mode-auto';
    }
    return $classes;
}
add_filter('body_class', 'globalnews_dark_mode_body_class');

function globalnews_dark_mode_toggle() {
    ?>
    <button class="dark-mode-toggle" id="darkModeToggle" aria-label="<?php esc_attr_e('Toggle dark mode', 'globalnews-media'); ?>">
        <svg class="sun-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="5"></circle>
            <line x1="12" y1="1" x2="12" y2="3"></line>
            <line x1="12" y1="21" x2="12" y2="23"></line>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
            <line x1="1" y1="12" x2="3" y2="12"></line>
            <line x1="21" y1="12" x2="23" y2="12"></line>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
        </svg>
        <svg class="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
        </svg>
    </button>
    <?php
}
