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
Saya membuat berkas `application/views/admin/mpenyewa_view.php` dengan pola kerja yang sama seperti
`mobil_view.php`: tabel dan formulir berada pada satu View, dan tampilannya ditentukan oleh variabel
`$mode` (`null` untuk daftar saja, `tambah` atau `ubah` untuk menampilkan formulir). Agar tampilannya
tidak monoton, saya mengembangkan desainnya dengan beberapa elemen tambahan:

- **Banner judul** berisi deskripsi halaman dan tombol "Penyewa Baru".
- **Tiga kotak ringkasan** yang dihitung di View dari data yang ada: total penyewa, jumlah penyewa
  yang sudah mengunggah KTP, dan jumlah yang sudah mengunggah SIM A.
- **Daftar penyewa** memakai kelas `js-data-table` sehingga mendapat fitur DataTables (pencarian,
  pengurutan, paginasi). Setiap penyewa ditampilkan dengan lingkaran inisial nama, nama lengkap,
  username, NIK, nomor HP, alamat, dan label status dokumen KTP dan SIM A (hijau jika sudah ada,
  abu-abu jika belum).
- **Modal foto:** saat label dokumen diklik, foto KTP atau SIM A tampil dalam jendela pop-up
  Bootstrap tanpa berpindah halaman.
- **Modal konfirmasi hapus:** tombol hapus membuka pop-up yang menampilkan nama penyewa. JavaScript
  mengisi nama dan alamat tujuan formulir secara dinamis dari atribut `data-nama` dan `data-action`,
  sehingga satu modal dipakai untuk semua baris.
- **Formulir dua kolom:** kolom kiri berisi data diri (nama, NIK, nomor HP, alamat), kolom kanan
  berisi akun login (username, password) dan unggah dokumen.
- **Bantuan pengisian:** tombol lihat/sembunyikan password, penghitung digit NIK (0/16) yang hanya
  menerima angka, serta pratinjau foto yang dipilih sebelum disimpan.

Tabel menampilkan data dari seluruh kolom tabel `t_penyewa` kecuali `sandi`, yang sengaja tidak
ditampilkan demi keamanan. Formulir memakai `enctype="multipart/form-data"` agar dapat mengunggah
foto.