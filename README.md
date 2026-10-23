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

```

2. **Install Dependencies**
```bash
composer install
npm install && npm run build

```


3. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate

```


*Configure your MySQL database connection and WebSocket credentials in `.env`.*
4. **Database Migration & Seeding**
```bash
php artisan migrate --seed

```


5. **Storage Symlink & Real-time Server**
```bash
php artisan storage:link
php artisan reverb:start

```


6. **Serve Application**
```bash
php artisan serve

```



---

## 🤖 Client Telemetry Script Setup

The repository includes a PowerShell client script (`tga-monitor.ps1`) designed to run on client machines via Windows Task Scheduler.

1. Open `tga-monitor.ps1` and set your local endpoint and API Key:
```powershell
$ApiUrl = "http://localhost:8000/api/devices/sync"
$ApiKey = "YOUR_SECRET_API_KEY"

```


2. Schedule the script to run periodically or at Windows startup using Task Scheduler:
```powershell
powershell.exe -ExecutionPolicy Bypass -WindowStyle Hidden -File "C:\Path\To\tga-monitor.ps1"

```



---

## 🔒 Security & Sanitization Note

This repository is sanitized for showcase and portfolio purposes. All private credentials, database dumps, IP address configurations, and production API tokens have been scrubbed or replaced with mock placeholders.

```

```
