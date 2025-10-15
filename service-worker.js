self.addEventListener('install', event => {
  event.waitUntil(
    caches.open('mi-cache').then(cache => {
      return cache.addAll([
        '/',
        'clientear/index.html',
        '/otras-rutas-que-quieras-cache'
      ]);
    })
  );
});
