// A very basic service worker to enable the "Install" feature.

const CACHE_NAME = 'modern-pos-cache-v1';
const urlsToCache = [
  '/',
  '/login',
  '/css/style.css',
  '/images/SMS POS.png'
];

// Install event: opens the cache and adds main files to it.
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Opened cache');
        // addAll() is atomic - if one file fails, the whole operation fails.
        return cache.addAll(urlsToCache);
      })
  );
});

// Fetch event: serves assets from cache if they exist, otherwise fetches from network.
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Cache hit - return the response from the cache
        if (response) {
          return response;
        }
        // Not in cache - fetch from the network
        return fetch(event.request);
      }
    )
  );
});
