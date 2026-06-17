/**
 * GlobalNews Media - Main JavaScript
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {

        /* ============================================
           Mobile Menu
           ============================================ */
        var mobileOverlay = document.getElementById('mobileMenuOverlay');
        var mobileClose = document.getElementById('mobileMenuClose');
        var mobileToggle = document.getElementById('mobileMenuToggle');

        if (mobileOverlay) {
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    mobileOverlay.classList.toggle('active');
                    document.body.style.overflow = mobileOverlay.classList.contains('active') ? 'hidden' : '';
                });
            }

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
           Hero Slider
           ============================================ */
        var heroSlider = document.getElementById('heroSlider');
        if (heroSlider) {
            var track = document.getElementById('heroSliderTrack');
            var dotsContainer = document.getElementById('heroSliderDots');
            var slides = track ? track.querySelectorAll('.hero-slide') : [];
            var currentIndex = 0;
            var interval;

            if (slides.length > 1) {
                slides.forEach(function(_, i) {
                    var dot = document.createElement('button');
                    dot.className = 'dot' + (i === 0 ? ' active' : '');
                    dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                    dot.addEventListener('click', function() {
                        goToSlide(i);
                        resetInterval();
                    });
                    dotsContainer.appendChild(dot);
                });

                function goToSlide(index) {
                    currentIndex = index;
                    track.style.transform = 'translateX(-' + (index * 100) + '%)';
                    dotsContainer.querySelectorAll('.dot').forEach(function(d, i) {
                        d.classList.toggle('active', i === index);
                    });
                }

                function nextSlide() {
                    goToSlide((currentIndex + 1) % slides.length);
                }

                function resetInterval() {
                    clearInterval(interval);
                    interval = setInterval(nextSlide, 5000);
                }

                interval = setInterval(nextSlide, 5000);
            }
        }

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

        /* ============================================
           Article Feedback (Reactions & Helpful)
           ============================================ */
        var feedbackEl = document.querySelector('.article-feedback');
        if (feedbackEl && typeof globalnewsFeedback !== 'undefined') {
            var postId = globalnewsFeedback.postId;

            var reactionBtns = feedbackEl.querySelectorAll('.reaction-btn');
            reactionBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var reaction = this.dataset.reaction;
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', globalnewsFeedback.ajaxUrl, true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                var res = JSON.parse(xhr.responseText);
                                if (res.success && res.data) {
                                    var data = res.data;
                                    reactionBtns.forEach(function(b) {
                                        var r = b.dataset.reaction;
                                        b.classList.toggle('active', data.user.indexOf(r) !== -1);
                                        var countEl = b.querySelector('.reaction-count');
                                        if (countEl && data.reactions[r] !== undefined) {
                                            countEl.textContent = data.reactions[r];
                                        }
                                    });
                                    var totalEl = feedbackEl.querySelector('.reaction-total');
                                    if (totalEl) {
                                        totalEl.textContent = data.total + ' ' + (data.total === 1 ? 'reaction' : 'reactions');
                                    }
                                }
                            } catch(e) {}
                        }
                    };
                    xhr.send('action=globalnews_add_reaction&post_id=' + postId + '&reaction=' + reaction + '&nonce=' + globalnewsFeedback.nonce);
                });
            });

            var helpfulBtns = feedbackEl.querySelectorAll('.helpful-btn');
            helpfulBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var type = this.dataset.type;
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', globalnewsFeedback.ajaxUrl, true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                var res = JSON.parse(xhr.responseText);
                                if (res.success) {
                                    var countEl = btn.querySelector('.helpful-count');
                                    if (countEl) {
                                        countEl.textContent = parseInt(countEl.textContent) + 1;
                                    }
                                    var msg = feedbackEl.querySelector('.feedback-form-msg');
                                    if (msg) {
                                        msg.textContent = res.data.message;
                                        msg.className = 'feedback-form-msg success';
                                        setTimeout(function() { msg.textContent = ''; msg.className = 'feedback-form-msg'; }, 3000);
                                    }
                                }
                            } catch(e) {}
                        }
                    };
                    xhr.send('action=globalnews_submit_feedback&post_id=' + postId + '&feedback_type=' + type + '&feedback_message=&nonce=' + globalnewsFeedback.nonce);
                });
            });

            var formToggle = feedbackEl.querySelector('.feedback-form-btn');
            var formWrapper = feedbackEl.querySelector('.feedback-form-wrapper');
            if (formToggle && formWrapper) {
                formToggle.addEventListener('click', function() {
                    if (formWrapper.style.display === 'none' || formWrapper.style.display === '') {
                        formWrapper.style.display = 'block';
                        formToggle.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Close';
                    } else {
                        formWrapper.style.display = 'none';
                        formToggle.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> Send feedback to editors';
                    }
                });
            }

            var feedbackForm = feedbackEl.querySelector('.feedback-form');
            if (feedbackForm) {
                feedbackForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var type = document.getElementById('feedback-type').value;
                    var message = document.getElementById('feedback-message').value;
                    var submitBtn = this.querySelector('.feedback-submit');
                    var msgEl = this.querySelector('.feedback-form-msg');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Sending...';
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', globalnewsFeedback.ajaxUrl, true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Send feedback';
                        try {
                            var res = JSON.parse(xhr.responseText);
                            if (res.success) {
                                msgEl.textContent = res.data.message;
                                msgEl.className = 'feedback-form-msg success';
                                document.getElementById('feedback-message').value = '';
                                setTimeout(function() { msgEl.textContent = ''; msgEl.className = 'feedback-form-msg'; }, 3000);
                            } else {
                                msgEl.textContent = 'Something went wrong. Please try again.';
                                msgEl.className = 'feedback-form-msg error';
                            }
                        } catch(e) {
                            msgEl.textContent = 'Something went wrong. Please try again.';
                            msgEl.className = 'feedback-form-msg error';
                        }
                    };
                    xhr.send('action=globalnews_submit_feedback&post_id=' + postId + '&feedback_type=' + encodeURIComponent(type) + '&feedback_message=' + encodeURIComponent(message) + '&nonce=' + globalnewsFeedback.nonce);
                });
            }
        }

        /* ============================================
           Hero Ads Slider
           ============================================ */
        var adsSlider = document.getElementById('heroAdsSlider');
        var adsTrack = document.getElementById('heroAdsTrack');
        if (adsSlider && adsTrack) {
            var adsWidgets = adsTrack.querySelectorAll(':scope > .widget, :scope > .custom-html-widget, :scope > .textwidget, :scope > div');
            var adsSlides = [];
            if (adsWidgets.length > 1) {
                adsWidgets.forEach(function(w) {
                    var slide = document.createElement('div');
                    slide.className = 'ads-slide';
                    w.parentNode.insertBefore(slide, w);
                    slide.appendChild(w);
                    adsSlides.push(slide);
                });

                var adsDotsContainer = document.createElement('div');
                adsDotsContainer.className = 'ads-dots';
                adsSlider.appendChild(adsDotsContainer);

                var adsCurrent = 0;

                adsSlides.forEach(function(_, i) {
                    var dot = document.createElement('button');
                    dot.className = 'ads-dot' + (i === 0 ? ' active' : '');
                    dot.setAttribute('aria-label', 'Go to ad ' + (i + 1));
                    dot.addEventListener('click', function() {
                        goToAdsSlide(i);
                        resetAdsInterval();
                    });
                    adsDotsContainer.appendChild(dot);
                });

                function goToAdsSlide(index) {
                    adsCurrent = index;
                    adsTrack.style.transform = 'translateX(-' + (index * 100) + '%)';
                    adsDotsContainer.querySelectorAll('.ads-dot').forEach(function(d, i) {
                        d.classList.toggle('active', i === index);
                    });
                }

                function nextAdsSlide() {
                    goToAdsSlide((adsCurrent + 1) % adsSlides.length);
                }

                function resetAdsInterval() {
                    clearInterval(window._adsInterval);
                    window._adsInterval = setInterval(nextAdsSlide, 4000);
                }

                window._adsInterval = setInterval(nextAdsSlide, 4000);
            }
        }

    });

})();
