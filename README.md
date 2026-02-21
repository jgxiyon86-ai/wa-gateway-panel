# ALIMA GATEWAY Panel (Laravel)

Panel admin Laravel untuk mengelola engine WhatsApp Node (`wa-gateway-hub`), siap dikembangkan ke model SaaS/white-label.

## Arsitektur

- `wa-gateway-panel` (Laravel): UI admin, auth panel, proxy API.
- `wa-gateway-hub` (Node.js): koneksi WhatsApp (Baileys), session device, kirim pesan.

## Fitur Saat Ini

- Login panel
- Dashboard app + setting blast
- Device management (create/start/reconnect/disconnect/edit/delete/token)
- Phonebook contact + pilih kontak ke menu send
- WA Group list (pilih device -> update list group)
- Message history (load/search/delete/download)
- Send / bulk send

## Konfigurasi `.env`

```env
WA_NODE_BASE_URL=http://127.0.0.1:3210
WA_NODE_PANEL_USER=admin
WA_NODE_PANEL_PASSWORD=admin123

WA_PANEL_USER=admin
WA_PANEL_PASSWORD=admin123
```

## Jalankan Lokal

1. Pastikan Node engine hidup dulu:
```bash
cd c:\xampp\htdocs\wa-gateway-hub
npm run dev
```

2. Jalankan panel Laravel:
```bash
cd c:\xampp\htdocs\wa-gateway-panel
php artisan serve --host=127.0.0.1 --port=8092
```

3. Buka:
- `http://127.0.0.1:8092/login`

## Deploy Produksi (cPanel / VPS)

1. Deploy `wa-gateway-panel` seperti Laravel standar.
2. Jalankan `wa-gateway-hub` di server yang sama / server terpisah (PM2).
3. Set `WA_NODE_BASE_URL` ke URL engine Node produksi.
4. Gunakan HTTPS + reverse proxy.
5. Pisahkan credential panel dan credential node admin.

## Catatan Roadmap Jualan

- Multi-user + role/permission
- Billing subscription
- Audit log
- Tenant isolation
- Webhook delivery report
- Queue worker terpisah

