import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Pastikan skrip berjalan setelah seluruh elemen HTML selesai dimuat (DOM Ready)
document.addEventListener("DOMContentLoaded", () => {
    // Menangkap elemen tombol dan tag <html>
    const themeToggleBtn = document.getElementById("theme-toggle");
    const htmlElement = document.documentElement;

    // 1. Membaca Memori (Local Storage) dan Sistem Operasi
    const savedTheme = localStorage.getItem("theme");
    const systemPrefersDark = window.matchMedia(
        "(prefers-color-scheme: dark)",
    ).matches;

    // 2. Menetapkan Tema Awal (Berdasarkan memori, atau bawaan OS jika belum ada memori)
    if (savedTheme === "dark" || (!savedTheme && systemPrefersDark)) {
        htmlElement.setAttribute("data-theme", "dark");
    }

    // 3. Mengeksekusi Logika Pergantian Tema Saat Tombol Diklik
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener("click", () => {
            const currentTheme = htmlElement.getAttribute("data-theme");

            if (currentTheme === "dark") {
                // Ubah ke mode terang: Hapus atribut dan simpan ke memori
                htmlElement.removeAttribute("data-theme");
                localStorage.setItem("theme", "light");
            } else {
                // Ubah ke mode gelap: Tambah atribut dan simpan ke memori
                htmlElement.setAttribute("data-theme", "dark");
                localStorage.setItem("theme", "dark");
            }
        });
    }
});
