# pertemuan-01
1. Kesinambungan PWD – DPW – DPWL
Ketiganya merupakan tahapan mata kuliah/topik pengembangan aplikasi web yang saling berkelanjutan:
-PWD (Pemrograman Web Dasar):Berfokus pada fondasi web dasar seperti struktur tampilan (HTML), styling (CSS), dan logika pemrograman dasar/interaktivitas di sisi (JavaScript dasar) maupun server (PHP native dasar).
-DPW (Desain dan Pemrograman Web): Mengembangkan logika PWD ke tingkat menengah. Di sini dipelajari bagaimana mengintegrasikan logika PHP terstruktur dengan basis data (MySQL/CRUD), penanganan form, session/authentication, serta penerapan arsitektur aplikasi web dasar.
-DPWL (Desain dan Pemrograman Web Lanjut):Menyempurnakan DPW dengan menerapkan arsitektur software design pattern yang modern, yaitu MVC (Model-View-Controller) menggunakan framework PHP (seperti Laravel atau CodeIgniter), pengelolaan REST API, hingga keamanan web advanced.
2. Perbedaan PHP Terstruktur dan MVC
-PHP Terstruktur (Native/Procedural): Kode logika database, pemrosesan data, dan tampilan HTML digabung dalam satu file yang sama (atau terpisah secara sederhana menggunakan `include/require`). Sulit dikembangkan untuk skala besar dan lebih rumit dieksekusi secara tim karena semua fungsi menumpuk di satu tempat.
-PHP MVC (Model-View-Controller):Kode dipisah secara ketat menjadi 3 komponen utama berdasarkan tugasnya masing-masing. Arsitektur ini membuat kode lebih rapi, modular, mudah di-maintain, dan mendukung kolaborasi tim (misal: developer frontend fokus pada View, backend fokus pada Model & Controller).
3. Fungsi Model, View, dan Controller
-Model:Bertanggung jawab atas pengelolaan data dan logika bisnis. Model berhubungan langsung dengan database (menjalankan query SELECT, INSERT, UPDATE, DELETE).
-View:Bertanggung jawab untuk menyajikan tampilan antarmuka (UI) kepada pengguna (HTML/CSS). View hanya menerima data dari Controller dan menampilkannya tanpa memproses logika database.
-Controller: Bertindak sebagai jembatan/penengah antara Model dan View. Controller menerima request dari pengguna, meminta data dari Model jika diperlukan, lalu menentukan View mana yang harus ditampilkan beserta datanya.
4. Alur Request–Response MVC
-User Request: Pengguna mengakses URL atau mengirim form melalui browser.
-Routing & Controller: *Router* mengarahkan request ke Controller dan fungsi (method) yang sesuai.
-Model Interaction: Controller memanggil Model jika request membutuhkan manipulasi atau pengambilan data dari database.
-Data Retrieval: Model memproses ke database dan mengembalikan hasil datanya ke Controller.
-View Rendering: Controller melempar data tersebut ke View yang dituju untuk disusun menjadi tampilan HTML.
-Response Hasil tampilan HTML dikirimkan kembali oleh Controller ke browser pengguna.
5. Pemetaan Fitur Aplikasi DPW ke Model, Controller, dan View (Contoh Fitur: Manajemen Login/Authentication)
6. Kesimpulan P1
Transisi dari PHP Terstruktur (DPW) ke konsep MVC (DPWL) merupakan langkah krusial dalam pengembangan web modern. Dengan memisahkan logika data (Model), antarmuka (View), dan pemrosesan alur (Controller), kode program menjadi jauh lebih terstruktur, aman, mudah dipelihara, dan siap dikembangkan untuk skala aplikasi yang lebih besar.PWD (Pemrograman Web Dasar):** Berfokus pada fondasi web dasar seperti struktur tampilan (HTML), styling (CSS), dan logika pemrograman dasar/interaktivitas di sisi client (JavaScript dasar) maupun server (PHP native dasar).
-DPW (Desain dan Pemrograman Web):** Mengembangkan logika PWD ke tingkat menengah. Di sini dipelajari bagaimana mengintegrasikan logika PHP terstruktur dengan basis data (MySQL/CRUD), penanganan form, session/authentication, serta penerapan arsitektur aplikasi web dasar.
-DPWL (Desain dan Pemrograman Web Lanjut):** Menyempurnakan DPW dengan menerapkan arsitektur *software design pattern* yang modern, yaitu MVC (Model-View-Controller) menggunakan framework PHP (seperti Laravel atau CodeIgniter), pengelolaan REST API, hingga keamanan web advanced.
-Fitur: Login Pengguna
View (`login.php` / `login.blade.php`): Menampilkan form input username/email dan password serta tombol Submit.
Alasan: View hanya bertugas menyediakan antarmuka visual agar pengguna bisa memasukkan kredensial login.
-Controller (`AuthController.php`): Menerima input data dari form login, melakukan validasi kelengkapan form, memanggil fungsi verifikasi di Model, mengeset session jika berhasil, dan mengarahkan (redirect) ke halaman dashboard atau menampilkan pesan error jika gagal.
Alasan:Controller mengendalikan alur logika proses login dan alur navigasi halaman berdasarkan respon data.
-Model (`UserModel.php`): Menjalankan query ke database untuk mencari akun pengguna berdasarkan email/username, lalu mencocokkan password (hash) yang diinputkan.
Alasan: Model adalah satu-satunya komponen yang berinteraksi langsung dengan tabel `users` di database.
