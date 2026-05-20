self.addEventListener('push', function(event) {
    if (!event.data) return;
    const data = event.data.json();
    self.registration.showNotification(data.title || 'Notification', {
        body: data.body || '',
    });
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
});