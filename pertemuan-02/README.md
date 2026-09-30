# pertemuan-02
## 1. Tujuan dari Praltikum pertemuan 2
-	Memahami konsep dasar dan alur kerja arsitektur Model-View-Controller (MVC) dengan membuatnya secara manual dari awal (from scratch) tanpa framework. 
-	Menyusun struktur folder aplikasi web agar rapi dan terisolasi dengan baik. 
-	Menerapkan teknik Front Controller sebagai jalur tunggal masuknya request. 
-	Membuat sistem Routing untuk memetakan alamat URL ke Controller, method, dan parameter yang sesuai. 
-	Membuat fungsi helper URL (base_url() dan site_url()) untuk mempermudah pemanggilan aset statis dan navigasi antar halaman.

## 2. Struktur Direktori
Folder PATH listing for volume OS
Volume serial number is 0426-97FE
Folder PATH listing for volume OS
Volume serial number is 0426-97FE
C:.
│   index.php
│   
├───application
│   ├───config
│   │       config.php
│   │       routes.php
│   │       
│   ├───controllers
│   │       Home.php
│   │       
│   ├───helpers
│   │       url_helper.php
│   │       
│   └───views
│       └───home
│               index.php
│               info.php
│               
├───assets
│   └───css
│           app.css
│           
└───system
    └───core
            Controller.php
            Router.php

-application/: Direktori utama yang menampung seluruh kode logika, aturan bisnis, serta tampilan aplikasi yang di buat.
-config/: Berisi file-file konfigurasi aplikasi, seperti pengaturan database, rute URL (routes), autoload library, dan setelan keamanan.
-controllers/: Tempat menyimpan file Controller. Berfungsi sebagai jembatan/pengatur lalu lintas data yang menerima permintaan (request) dari pengguna, memproses logika, lalu menentukan tampilan (view) mana yang harus dimuat.
-helpers/: Berisi fungsi-fungsi bantuan kecil yang sifatnya kustom (misalnya fungsi format tanggal, format mata uang, atau pemotong teks) yang bisa dipanggil di mana saja dalam aplikasi.
-views/: Tempat menyimpan file antarmuka (UI) yang berisi kode HTML/CSS/PHP dasar untuk ditampilkan ke layar pengguna.
-home/: Sub-folder khusus untuk mengelompokkan file tampilan yang berkaitan dengan halaman Home (misalnya index.php, header.php, footer.php).
-assets/: Folder publik yang digunakan untuk menyimpan berkas-berkas statis aplikasi agar bisa diakses langsung oleh browser.
-css/: Tempat menyimpan file stylesheet (.css) yang mengatur tampilan visual dan pemodelan tata letak web.
-system/: Folder inti (core) bawaan framework. Folder ini berisi pustaka dasar, engine utama, dan fungsi bawaan yang membuat framework bisa berjalan.
    
## 3. Front controller
File index.php yang berada di direktori paling luar berfungsi sebagai Front Controller. Tugas utamanya adalah menangkap semua request URL dari browser sebelum diteruskan ke sistem. 
Proses yang dilakukan oleh index.php:
-	Menentukan konstanta jalur folder (FCPATH, APPPATH, SYSPATH). 
-	Memuat (load) file konfigurasi, helper, kelas inti (Controller.php & Router.php), serta aturan rute (routes.php). 
- Mengambil URL yang diakses pengguna. 
- Memanggil Router untuk menjalankan controller dan method yang sesuai. 
Manfaat metode ini adalah membuat struktur kode lebih aman dan tertata, karena pengguna tidak bisa langsung membuka file controller atau view secara acak via URL

## 4. Routing daan Pemetaan URL

## 5.	Base URL dan helper 
(jelaskan fugsi base URl () dan site URL(), kemudian berikan contoh penggunya pada implementasi P2
Base URL berfungsi menghasilkan URL lengkap ke root serta menghasilkan url asset CSS
Localhost/dwpl-2522500023/
Site_url berfungsi menampilkan parameter routing dan  membentuk URL navigasi internal aplikasi melewati front controller 
Localhost/dpwl-2522500023/index.php/info/routing

## 6. Alur Request-response 
Alur Eksekusi Aktual P2
Alur ini berjalan tanpa melibatkan database:
- Browser: User melakukan request URL melalui browser.   
- index.php: Request masuk ke file index.php yang berfungsi sebagai entry point utama aplikasi.   
- Router: index.php meneruskan permintaan ke Router untuk mencocokkan URL dengan route yang sesuai.   
- Controller: Router memanggil Controller untuk memproses permintaan.  
- View: Controller langsung memanggil file View tanpa mengambil data dari Model/database.   
- Response: View dirender menjadi tampilan (HTML/JSON) lalu dikirim kembali ke browser user.   

Posisi Model dalam Arsitektur MVC Lengkap
Alur ini menggambarkan proses MVC secara utuh saat aplikasi sudah terhubung ke database:
- Browser: User melakukan request melalui browser.   
- index.php: Entry point menerima request.   
- Router: Mengarahkan request ke Controller.   
- Controller: Meminta data ke Model karena membutuhkan informasi dari database.   
- Model & Basis Data: Model melakukan query/olah data ke basis data (database), lalu menerima balik hasilnya.
- Model ke Controller: Model mengembalikan data yang sudah diproses ke Controller.  
-  View: Controller mengoper data tersebut ke View untuk disajikan.   Response: Hasil akhir dikirim kembali sebagai respon ke browser.   

## 7. Hasil pengujian Dan debugging
jika Ditemukan Kesalahan / Obstacle Selama Implementasi 
- Gejala:
Saat mengakses URL custom route http://localhost/dpwl-0344300002/index.php/info/routing, sistem menampilkan pesan error HTTP 500: "View tidak ditemukan."
- Penyebab:
Terjadi kesalahan penulisan (typo) pada nama berkas View di direktori application/views/home/. Berkas tersimpan dengan nama infop.php atau berada di luar folder home/, sehingga pemanggilan $this->view('home/info', $data); pada Home.php gagal menemukan file lokasi application/views/home/info.php.
- Perbaikan:
Mengubah nama berkas (rename) dari infop.php menjadi info.php di dalam direktori application/views/home/ agar sesuai dengan argumen pemanggilan View pada Controller.   
- Hasil Uji Ulang:
URL http://localhost/dpwl-0344300002/index.php/info/routing diakses kembali pada peramban. Halaman berhasil memuat View info.php dengan parameter topik routing tanpa pesan error.

## 8. Bukti tangkapan layar
Sisipkan gambar yang relavan dari folder dokumentasi/dengan perintah:
### gambar 1. hasil pengujian utama 
![Gambar 1 - Halaman Utama](dokumentasi/gambar1.jpg)
### gambar 1. hasil pengujian custom Route
![Gambar 1 - Halaman Utama](dokumentasi/gambar2.jpg)


## 9. Pada Pertemuan 02 (P2), kerangka aplikasi berbasi MVC yang dibangun telah berhasil menyelesaikan fondasi utama arsitektur web.
yang sudah dapat dilakukan oleh kerangka MVC saat ini (P2):
-Front Controller & Single Entry Point: Aplikasi telah menggunakan index.php sebagai satu-satunya titik masuk (entry point) untuk menangani seluruh request dinamis.   
-Sistem Routing Dinamis: Router.php dan routes.php mampu memetakan URL secara fleksibel ke Controller, method/action, serta meneruskan parameter ke komponen aplikasi.   
-Pemisahan Alur Interaksi (Controller & View): Controller berperan mengatur alur logika dan data, sedangkan View hanya berfokus menyajikan struktur HTML.   
-Pengelolaan URL & Aset: Menggunakan Helper (url_helper.php) untuk fungsi base_url() dan site_url() dalam memuat aset statis (seperti CSS) dan membuat tautan navigasi antarhalaman secara konsisten.  
- Handling Error Dasar: Router dan Base Controller mampu mendeteksi dan menangani kondisi Controller/Method tidak ditemukan (HTTP 404) serta View tidak ditemukan (HTTP 500).   