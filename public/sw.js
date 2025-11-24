// ==== BASIS CONFIG ====
const CACHE_NAME = 'taskcheck-v5.0.0';
const STATIC_ASSETS = [
  '/manifest.json',
  '/icons/icon-192x192.png',
  '/icons/icon-512x512.png',
  '/offline.html',
];

// Nooit cachen (altijd vers ophalen)
const NEVER_CACHE = [
  '/login',
  '/logout',
  '/register',
  '/password',
  '/refresh-csrf',
  '/sanctum/csrf-cookie',
  '/employee/submissions', // altijd vers
  '/admin',                // admin schermen liever niet cachen
  '/api',                  // api calls niet via cache
];

function shouldNeverCache(url) {
  const path = url.pathname;
  return NEVER_CACHE.some((blocked) => path === blocked || path.startsWith(blocked));
}

// ==== INSTALL ====
self.addEventListener('install', (event) => {
  console.log('[SW] Installing…');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => cache.addAll(STATIC_ASSETS))
      .then(() => {
        console.log('[SW] Static assets cached');
        return self.skipWaiting();
      })
      .catch((err) => console.error('[SW] Install error', err))
  );
});

// ==== ACTIVATE ====
self.addEventListener('activate', (event) => {
  console.log('[SW] Activating…');
  event.waitUntil(
    caches.keys().then((names) =>
      Promise.all(
        names.map((name) => {
          if (name !== CACHE_NAME) {
            console.log('[SW] Deleting old cache', name);
            return caches.delete(name);
          }
        })
      )
    ).then(() => self.clients.claim())
  );
});

// ==== FETCH HANDLER ====
self.addEventListener('fetch', (event) => {
  const req = event.request;

  // Alleen GET en zelfde origin
  if (req.method !== 'GET') return;
  if (!req.url.startsWith(self.location.origin)) return;

  const url = new URL(req.url);

  // Routes die nooit gecached mogen worden (login/logout, api, csrf, etc.)
  if (shouldNeverCache(url)) {
    event.respondWith(
      fetch(req).catch(() => {
        // Als navigatie faalt → offline pagina
        if (req.mode === 'navigate' || req.destination === 'document') {
          return caches.match('/offline.html');
        }
      })
    );
    return;
  }

  // HTML / navigatie: ALTIJD network-first, NIET cachen
  // → zorgt dat CSRF tokens en sessies altijd vers zijn
  if (req.mode === 'navigate' || req.destination === 'document') {
    event.respondWith(
      fetch(req)
        .catch(() => caches.match('/offline.html'))
    );
    return;
  }

  // Static assets (css, js, images, fonts): cache-first
  if (['style', 'script', 'image', 'font'].includes(req.destination)) {
    event.respondWith(
      caches.match(req).then((cached) => {
        if (cached) return cached;

        return fetch(req)
          .then((res) => {
            // Alleen succesvolle responses cachen
            if (!res || res.status !== 200 || res.type !== 'basic') {
              return res;
            }

            const resClone = res.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(req, resClone);
            });

            return res;
          })
          .catch(() => {
            // eventueel fallback voor images/fonts hier
            return undefined;
          });
      })
    );
    return;
  }

  // Overige GET requests: gewoon netwerk (geen cache)
  event.respondWith(fetch(req).catch(() => undefined));
});

// ==== SKIP_WAITING MESSAGE ====
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

// ==== BACKGROUND SYNC ====
self.addEventListener('sync', (event) => {
  if (event.tag === 'background-sync') {
    console.log('[SW] Background sync triggered');
    event.waitUntil(handleOfflineSubmissions());
  }
});

// ==== PUSH NOTIFICATIONS ====
self.addEventListener('push', (event) => {
  console.log('[SW] Push received');
  const body = event.data ? event.data.text() : 'New notification from TaskCheck';

  const options = {
    body: body,
    icon: '/icons/icon-192x192.png',
    badge: '/icons/icon-192x192.png',
    vibrate: [100, 50, 100],
    data: {
      dateOfArrival: Date.now(),
      primaryKey: 1,
    },
    actions: [
      {
        action: 'explore',
        title: 'View details',
      },
      {
        action: 'close',
        title: 'Close',
      },
    ],
  };

  event.waitUntil(
    self.registration.showNotification('TaskCheck', options)
  );
});

self.addEventListener('notificationclick', (event) => {
  event.notification.close();

  if (event.action === 'explore') {
    event.waitUntil(clients.openWindow('/admin/dashboard'));
  } else if (event.action === 'close') {
    // niets
  } else {
    event.waitUntil(clients.openWindow('/'));
  }
});

// ==== OFFLINE SUBMISSIONS (BASIS) ====
// LET OP: hier géén document / window gebruiken! Service worker heeft dat niet.
async function handleOfflineSubmissions() {
  try {
    const submissions = await getOfflineSubmissions();

    for (const submission of submissions) {
      try {
        const res = await fetch('/api/submissions', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            // Eventuele auth/CSRF hier toevoegen als je dat later goed inricht.
          },
          body: JSON.stringify(submission),
        });

        if (res.ok) {
          await removeOfflineSubmission(submission.id);
          console.log('[SW] Offline submission synced', submission.id);
        } else {
          console.warn('[SW] Sync failed with status', res.status);
        }
      } catch (e) {
        console.error('[SW] Error syncing one submission', e);
      }
    }
  } catch (e) {
    console.error('[SW] Error in handleOfflineSubmissions', e);
  }
}

// Dummy helpers - implementeer dit in combinatie met IndexedDB als je het echt gaat gebruiken
async function getOfflineSubmissions() {
  // TODO: haal data uit IndexedDB
  return [];
}

async function removeOfflineSubmission(id) {
  // TODO: verwijder record uit IndexedDB
  return true;
}
