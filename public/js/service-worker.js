self.addEventListener('push', function(event) {
    let title = event.data ? event.data.title : 'Recordatorio';
    let body = event.data ? event.data.text() : 'Tienes un recordatorio pendiente.';
    let options = {
        body: body,
        icon: '/path/to/icon.png',
        badge: '/path/to/badge.png'
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});
