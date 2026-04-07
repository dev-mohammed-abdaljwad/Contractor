/**
 * iDara Service Worker v2.0.1
 * 
 * Strategy:
 * ✅ HTML Pages → Network-first (Laravel middleware must always run for redirects)
 * ✅ Static Assets → Cache-first (CSS, JS, fonts, images, SVG)
 * ✅ Offline Fallback → Simple text message (no cached HTML pages)
 * ✅ Excluded URLs → Never cached: /login, /logout, /dashboard, /contractor/*, POST
 */

const CACHE_VERSION = 'idara-v2.0.1';
const STATIC_CACHE = `${CACHE_VERSION}-static`;

// URLs that must NEVER be cached (auth-related pages)
const NEVER_CACHE_URLS = [
  '/login',
  '/logout',
  '/dashboard',
  '/contractor/',
  '/admin/',
  '/api/',
];

/**
 * INSTALL EVENT
 * Skip waiting — take over immediately without waiting for old tabs to close
 */
self.addEventListener('install', (event) => {
  console.log('[SW] Installing...', CACHE_VERSION);
  self.skipWaiting();
});

/**
 * ACTIVATE EVENT
 * Clean up old caches and claim all clients
 */
self.addEventListener('activate', (event) => {
  console.log('[SW] Activating...');
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((name) => {
          // Delete any cache that doesn't match current version
          if (!name.includes(CACHE_VERSION)) {
            console.log('[SW] Deleting old cache:', name);
            return caches.delete(name);
          }
        })
      );
    }).then(() => {
      console.log('[SW] Claiming all clients...');
      return self.clients.claim();
    })
  );
});

/**
 * FETCH EVENT
 * Implement Network-first for HTML, Cache-first for static assets
 */
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip non-GET requests (POST, PUT, DELETE, etc.) and non-HTTP
  if (request.method !== 'GET' || !url.protocol.startsWith('http')) {
    return;
  }

  // Check if URL should never be cached
  const shouldNotCache = NEVER_CACHE_URLS.some(
    (path) => url.pathname.includes(path)
  );

  if (shouldNotCache) {
    // Network-only for auth-protected pages
    event.respondWith(
      fetch(request).catch(() =>
        new Response('لا يوجد اتصال بالإنترنت\n(No internet connection)', {
          status: 503,
          headers: { 'Content-Type': 'text/plain; charset=UTF-8' },
        })
      )
    );
    return;
  }

  // HTML pages → Network-first (so Laravel redirects work)
  if (request.headers.get('accept')?.includes('text/html')) {
    event.respondWith(networkFirstStrategy(request));
    return;
  }

  // Static assets → Cache-first
  if (isStaticAsset(url.pathname)) {
    event.respondWith(cacheFirstStrategy(request));
    return;
  }

  // Everything else → Network-first
  event.respondWith(networkFirstStrategy(request));
});

/**
 * NETWORK-FIRST STRATEGY
 * Try network first, fall back to cache only if offline
 */
function networkFirstStrategy(request) {
  return fetch(request)
    .then((response) => {
      // Don't cache error responses
      if (!response || response.status >= 400) {
        return response;
      }
      return response;
    })
    .catch(() => {
      // Network failed — show offline message (no cached page)
      return new Response('لا يوجد اتصال بالإنترنت\n(No internet connection)', {
        status: 503,
        headers: { 'Content-Type': 'text/plain; charset=UTF-8' },
      });
    });
}

/**
 * CACHE-FIRST STRATEGY
 * Check cache first, fall back to network if not found
 */
function cacheFirstStrategy(request) {
  return caches.match(request).then((cachedResponse) => {
    if (cachedResponse) {
      console.log('[SW] Cache hit:', request.url);
      return cachedResponse;
    }

    return fetch(request)
      .then((response) => {
        // Don't cache failed requests
        if (!response || response.status >= 400) {
          return response;
        }

        // Cache successful static assets
        const responseToCache = response.clone();
        caches.open(STATIC_CACHE).then((cache) => {
          cache.put(request, responseToCache);
          console.log('[SW] Cached static asset:', request.url);
        });

        return response;
      })
      .catch(() => {
        // Network failed and no cache available
        return new Response('لا يوجد اتصال بالإنترنت\n(No internet connection)', {
          status: 503,
          headers: { 'Content-Type': 'text/plain; charset=UTF-8' },
        });
      });
  });
}

/**
 * Check if URL is a static asset
 */
function isStaticAsset(pathname) {
  const staticExtensions = /\.(css|js|jpg|jpeg|png|gif|svg|woff|woff2|ttf|eot|webp)$/i;
  return staticExtensions.test(pathname);
}

/**
 * MESSAGE HANDLER
 * Allow clients to send messages to Service Worker
 */
self.addEventListener('message', (event) => {
  if (event.data?.type === 'SKIP_WAITING') {
    console.log('[SW] Received SKIP_WAITING message');
    self.skipWaiting();
  }

  if (event.data?.type === 'CLEAR_CACHE') {
    console.log('[SW] Clearing all caches on logout...');
    caches.keys().then((keys) => {
      return Promise.all(
        keys.map((key) => {
          console.log('[SW] Deleting cache:', key);
          return caches.delete(key);
        })
      );
    }).then(() => {
      console.log('[SW] All caches cleared successfully');
    });
  }
});

console.log('[Service Worker] Loaded - v2.0.0 (Network-first HTML, Cache-first Assets)');
