const CACHE_NAME = "app-v1";

const ASSETS = [
    "/",
    "/offline.html",
    "/img/logo/logo.png",
    "/img/obg/planta.png"
];

// instalar
self.addEventListener("install", (e) => {
    e.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(ASSETS))
    );
});

// ativar (limpa cache antigo)
self.addEventListener("activate", (e) => {
    e.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.map(key => {
                if (key !== CACHE_NAME) return caches.delete(key);
            }))
        )
    );
});

// fetch inteligente
self.addEventListener("fetch", (event) => {

    const url = new URL(event.request.url);

    // ❌ nunca cachear PHP
    if (url.pathname.endsWith(".php") || url.pathname.startsWith("/api")) {
        event.respondWith(
            fetch(event.request).catch(() => caches.match("/offline.html"))
        );
        return;
    }

    // ✅ cache-first para assets
    event.respondWith(
        caches.match(event.request).then(res => {
            return res || fetch(event.request).catch(() => {
                if (event.request.mode === "navigate") {
                    return caches.match("/offline.html");
                }
            });
        })
    );
});