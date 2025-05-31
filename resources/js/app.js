import './bootstrap';

// resources/js/app.js

// resources/js/app.js

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/js/service-worker.js')
        .then(function(registration) {
            console.log('Service Worker registrado con éxito:', registration);

            // Aquí agregamos la suscripción a las notificaciones push
            registration.pushManager.getSubscription()
                .then(function(subscription) {
                    if (subscription) {
                        console.log('Usuario ya está suscrito:', subscription);
                    } else {
                        registration.pushManager.subscribe({
                            userVisibleOnly: true
                        }).then(function(subscription) {
                            console.log('Usuario suscrito a Push Notifications:', subscription);
                            // Enviar la suscripción al backend para almacenarla
                            saveSubscriptionToServer(subscription);
                        }).catch(function(error) {
                            console.error('Error al suscribir al usuario:', error);
                        });
                    }
                })
                .catch(function(error) {
                    console.error('Error al obtener la suscripción:', error);
                });

        })
        .catch(function(error) {
            console.log('Error al registrar el Service Worker:', error);
        });
}




function saveSubscriptionToServer(subscription) {
    fetch('/save-subscription', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(subscription)
    })
    .then(function(response) {
        if (response.ok) {
            console.log('Suscripción guardada en el servidor');
        } else {
            console.error('Error al guardar la suscripción');
        }
    })
    .catch(function(error) {
        console.error('Error al enviar la suscripción al servidor:', error);
    });
}



