const CACHE_NAME = 'paperboy-v2';
const OFFLINE_URL = 'offline.html';

const FILES_TO_CACHE = [
    OFFLINE_URL,
    'icon-192x192.png',
    'icon-512x512.png',
    'manifest.json'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('[ServiceWorker] Pre-caching assets');
            return Promise.all(
                FILES_TO_CACHE.map(url => {
                    return cache.add(url).catch(reason => console.log('[ServiceWorker] Cache add failed for', url, reason));
                })
            );
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keyList) => {
            return Promise.all(keyList.map((key) => {
                if (key !== CACHE_NAME) {
                    console.log('[ServiceWorker] Removing old cache', key);
                    return caches.delete(key);
                }
            }));
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') {
        return;
    }

    // Navigation requests (HTML pages)
    if (event.request.mode === 'navigate' || (event.request.headers.get('accept') && event.request.headers.get('accept').includes('text/html'))) {
        event.respondWith(
            fetch(event.request)
                .catch(() => {
                    return caches.match(OFFLINE_URL) || caches.match('./offline.html');
                })
        );
        return;
    }

    // Static assets
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
});
