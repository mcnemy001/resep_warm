import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    // Notifikasi welcome
    const welcomeNotification = document.getElementById('welcome-notification');
    if (welcomeNotification) {
        setTimeout(() => {
            welcomeNotification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                welcomeNotification.remove();
            }, 500);
        }, 3000);
    }

    // Notifikasi status umum
    const statusNotification = document.getElementById('status-notification');
    if (statusNotification) {
        setTimeout(() => {
            statusNotification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                statusNotification.remove();
            }, 500);
        }, 3000);
    }
});