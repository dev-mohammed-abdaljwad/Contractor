// iDara Service Worker v1.0
// Cache versioning for easy updates
const CACHE_NAME = 'idara-v1.0.0';
const STATIC_CACHE = 'idara-static-v1.0.0';
const RUNTIME_CACHE = 'idara-runtime-v1.0.0';

// Assets to cache on install
const STATIC_ASSETS = [
  '/',
  '/index.php',
  '/offline.html',
  '/css/main.css',
];

// Files to skip from caching (API routes, admin routes)
const SKIP_CACHE = [
  '/api/',
  '/admin/',
  '/sanctum/',
  '/logout'
];

/**
 * Install Event - Cache static assets
 */
self.addEventListener('install', (event) => {
  console.log('[Service Worker] Installing...');
  
  event.waitUntil(
    caches.open(STATIC_CACHE).then((cache) => {
      console.log('[Service Worker] Caching critical assets');
      // Only cache offline.html as it's critical for fallback
      // Other assets will be cached on first access (network-first/cache-first)
      return cache.addAll(['/offline.html']).catch((error) => {
        console.warn('[Service Worker] Failed to cache offline.html:', error);
        // Continue even if offline.html fails
      });
    }).then(() => {
      console.log('[Service Worker] Skipping waiting and activating now');
      return self.skipWaiting();
    })
  );
});


/**
 * Activate Event - Clean up old caches
 */
self.addEventListener('activate', (event) => {
  console.log('[Service Worker] Activating...');
  
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          // Delete old caches but keep current ones
          if (cacheName !== STATIC_CACHE && 
              cacheName !== RUNTIME_CACHE && 
              cacheName !== CACHE_NAME) {
            console.log('[Service Worker] Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => {
      return self.clients.claim();
    })
  );
});

/**
 * Fetch Event - Implement caching strategies
 */
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip caching for API routes, admin, and logout
  if (SKIP_CACHE.some(skip => url.pathname.includes(skip))) {
    console.log('[Service Worker] Skipping cache for:', url.pathname);
    return;
  }

  // Strategy: Network-first for HTML pages
  if (request.headers.get('accept')?.includes('text/html')) {
    event.respondWith(networkFirstStrategy(request));
  }
  // Strategy: Cache-first for static assets
  else if (isStaticAsset(request.url)) {
    event.respondWith(cacheFirstStrategy(request));
  }
  // Default: Network-first with offline fallback
  else {
    event.respondWith(networkFirstStrategy(request));
  }
});

/**
 * Cache-First Strategy
 * Try cache first, fall back to network if not found
 */
function cacheFirstStrategy(request) {
  return caches.match(request).then((response) => {
    if (response) {
      console.log('[Service Worker] Cache hit:', request.url);
      return response;
    }

    return fetch(request)
      .then((response) => {
        // Don't cache failed requests
        if (!response || response.status !== 200 || response.type === 'error') {
          return response;
        }

        // Cache successful responses
        const responseToCache = response.clone();
        caches.open(STATIC_CACHE).then((cache) => {
          cache.put(request, responseToCache);
          console.log('[Service Worker] Cached:', request.url);
        });

        return response;
      })
      .catch(() => {
        console.log('[Service Worker] Network failed for:', request.url);
        // Return offline page for failed requests
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
 * Network-First Strategy
 * Try network first, fall back to cache if offline
 */
function networkFirstStrategy(request) {
  return fetch(request)
    .then((response) => {
      // Don't cache failed requests
      if (!response || response.status !== 200) {
        return response;
      }

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
