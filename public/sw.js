// iDara Service Worker v1.0
// CACHING DISABLED - Network-only strategy
const CACHE_NAME = 'idara-v1.0.0';

/**
 * Install Event - Skip caching
 */
self.addEventListener('install', (event) => {
  console.log('[Service Worker] Installing (Caching Disabled)...');
  return self.skipWaiting();
});

/**
 * Activate Event - Clear all caches
 */
self.addEventListener('activate', (event) => {
  console.log('[Service Worker] Activating (Clearing all caches)...');
  
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          console.log('[Service Worker] Deleting cache:', cacheName);
          return caches.delete(cacheName);
        })
      );
    }).then(() => {
      return self.clients.claim();
    })
  );
});

/**
 * Fetch Event - Network-only (no caching)
 */
self.addEventListener('fetch', (event) => {
  const { request } = event;
  
  // All requests go directly to network - no caching
  event.respondWith(
    fetch(request)
      .then((response) => {
        if (!response) {
          return new Response('Offline - No cache available', {
            status: 503,
            statusText: 'Service Unavailable',
            headers: new Headers({
              'Content-Type': 'text/plain; charset=UTF-8'
            })
          });
        }
        return response;
      })
      .catch(() => {
        console.log('[Service Worker] Network failed for:', request.url);
        return new Response('Offline - Network unavailable', {
          status: 503,
          statusText: 'Service Unavailable',
          headers: new Headers({
            'Content-Type': 'text/plain; charset=UTF-8'
          })
        });
      })
  );
});

      // Cache successful HTML responses
      if (request.headers.get('accept')?.includes('text/html')) {
        const responseToCache = response.clone();
        caches.open(RUNTIME_CACHE).then((cache) => {
          cache.put(request, responseToCache);
          console.log('[Service Worker] Cached HTML:', request.url);
        });
      }

      return response;
    })
    .catch(() => {
      console.log('[Service Worker] Network failed, trying cache:', request.url);
      
      // Try to return cached version
      return caches.match(request).then((response) => {
        if (response) {
          return response;
        }

        // Return offline page as last resort
        return caches.match('/offline.html') || 
               new Response('Offline - Please check your connection', {
                 status: 503,
                 statusText: 'Service Unavailable',
                 headers: new Headers({
                   'Content-Type': 'text/plain; charset=UTF-8'
                 })
               });
      });
    });
}

/**
 * Check if request is for a static asset
 */
function isStaticAsset(url) {
  return /\.(css|js|jpg|jpeg|png|gif|svg|woff|woff2|ttf|eot)$/i.test(url);
}

/**
 * Message handling for cache updates
 */
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
  
  if (event.data && event.data.type === 'CLEAR_CACHE') {
    caches.delete(STATIC_CACHE);
    caches.delete(RUNTIME_CACHE);
    console.log('[Service Worker] Caches cleared');
  }
});

console.log('[Service Worker] Service Worker loaded');
