# IT Ticketing & Hardware Telemetry System

A web-based internal tool built for IT Support operations to streamline ticket lifecycle management, track client device telemetry via PowerShell automation, and deliver real-time system notifications.

---

## 🚀 Key Features

* **Ticket Management System:** Centralized ticket handling with status tracking, issue categorization, priority levels, and resolution logs.
* **Automated Hardware Telemetry:** Client-side PowerShell automation script to collect device metrics (CPU/RAM usage, free disk space, uptime, and battery health) via REST API.
* **Real-time Notifications:** WebSockets integration powered by **Laravel Reverb** for instant ticket updates and alert triggers without refreshing the browser.
* **Battery Health Diagnostics:** Automated generation and upload of native Windows HTML battery reports for mobile/laptop asset audits.
* **Data Export & Reporting:** Streamed CSV export for hardware inventory, telemetry logs, and operational performance audits.

---

## 🛠️ Tech Stack

* **Backend Framework:** PHP 8.x, Laravel 11/12
* **Frontend:** Blade Templates, Tailwind CSS, Alpine.js, Vite
* **Database:** MySQL
* **Real-time Server:** Laravel Reverb (WebSockets)
* **Client Automation:** Windows PowerShell Scripting (WMI / CIM Querying)

---

## ⚙️ Local Installation & Setup

1. **Clone Repository**
   ```bash
   git clone [https://github.com/noviandf/web-ticketing.git](https://github.com/noviandf/web-ticketing.git)
   cd web-ticketing
