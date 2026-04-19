# Government Portal: Deployment & Server Guide

This document contains all essential information for interacting with the live Hostinger environment and managing deployments. 

> [!WARNING]
> **This file is included in `.gitignore` and MUST NOT be pushed to GitHub** because it contains sensitive server credentials. It will permanently stay only on your local machine.

## 🌐 Live System Information

- **Frontend URL:** [https://goveportal.keromultiservice.com](https://goveportal.keromultiservice.com)
- **Local Application Domain:** http://127.0.0.1:8001 (via `php artisan serve --port=8001`)
- **Hosting Provider:** Hostinger (Shared Hosting Environment)

---

## 🔑 PuTTY & SSH Credentials

Use these details when connecting via PuTTY or any SSH client to the Hostinger server:

- **Hostname / IP:** `fr-int-web1468` (or use your domain `goveportal.keromultiservice.com`)
- **Port:** `65002` *(Standard Hostinger SSH Port - verify in your Hostinger Panel if this fails)*
- **SSH Username:** `u182311384`
- **SSH Password:** `6Uh89P|gN>j`
- **Target Directory:** `public_html`

---

## 🗄️ Database Information (Production)

The production server uses MySQL, hooked up to the local `.env` of the server. 

- **Database Name:** `u182311384_govpor`
- **Database Username:** `u182311384_gov`

### 👤 Test User Accounts

The database was successfully seeded with the following development credentials:

*   **Main Admin:** 
    *   Username: `admin` | Password: `password`
    *   Username: `mehdi` | Password: `aloalo`
*   **Institutional Admin:** `institutional_admin` | `password`
*   **Sectoral Admin:** `sectoral_admin` | `password`
*   **Company Account:** `testcompany` | `password`
*   **Candidate Account:** `testcandidate` | `password`

---

## 🚀 The Deployment Workflow

If you make changes to your local project, here is the exact step-by-step process to push them to the live server safely.

### Step 1: Prepare the Frontend (Local Machine)
If you add new Tailwind classes, alter CSS, or change JS files, you **must** build the assets locally before pushing, as Hostinger does not have `npm` installed.

```bash
# Run this in your local Laragon terminal before committing
npm run build
```

### Step 2: Push to GitHub (Local Machine)
Commit your changes (including the `public/build` folder if modified).

```bash
git add -A
git commit -m "Describe your updates here"
git push origin main
```

### Step 3: Pull to Hostinger (PuTTY terminal)
Log in to your Hostinger server using PuTTY, navigate to your public directory, and pull the fresh code.

```bash
# 1. Ensure you are in the right folder
cd public_html

# 2. Pull the latest code
git pull origin main

# 3. Clear the Laravel caches to force the server to see your new changes
php artisan view:clear
php artisan route:clear
php artisan cache:clear
php artisan config:clear
```

> [!TIP]
> If you make changes to the database structure (new migrations), don't forget to also run `php artisan migrate --force` during Step 3!
