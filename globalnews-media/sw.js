/* GlobalNews Media - Service Worker */
const CACHE_NAME = 'globalnews-v2';
const OFFLINE_URL = '/offline/';

const PRECACHE_URLS = [
    '/',
    '/wp-content/themes/globalnews-media/assets/css/main.css',
    '/wp-content/themes/globalnews-media/assets/css/responsive.css',
    '/wp-content/themes/globalnews-media/assets/css/dark-mode.css',
];

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function(cache) {
            return cache.addAll(PRECACHE_URLS);
        }).then(function() {
            return self.skipWaiting();
        })
    );
});

self.addEventListener('activate', function(event) {
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.filter(function(name) {
                    return name !== CACHE_NAME;
                }).map(function(name) {
                    return caches.delete(name);
                })
            );
        }).then(function() {
            return self.clients.claim();
        })
    );
});

self.addEventListener('fetch', function(event) {
    if (event.request.method !== 'GET') return;

    var url = new URL(event.request.url);

    if (url.pathname.includes('/wp-admin/') ||
        url.pathname.includes('/wp-login.php') ||
        url.pathname.includes('preview=true') ||
        url.pathname.includes('sitemap')) {
        return;
    }

    if (url.pathname.match(/\.(php|json)$/)) {
        event.respondWith(
            fetch(event.request).catch(function() {
                return caches.match(OFFLINE_URL);
            })
        );
        return;
    }

    event.respondWith(
        caches.match(event.request).then(function(cached) {
            var fetchPromise = fetch(event.request).then(function(response) {
                if (response && response.status === 200 && response.type === 'basic') {
                    var clone = response.clone();
                    caches.open(CACHE_NAME).then(function(cache) {
                        cache.put(event.request, clone);
                    });
                }
                return response;
            }).catch(function() {
                return caches.match(OFFLINE_URL).then(function(offline) {
                    return offline || caches.match('/');
                });
            });
            return cached || fetchPromise;
        })
    );
});

self.addEventListener('push', function(event) {
    if (!event.data) return;

    var data = event.data.json();
    var title = data.title || 'GlobalNews Media';
    var options = {
        body: data.body || '',
        icon: data.icon || '/wp-content/themes/globalnews-media/assets/images/app-icon-192.png',
        badge: '/wp-content/themes/globalnews-media/assets/images/badge.png',
        vibrate: [200, 100, 200],
        data: {
            url: data.url || '/',
            date: Date.now()
        },
        actions: [
            { action: 'read', title: 'Read More' },
            { action: 'close', title: 'Dismiss' }
        ],
        tag: 'globalnews-' + Date.now(),
        renotify: true,
        requireInteraction: true
    };
    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    if (event.action === 'close') return;

    var url = event.notification.data.url || '/';
    event.waitUntil(
        clients.matchAll({ type: 'window' }).then(function(clientList) {
            for (var i = 0; i < clientList.length; i++) {
                if ('focus' in clientList[i]) {
                    return clientList[i].focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});

self.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

self.addEventListener('sync', function(event) {
    if (event.tag === 'sync-pending-posts') {
        event.waitUntil(syncPendingPosts());
    }
});

function syncPendingPosts() {
    return self.registration.showNotification('Sync Complete', {
        body: 'Pending updates have been synchronized.',
        icon: '/wp-content/themes/globalnews-media/assets/images/app-icon-192.png'
    });
}
