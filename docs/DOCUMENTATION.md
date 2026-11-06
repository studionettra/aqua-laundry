# Dokumentasi Kode Program: PPKD Laundry

Dokumen ini berfungsi sebagai panduan (*manual book*) untuk memahami arsitektur, alur kerja, dan logika khusus yang diterapkan di dalam sistem aplikasi PPKD Laundry. 

## 1. Informasi Umum
- **Framework Utama**: Laravel 12.63.0
- **Tema Tampilan (UI/UX)**: Mazer (Bootstrap 5)
- **Database**: Relasional (MySQL) sesuai ERD (Entity Relationship Diagram) PPKD.

## 2. Struktur Direktori Utama
Bagi Anda (atau programmer lain) yang ingin memodifikasi kode, fokuslah pada folder berikut:
- `app/Http/Controllers/` : Tempat beradanya otak atau logika dari aplikasi (*Backend*).
- `app/Models/` : Representasi dari setiap tabel yang ada di dalam database.
- `resources/views/` : Tempat file desain antarmuka/UI berada (*Frontend*), menggunakan bahasa template *Blade*.
- `database/migrations/` : Kumpulan *blueprint* atau sejarah pembuatan tabel database.

## 3. Logika Bisnis Khusus (*Custom Business Logic*)

Untuk mempertahankan struktur database yang murni agar 100% sama dengan *blueprint* rancangan ERD asli, sistem ini menerapkan beberapa logika penyelesaian khusus (*workaround*):

### A. Arsitektur Pajak (Tax)
Sistem memiliki fitur pajak, namun tidak ada tabel `taxes` maupun kolom `tax_amount` di dalam tabel transaksi.
**Bagaimana cara kerjanya?**
1. **Penyimpanan Setelan**: Status aktif/tidaknya pajak dan persentasenya disimpan di dalam file teks: `storage/app/tax_settings.json`. Ini dikelola oleh `TaxController`.
2. **Injeksi Saat Transaksi**: Saat Kasir membuat transaksi baru, `TransOrderController` akan mengecek file JSON tersebut. Jika pajak aktif, sistem akan secara otomatis menambahkan satu baris layanan fiktif (*dummy service*) bernama **"Pajak PPN"** ke dalam tabel `trans_order_detail`.
3. **Penyaringan (Filtering) Laporan**: Saat nota atau laporan dicetak, sistem akan memisahkan baris "Pajak PPN" ini dari layanan cuci reguler, dan menampilkannya di bagian bawah (*footer*) struk agar tampak profesional.

### B. Waktu Pembayaran (Payment Timing)
Tidak ada kolom yang menyimpan status apakah pelanggan memilih opsi "Bayar di Awal" atau "Bayar di Akhir".
**Bagaimana cara kerjanya?**
1. **Deduksi Logika Implisit**: Saat pesanan dibuat, sistem bergantung murni pada kolom uang bayar (`order_pay`) di tabel `trans_order`. Kolom ini bersifat *nullable* (boleh kosong).
2. **Bayar di Awal**: Jika kolom `order_pay` terisi angka sejak awal, sistem menyimpulkan bahwa pelanggan telah membayar saat menitipkan cucian. UI akan melabelinya dengan *badge* **Bayar Di Awal**.
3. **Bayar di Akhir**: Jika kolom `order_pay` `NULL`, sistem menganggap pelanggan menunda pembayaran. UI akan melabelinya dengan *badge* **Bayar Di Akhir**.
4. **Workaround Saat Pickup**: Ketika pelanggan "Bayar di Akhir" akhirnya melunasi tagihannya saat *pickup*, kolom `order_pay` akan terisi. Untuk mencegah sistem salah mengiranya sebagai "Bayar di Awal", sistem otomatis menyisipkan penanda tersembunyi `(Lunas dibayar saat Pickup)` ke dalam kolom catatan (*notes*) di tabel `trans_laundry_pickup`. UI membaca penanda ini untuk tetap mempertahankan label **Bayar Di Akhir** yang benar.

## 4. Dependensi & Library Tambahan
- **Select2 (via CDN)**: Digunakan pada halaman *Create Order* untuk memunculkan fitur pencarian (Search) di dalam *dropdown* pilihan pelanggan agar UI lebih rapi dan mempercepat kerja kasir.
- **SweetAlert2**: Digunakan untuk menampilkan notifikasi sukses/error bergaya *pop-up* yang elegan.

---
*Dokumen ini dapat terus diperbarui (di-update) seiring dengan berkembangnya fitur aplikasi ini di masa depan.*
