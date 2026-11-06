# Panduan Kontribusi (Contributing Guidelines)

Terima kasih atas minat Anda untuk berkontribusi pada proyek **PPKD Laundry**! 

Proyek ini dibangun menggunakan **Laravel 12** dan **Bootstrap 5 (Mazer Theme)**. Panduan ini dibuat agar setiap kode yang ditambahkan atau diperbarui tetap terstruktur, rapi, dan sejalan dengan arsitektur inti aplikasi.

---

## 1. Aturan Emas: Struktur Database (ERD)
Sistem ini dirancang untuk **100% patuh pada rancangan ERD (Entity Relationship Diagram) awal**. 
- **DILARANG KERAS** menambah, menghapus, atau mengubah nama kolom/tabel di dalam database murni hanya untuk jalan pintas.
- Jika Anda menghadapi masalah bisnis logika yang kompleks, gunakan pendekatan *workaround* (manipulasi logika di *Controller* atau penyisipan penanda *string* di *notes/JSON*). 
- Harap baca file [`DOCUMENTATION.md`](DOCUMENTATION.md) untuk melihat contoh *workaround* cerdas yang sudah diimplementasikan (seperti fitur Pajak PPN dan Waktu Pembayaran).

## 2. Standar Kode & Formatting (Laravel Pint)
Proyek ini mengikuti standar format kode Laravel. Sebelum melakukan *commit* atau *pull request*, pastikan Anda telah merapikan kode Anda menggunakan **Laravel Pint**:
```bash
./vendor/bin/pint --format agent
```
Jika Anda memodifikasi file PHP apa pun, Anda **wajib** menjalankan perintah di atas untuk memastikan kode selaras dengan gaya yang diharapkan.

## 3. Pengujian (Testing) menggunakan Pest
Sistem ini menggunakan **Pest PHP** untuk pengujian. 
- Jika Anda menambahkan fitur krusial, sangat disarankan untuk menuliskan pengujian (*test*).
- Jalankan pengujian dan pastikan semuanya hijau (berhasil) sebelum mengirimkan kontribusi:
```bash
php artisan test --compact
```

## 4. Alur Kerja Kontribusi
1. **Fork atau Buat Branch Baru**: Jangan melakukan *push* langsung ke *branch* `main`. Buatlah *branch* baru dengan nama yang mendeskripsikan fitur/perbaikan Anda (contoh: `feat/laporan-harian` atau `fix/bug-kasir`).
2. **Commit Message**: Gunakan pesan *commit* yang jelas dan berbahasa Indonesia/Inggris yang baik. 
3. **Pull Request (PR)**: Ajukan PR dan jelaskan secara detail apa saja yang Anda ubah, mengapa perubahan tersebut dibutuhkan, dan apakah itu mempengaruhi tampilan antarmuka (UI).

## 5. Asisten AI & Folder Khusus
- **Folder `.agents/`**: Proyek ini telah dilengkapi dengan panduan standar (*guidelines*) khusus untuk asisten AI. Folder ini **sengaja kami simpan di Git (tidak di-ignore)**. Jika Anda menggunakan IDE dengan asisten AI yang mendukung konfigurasi *workspace*, asisten Anda otomatis akan membaca pedoman di `.agents/AGENTS.md` (seperti aturan ketat untuk tidak mengubah ERD). Ini sangat berguna agar kode yang dihasilkan AI Anda tetap konsisten dengan standar arsitektur kami. Tolong jangan hapus atau abaikan folder ini.
- **Folder `.codex/`**: Merupakan direktori *cache* indeks sementara untuk AI lokal. Folder ini diabaikan (ada di dalam `.gitignore`) karena isinya berbeda di tiap perangkat.
- Jangan menyimpan skrip sementara atau file sampah di direktori `public/` atau *root* proyek.

---

Dengan mengikuti pedoman di atas, Anda membantu menjaga aplikasi PPKD Laundry ini tetap bersih, aman, dan mudah dirawat di masa depan. Selamat mengoding! 🚀
