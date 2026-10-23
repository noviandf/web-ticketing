import axios from 'axios';
import Swal from 'sweetalert2';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Konfigurasi Laravel Echo & Reverb WebSockets
 */
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 5000, // Hilang otomatis dalam 5 detik
    timerProgressBar: true,
    background: '#1f2937', // Warna latar gelap yang relevan dengan tema dashboard Anda
    color: '#ffffff',
    iconColor: '#3b82f6', // Warna biru untuk ikon info
    didOpen: (toast) => {
        // Logika agar notifikasi tidak hilang jika kursor mouse sedang berada di atasnya
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
        
        // Refresh halaman jika pop-up notifikasi diklik
        toast.addEventListener('click', () => {
            window.location.reload();
        })
    }
});

window.Echo.channel('public-notifications')
    .listen('.TicketEvent', (e) => {
        let metaUserId = document.querySelector('meta[name="user-id"]');
        let currentUserId = metaUserId ? parseInt(metaUserId.getAttribute('content')) : null;

        // Jangan tampilkan notifikasi jika kita sendiri yang melakukan aksi tersebut
        if (e.user_id === currentUserId) {
            return;
        }
        
        // LAPIS 1: SweetAlert2 (Muncul di dalam web)
        Toast.fire({
            icon: 'info',
            title: 'Notifikasi Sistem',
            text: e.message
        });

        // LAPIS 2: Dynamic Title Bar (Mengubah teks tab browser)
        // Menyimpan title asli, lalu mengubahnya
        let originalTitle = document.title;
        document.title = "🔴 (1) TIKET BARU! - " + originalTitle;
        
        // Mengembalikan title ke semula setelah 5 detik (opsional)
        setTimeout(() => {
            document.title = originalTitle;
        }, 5000);

        let audio = new Audio('/notif.mp3');
        audio.play().catch(error => console.log("Browser memblokir autoplay audio sebelum user berinteraksi dengan halaman."));

        // LAPIS 3: Native Desktop Notification (Muncul di OS Windows/Mac)
        // Cek apakah browser mendukung notifikasi
        if (!("Notification" in window)) {
            console.log("Browser ini tidak mendukung desktop notification");
        } 
        // Jika sudah diizinkan oleh user
        else if (Notification.permission === "granted") {
            let deskNotif = new Notification("TGA Ticketing System", {
                body: e.message,
                icon: '/favicon.png' // Akan mengambil logo TGA Anda
            });
            
            deskNotif.onclick = function() {
                window.focus();
                window.location.reload();
            };
        } 
        // Jika belum meminta izin, kita minta izin sekarang
        else if (Notification.permission !== "denied") {
            Notification.requestPermission().then((permission) => {
                if (permission === "granted") {
                    let deskNotif = new Notification("TGA Ticketing System", {
                        body: e.message,
                        icon: '/favicon.png'
                    });
                    
                    deskNotif.onclick = function() {
                        window.focus();
                        window.location.reload();
                    };
                }
            });
        }

    });