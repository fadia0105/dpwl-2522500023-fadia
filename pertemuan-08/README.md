UTS DPWL - Manajemen Penyewa (Admin)
Nama : Fadia Khairun
NIM : 2522500023
Kelas: SI3A
Mata Kuliah: Desain & Pemrograman Web Lanjutan 

## Fitur yang ditambahkan
## Penjelasan Pengerjaan

### 1. Menu Navigasi
Saya menambahkan menu **Manajemen Penyewa** pada navigasi admin dengan mengubah berkas
`application/views/templates/sidebar.php`. Menu ini berisi tautan ke `site_url('admin/penyewa')`dengan ikon `fa-user`. 
Saya juga merapikan tag `<li>` pada menu lain yang sebelumnya belum tertutup agar susunan sidebar tidak rusak. 
Pada `application/config/routes.php` saya menambahkan empat route: `admin/penyewa`, `admin/penyewa/tambah`, `admin/penyewa/ubah/(:any)`, dan
`admin/penyewa/hapus/(:any)`, yang seluruhnya diarahkan ke `Mpenyewa_controller`.

### 2. Formulir dan Tampilan Data
Saya membuat berkas `application/views/admin/mpenyewa_view.php` dengan pola yang sama seperti `mobil_view.php`. Tabel dan formulir berada pada satu View, dan tampilannya ditentukan oleh variabel `$mode` (`null` untuk tabel saja, `tambah` atau `ubah` untuk menampilkan formulir).
Tabel menampilkan seluruh kolom tabel `t_penyewa`: username, NIK, nama lengkap, nomor HP, alamat, foto KTP, dan foto SIM A, kecuali kolom `sandi` yang sengaja tidak ditampilkan. 
Tabel memakai kelas `js-data-table` agar mendapat fitur DataTables (pencarian,pengurutan, dan paginasi).
Formulir memakai `enctype="multipart/form-data"` untuk unggah foto, serta menampilkan pratinjau foto yang dipilih dan foto yang sedang tersimpan.
