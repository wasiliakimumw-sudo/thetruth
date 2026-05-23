/**
 * GlobalNews Media - Main JavaScript
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {

        /* ============================================
           Search Overlay
           ============================================ */
        var searchToggle = document.getElementById('searchToggle');
        var searchOverlay = document.getElementById('searchOverlay');
        var searchClose = document.getElementById('searchClose');

        if (searchToggle && searchOverlay) {
            searchToggle.addEventListener('click', function(e) {
                e.preventDefault();
                searchOverlay.classList.add('active');
                setTimeout(function() {
                    var input = searchOverlay.querySelector('.search-field');
                    if (input) input.focus();
                }, 300);
            });

            if (searchClose) {
                searchClose.addEventListener('click', function() {
                    searchOverlay.classList.remove('active');
                });
            }

            searchOverlay.addEventListener('click', function(e) {
                if (e.target === searchOverlay) {
                    searchOverlay.classList.remove('active');
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                    searchOverlay.classList.remove('active');
                }
            });
        }

        /* ============================================
           Mobile Menu
           ============================================ */
        var mobileToggle = document.getElementById('mobileMenuToggle');
        var mobileOverlay = document.getElementById('mobileMenuOverlay');
        var mobileClose = document.getElementById('mobileMenuClose');

        if (mobileToggle && mobileOverlay) {
            mobileToggle.addEventListener('click', function() {
                mobileOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            if (mobileClose) {
                mobileClose.addEventListener('click', function() {
                    mobileOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }

            mobileOverlay.addEventListener('click', function(e) {
                if (e.target === mobileOverlay) {
                    mobileOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }

        /* ============================================
           Mobile Submenu Toggle
           ============================================ */
        var menuItems = document.querySelectorAll('.mobile-menu-list .menu-item-has-children > a');
        menuItems.forEach(function(item) {
            item.addEventListener('click', function(e) {
                var parent = this.parentElement;
                var submenu = parent.querySelector('.sub-menu');
                if (submenu) {
                    e.preventDefault();
                    submenu.classList.toggle('open');
                    var icon = this.querySelector('.dropdown-icon');
                    if (icon) {
                        icon.style.transform = submenu.classList.contains('open') ? 'rotate(180deg)' : '';
                    }
                }
            });
        });

        /* ============================================
           Breaking News Close
           ============================================ */
        var breakingClose = document.getElementById('breakingClose');
        var breakingBar = document.querySelector('.breaking-news-bar');

        if (breakingClose && breakingBar) {
            breakingClose.addEventListener('click', function() {
                breakingBar.style.display = 'none';
            });
        }

        /* ============================================
           Video Carousel Scroll
           ============================================ */
        var carousel = document.getElementById('videoCarousel');
        if (carousel) {
            var track = carousel.querySelector('.video-track');
            var prevBtn = carousel.querySelector('.carousel-prev');
            var nextBtn = carousel.querySelector('.carousel-next');

            if (track && prevBtn && nextBtn) {
                var scrollAmount = 320;

                prevBtn.addEventListener('click', function() {
                    track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                });

                nextBtn.addEventListener('click', function() {
                    track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                });
            }
        }

        /* ============================================
           Sticky Header
           ============================================ */
        var header = document.querySelector('.site-header');
        var headerHeight = header ? header.offsetHeight : 0;
        var lastScrollTop = 0;

        if (header) {
            window.addEventListener('scroll', function() {
                var scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                if (scrollTop > 100) {
                    header.classList.add('header-sticky');
                } else {
                    header.classList.remove('header-sticky');
                }

                if (scrollTop > headerHeight + 100) {
                    if (scrollTop > lastScrollTop) {
                        header.classList.add('header-hidden');
                    } else {
                        header.classList.remove('header-hidden');
                    }
                } else {
                    header.classList.remove('header-hidden');
                }

                lastScrollTop = scrollTop;
            }, { passive: true });
        }

        /* ============================================
           Newsletter Form Submit (placeholder)
           ============================================ */
        var newsletterForms = document.querySelectorAll('.newsletter-form, .newsletter-mini-form, .subscribe-bar-form, .newsletter-widget-form');
        newsletterForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var emailInput = this.querySelector('input[type="email"]');
                if (emailInput && emailInput.value) {
                    alert('Thank you for subscribing! This feature will be connected to MailPoet.');
                    emailInput.value = '';
                }
            });
        });

        /* ============================================
           Lazy Loading Images (fallback)
           ============================================ */
        if ('loading' in HTMLImageElement.prototype) {
            var lazyImages = document.querySelectorAll('img[loading="lazy"]');
            lazyImages.forEach(function(img) {
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                }
            });
        } else {
            var lazyImages = document.querySelectorAll('img[data-src]');
            if ('IntersectionObserver' in window) {
                var imageObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var img = entry.target;
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                            imageObserver.unobserve(img);
                        }
                    });
                });

                lazyImages.forEach(function(img) {
                    imageObserver.observe(img);
                });
            } else {
                lazyImages.forEach(function(img) {
                    img.src = img.dataset.src;
                });
            }
        }

    });

})();
