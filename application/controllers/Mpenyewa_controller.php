<?php
// UTS: CRUD Manajemen Penyewa (Admin) - pola MVC sama dengan Mobil_controller
class Mpenyewa_controller extends Controller
{
    private Mpenyewa_model $model;

    public function __construct()
    {
        require_once APPPATH . 'models/Mpenyewa_model.php';
        require_once APPPATH . 'helpers/auth_helper.php';
        require_once APPPATH . 'helpers/flash_helper.php';
        $this->model = new Mpenyewa_model();
    }

    // READ: tabel + flash (hasil PRG)
    public function index(): void
    {
        require_admin_login();
        $this->view('admin/mpenyewa_view', [
            'username'        => $_SESSION['admin_username'],
            'penyewa'         => $this->model->getAll(),
            'flash'           => get_flash_message(),
            'mode'            => null,
            'penyewa_dipilih' => null,
            'error'           => null,
        ]);
    }

    private function renderPenyewa(string $mode, ?array $dipilih = null, ?string $error = null): void
    {
        $this->view('admin/mpenyewa_view', [
            'username'        => $_SESSION['admin_username'],
            'penyewa'         => $this->model->getAll(),
            'flash'           => null,
            'mode'            => $mode,
            'penyewa_dipilih' => $dipilih,
            'error'           => $error,
        ]);
    }

    private function redirect(): void
    {
        header('Location: ' . site_url('admin/penyewa'));
        exit;
    }

    // Validasi + sanitasi data teks (mengacu aturan Auth/Penyewa_controller)
    private function validateInput(array $d): ?string
    {
        if (!preg_match('/\A[0-9]{16}\z/', $d['nik'])) {
            return 'NIK wajib terdiri dari tepat 16 digit angka.';
        }
        if (
            $d['nm_lngkp'] === '' || mb_strlen($d['nm_lngkp']) > 100 ||
            !preg_match("/\A[\p{L}\p{M}]+(?:[ .'-][\p{L}\p{M}]+)*\z/u", $d['nm_lngkp'])
        ) {
            return 'Nama lengkap wajib diisi, maksimal 100 karakter, dan hanya berisi huruf serta pemisah nama yang wajar.';
        }
        if (
            $d['no_hp'] === '' || strlen($d['no_hp']) > 15 ||
            !preg_match('/\A[0-9+(). -]+\z/', $d['no_hp']) || !preg_match('/[0-9]/', $d['no_hp'])
        ) {
            return 'Nomor HP wajib diisi, maksimal 15 karakter, dan hanya boleh berisi angka serta + . ( ) spasi atau -.';
        }
        if (
            $d['almt_lngkp'] === '' || mb_strlen($d['almt_lngkp']) > 300 ||
            preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $d['almt_lngkp'])
        ) {
            return 'Alamat wajib diisi, maksimal 300 karakter, dan tidak boleh mengandung karakter kontrol.';
        }
        return null;
    }

    private function validatePassword(string $pw): ?string
    {
        if (strlen($pw) < 8) {
            return 'Password minimal 8 karakter.';
        }
        if (strlen($pw) > 72) {
            return 'Password maksimal 72 byte.';
        }
        return null;
    }

    private function uploadFoto(string $field): array
    {
        if (!in_array($field, ['ft_ktp', 'ft_sim_a'], true)) {
            return ['success' => false, 'filename' => null, 'error' => 'Field foto tidak valid.'];
        }
        if (!isset($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return ['success' => true, 'filename' => null, 'error' => null];
        }
        $file = $_FILES[$field];
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return ['success' => false, 'filename' => null, 'error' => 'Unggah foto gagal. Periksa ukuran file dan konfigurasi PHP.'];
        }
        if (
            !isset($file['tmp_name'], $file['size']) ||
            !is_uploaded_file($file['tmp_name']) ||
            (int) $file['size'] < 1 || (int) $file['size'] > 5 * 1024 * 1024
        ) {
            return ['success' => false, 'filename' => null, 'error' => 'Foto harus berukuran lebih dari 0 byte dan maksimal 5 MB.'];
        }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!isset($allowed[$mime]) || @getimagesize($file['tmp_name']) === false) {
            return ['success' => false, 'filename' => null, 'error' => 'Format foto harus JPEG, PNG, atau WEBP yang valid.'];
        }
        $dir = dirname(APPPATH) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'penyewa' . DIRECTORY_SEPARATOR;
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            return ['success' => false, 'filename' => null, 'error' => 'Folder penyimpanan foto tidak dapat dibuat.'];
        }
        if (!is_writable($dir)) {
            return ['success' => false, 'filename' => null, 'error' => 'Folder penyimpanan foto tidak dapat ditulis.'];
        }
        try {
            $filename = $field . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
        } catch (Throwable $e) {
            return ['success' => false, 'filename' => null, 'error' => 'Nama file foto gagal dibuat.'];
        }
        if (!move_uploaded_file($file['tmp_name'], $dir . $filename)) {
            return ['success' => false, 'filename' => null, 'error' => 'Foto gagal dipindahkan ke folder penyimpanan.'];
        }
        return ['success' => true, 'filename' => $filename, 'error' => null];
    }

    // Hanya menghapus nama file yang sesuai pola hasil uploadFoto()
    private function hapusFoto(array $filenames): void
    {
        $dir = dirname(APPPATH) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'penyewa' . DIRECTORY_SEPARATOR;
        foreach ($filenames as $f) {
            if (is_string($f) && preg_match('/^(ft_ktp|ft_sim_a)_\d{10,14}_[a-f0-9]{12,16}\.(jpg|jpeg|png|webp)$/', $f)) {
                $path = $dir . $f;
                if (is_file($path)) {
                    @unlink($path);
                }
            }
        }
    }

    private function bacaPost(): array
    {
        return [
            'nik'        => trim((string) ($_POST['nik'] ?? '')),
            'nm_lngkp'   => trim((string) ($_POST['nm_lngkp'] ?? '')),
            'no_hp'      => trim((string) ($_POST['no_hp'] ?? '')),
            'almt_lngkp' => trim((string) ($_POST['almt_lngkp'] ?? '')),
        ];
    }

    // CREATE
    public function tambah(): void
    {
        require_admin_login();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->renderPenyewa('tambah');
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Metode request tidak diizinkan.');
        }

        $userPlg = trim((string) ($_POST['user_plg'] ?? ''));
        $sandi   = (string) ($_POST['sandi'] ?? '');
        $data    = array_merge(
            ['user_plg' => $userPlg, 'ft_ktp' => null, 'ft_sim_a' => null],
            $this->bacaPost()
        );

        $error = null;
        if (!preg_match('/\A[A-Za-z0-9._-]{1,15}\z/', $userPlg)) {
            $error = 'Username wajib 1-15 karakter: huruf, angka, titik, garis bawah, atau strip.';
        }
        $error ??= $this->validatePassword($sandi);
        $error ??= $this->validateInput($data);
        if ($error === null && $this->model->findByUserPlg($userPlg) !== null) {
            $error = 'Username sudah digunakan penyewa lain.';
        }
        if ($error !== null) {
            $this->renderPenyewa('tambah', $data, $error);
            return;
        }

        $uploaded = [];
        foreach (['ft_ktp', 'ft_sim_a'] as $field) {
            $up = $this->uploadFoto($field);
            if (!$up['success']) {
                $this->hapusFoto($uploaded);
                $this->renderPenyewa('tambah', $data, $up['error']);
                return;
            }
            $data[$field] = $up['filename'];
            if ($up['filename'] !== null) {
                $uploaded[] = $up['filename'];
            }
        }

        $data['sandi'] = password_hash($sandi, PASSWORD_DEFAULT); // password disimpan sebagai HASH
        try {
            $ok = $this->model->create($data);
        } catch (Throwable $e) {
            $ok = false;
        }
        if (!$ok) {
            $this->hapusFoto($uploaded);
            $this->renderPenyewa('tambah', $data, 'Data penyewa gagal disimpan. Periksa kembali data yang dimasukkan.');
            return;
        }
        set_flash_message('success', 'Data penyewa berhasil ditambahkan.');
        $this->redirect(); // PRG
    }

    // UPDATE
    public function ubah(string $userPlg): void
    {
        require_admin_login();
        $userPlg = rawurldecode($userPlg);
        $lama = $this->model->findByUserPlg($userPlg);
        if ($lama === null) {
            set_flash_message('danger', 'Data penyewa tidak ditemukan.');
            $this->redirect();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->renderPenyewa('ubah', $lama);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Metode request tidak diizinkan.');
        }

        $data = array_merge(
            ['user_plg' => $userPlg, 'ft_ktp' => $lama['ft_ktp'], 'ft_sim_a' => $lama['ft_sim_a']],
            $this->bacaPost()
        );
        $sandiBaru = (string) ($_POST['sandi'] ?? '');

        $error = $this->validateInput($data);
        if ($error === null && $sandiBaru !== '') {
            $error = $this->validatePassword($sandiBaru);
        }
        if ($error !== null) {
            $this->renderPenyewa('ubah', array_merge($lama, $data), $error);
            return;
        }

        $uploaded = [];
        $fotoLama = [];
        foreach (['ft_ktp', 'ft_sim_a'] as $field) {
            $up = $this->uploadFoto($field);
            if (!$up['success']) {
                $this->hapusFoto($uploaded);
                $this->renderPenyewa('ubah', array_merge($lama, $data), $up['error']);
                return;
            }
            if ($up['filename'] !== null) {
                $uploaded[] = $up['filename'];
                if (!empty($lama[$field])) {
                    $fotoLama[] = $lama[$field];
                }
                $data[$field] = $up['filename'];
            }
        }

        $hash = $sandiBaru !== '' ? password_hash($sandiBaru, PASSWORD_DEFAULT) : null;
        try {
            $ok = $this->model->update($userPlg, $data, $hash);
        } catch (Throwable $e) {
            $ok = false;
        }
        if (!$ok) {
            $this->hapusFoto($uploaded);
            $this->renderPenyewa('ubah', array_merge($lama, $data), 'Data penyewa gagal diperbarui.');
            return;
        }
        $this->hapusFoto($fotoLama); // foto lama dihapus setelah DB sukses
        set_flash_message('success', 'Data penyewa berhasil diperbarui.');
        $this->redirect(); // PRG
    }

    // DELETE (hanya via POST)
    public function hapus(string $userPlg): void
    {
        require_admin_login();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Metode request tidak diizinkan.');
        }
        $userPlg = rawurldecode($userPlg);
        $penyewa = $this->model->findByUserPlg($userPlg);
        if ($penyewa === null) {
            set_flash_message('danger', 'Data penyewa tidak ditemukan.');
            $this->redirect();
        }
        try {
            $ok = $this->model->delete($userPlg);
        } catch (Throwable $e) {
            $ok = false; // mis. masih dipakai tabel transaksi (foreign key)
        }
        if (!$ok) {
            set_flash_message('danger', 'Data penyewa gagal dihapus. Kemungkinan masih terkait data transaksi.');
            $this->redirect();
        }
        $this->hapusFoto([$penyewa['ft_ktp'] ?? null, $penyewa['ft_sim_a'] ?? null]);
        set_flash_message('success', 'Data penyewa berhasil dihapus.');
        $this->redirect(); // PRG
    }
}