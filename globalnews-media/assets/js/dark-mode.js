/**
 * GlobalNews Media - Dark Mode Toggle
 */

(function() {
    'use strict';

    var darkModeToggle = document.getElementById('darkModeToggle');
    if (!darkModeToggle) return;

    var body = document.body;

    function getCookie(name) {
        var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    }

    function setCookie(name, value, days) {
        var expires = '';
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }
        document.cookie = name + '=' + (value || '') + expires + '; path=/';
    }

    function setDarkMode(isDark) {
        if (isDark) {
            body.classList.remove('dark-mode-auto');
            body.classList.add('dark-mode');
            setCookie('globalnews_dark_mode', 'dark', 365);
        } else {
            body.classList.remove('dark-mode', 'dark-mode-auto');
            setCookie('globalnews_dark_mode', 'light', 365);
        }
    }

    function getSystemPreference() {
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    var currentMode = getCookie('globalnews_dark_mode');
    if (!currentMode) {
        if (getSystemPreference()) {
            body.classList.add('dark-mode-auto');
        }
    } else if (currentMode === 'dark') {
        body.classList.add('dark-mode');
    }

    darkModeToggle.addEventListener('click', function() {
        var isDark = body.classList.contains('dark-mode') || body.classList.contains('dark-mode-auto');
        if (isDark) {
            body.classList.remove('dark-mode-auto');
        }
        setDarkMode(!isDark);
        this.classList.toggle('is-dark');
    });

    if (window.matchMedia) {
        var mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        mediaQuery.addEventListener('change', function(e) {
            var cookieMode = getCookie('globalnews_dark_mode');
            if (!cookieMode) {
                if (e.matches) {
                    body.classList.add('dark-mode-auto');
                } else {
                    body.classList.remove('dark-mode-auto');
                }
            }
        });
    }

})();
