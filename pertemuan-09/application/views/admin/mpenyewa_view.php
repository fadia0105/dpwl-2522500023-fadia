<?php
require APPPATH . '/views/templates/header.php';
require APPPATH . '/views/templates/sidebar.php';

$e = static fn($v): string => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');

$penyewa = $penyewa ?? [];
$flash = $flash ?? null;
$error = $error ?? null;
$mode = $mode ?? null;
$dipilih = $penyewa_dipilih ?? null;
$isForm = in_array($mode, ['tambah', 'ubah'], true);
$isEdit = $mode === 'ubah';
$userDipilih = $dipilih['user_plg'] ?? '';
$formAction = $isEdit
    ? site_url('admin/penyewa/ubah/' . rawurlencode((string) $userDipilih))
    : site_url('admin/penyewa/tambah');

// Ringkasan untuk kotak statistik
$total = count($penyewa);
$adaKtp = 0;
$adaSim = 0;
foreach ($penyewa as $p) {
    if (!empty($p['ft_ktp'])) { $adaKtp++; }
    if (!empty($p['ft_sim_a'])) { $adaSim++; }
}
$fotoUrl = static fn(?string $f): string => base_url('uploads/penyewa/' . rawurlencode((string) $f));
?>
<style>
.pw-hero { background: linear-gradient(120deg, #00695c, #26a69a); color: #fff; padding: 18px 22px; border-radius: 4px; margin-bottom: 18px; }
.pw-hero h3 { margin: 0 0 4px; font-weight: 600; }
.pw-hero p { margin: 0; opacity: .9; }
.pw-stat { background: #fff; border-radius: 4px; padding: 14px 16px; margin-bottom: 15px; border-left: 4px solid #26a69a; box-shadow: 0 1px 2px rgba(0,0,0,.12); }
.pw-stat.b2 { border-left-color: #5c6bc0; }
.pw-stat.b3 { border-left-color: #ef6c00; }
.pw-stat .num { font-size: 26px; font-weight: 700; line-height: 1; }
.pw-stat .lbl { color: #777; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; }
.pw-avatar { width: 38px; height: 38px; border-radius: 50%; background: #26a69a; color: #fff; display: inline-block; text-align: center; line-height: 38px; font-weight: 700; margin-right: 10px; vertical-align: middle; }
.pw-name { font-weight: 600; }
.pw-sub { color: #888; font-size: 12px; }
.pw-doc { display: inline-block; margin: 1px 2px; cursor: pointer; }
.pw-section-title { font-size: 13px; text-transform: uppercase; letter-spacing: .6px; color: #00796b; border-bottom: 2px solid #b2dfdb; padding-bottom: 6px; margin: 0 0 14px; }
.pw-docbox { border: 1px dashed #b0bec5; border-radius: 4px; padding: 10px; margin-bottom: 12px; background: #fafafa; }
</style>

<div class="content-wrapper">
<section class="content-header">
<h1>Data Penyewa <small>pusat pengelolaan akun &amp; dokumen penyewa</small></h1>
<ol class="breadcrumb">
<li><a href="<?= $e(site_url('admin')) ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
<li class="active">Manajemen Penyewa</li>
</ol>
</section>

<section class="content">

<div class="pw-hero">
<div class="row">
<div class="col-sm-9">
<h3><i class="fa fa-id-card-o"></i> Manajemen Penyewa</h3>
<p>Kelola akun, identitas, serta dokumen KTP dan SIM A penyewa kendaraan.</p>
</div>
<div class="col-sm-3 text-right">
<a href="<?= $e(site_url('admin/penyewa/tambah')) ?>" class="btn btn-default" style="margin-top:6px;">
<i class="fa fa-user-plus"></i> Penyewa Baru
</a>
</div>
</div>
</div>

<?php if (!empty($flash)): ?>
<?php $ok = (($flash['type'] ?? '') === 'success'); ?>
<div class="alert <?= $ok ? 'alert-success' : 'alert-danger' ?> alert-dismissible">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<i class="fa <?= $ok ? 'fa-check-circle' : 'fa-exclamation-triangle' ?>"></i>
<?= $e($flash['message'] ?? '') ?>
</div>
<?php endif; ?>

<?php if ($error !== null): ?>
<div class="alert alert-danger alert-dismissible">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<i class="fa fa-exclamation-triangle"></i> <?= $e($error) ?>
</div>
<?php endif; ?>

<div class="row">
<div class="col-sm-4"><div class="pw-stat"><div class="num"><?= (int) $total ?></div><div class="lbl">Total penyewa</div></div></div>
<div class="col-sm-4"><div class="pw-stat b2"><div class="num"><?= (int) $adaKtp ?></div><div class="lbl">KTP sudah diunggah</div></div></div>
<div class="col-sm-4"><div class="pw-stat b3"><div class="num"><?= (int) $adaSim ?></div><div class="lbl">SIM A sudah diunggah</div></div></div>
</div>

<?php if ($isForm): ?>
<div class="box box-success">
<div class="box-header with-border">
<h3 class="box-title">
<i class="fa <?= $isEdit ? 'fa-pencil' : 'fa-user-plus' ?>"></i>
<?= $isEdit ? 'Ubah Penyewa: ' . $e($userDipilih) : 'Pendaftaran Penyewa Baru' ?>
</h3>
</div>
<form role="form" method="post" enctype="multipart/form-data" action="<?= $e($formAction) ?>" autocomplete="off">
<div class="box-body">
<div class="row">

<div class="col-md-6">
<h4 class="pw-section-title">Data Diri</h4>
<div class="form-group">
<label for="nm_lngkp">Nama Lengkap</label>
<input type="text" class="form-control" id="nm_lngkp" name="nm_lngkp" maxlength="100"
value="<?= $e($dipilih['nm_lngkp'] ?? '') ?>" required>
</div>
<div class="form-group">
<label for="nik">NIK <span id="nikCount" class="text-muted" style="font-weight:normal;">(0/16)</span></label>
<input type="text" class="form-control" id="nik" name="nik" maxlength="16" pattern="[0-9]{16}"
inputmode="numeric" value="<?= $e($dipilih['nik'] ?? '') ?>" required>
</div>
<div class="form-group">
<label for="no_hp">Nomor HP</label>
<div class="input-group">
<span class="input-group-addon"><i class="fa fa-phone"></i></span>
<input type="text" class="form-control" id="no_hp" name="no_hp" maxlength="15"
value="<?= $e($dipilih['no_hp'] ?? '') ?>" required>
</div>
</div>
<div class="form-group">
<label for="almt_lngkp">Alamat Lengkap</label>
<textarea class="form-control" id="almt_lngkp" name="almt_lngkp" maxlength="300" rows="4" required><?= $e($dipilih['almt_lngkp'] ?? '') ?></textarea>
</div>
</div>

<div class="col-md-6">
<h4 class="pw-section-title">Akun Login</h4>
<div class="form-group">
<label for="user_plg">Username</label>
<input type="text" class="form-control" id="user_plg" name="user_plg" maxlength="15"
pattern="[A-Za-z0-9._\-]{1,15}" value="<?= $e($dipilih['user_plg'] ?? '') ?>"
<?= $isEdit ? 'readonly' : '' ?> required>
</div>
<div class="form-group">
<label for="sandi">Password</label>
<div class="input-group">
<input type="password" class="form-control" id="sandi" name="sandi" minlength="8" maxlength="72"
autocomplete="new-password" <?= $isEdit ? '' : 'required' ?>>
<span class="input-group-btn">
<button type="button" class="btn btn-default" id="togglePw" title="Tampilkan/sembunyikan"><i class="fa fa-eye"></i></button>
</span>
</div>
<p class="help-block"><?= $isEdit ? 'Kosongkan bila tidak ingin mengganti. ' : '' ?>Minimal 8 karakter, disimpan sebagai hash.</p>
</div>

<h4 class="pw-section-title" style="margin-top:20px;">Dokumen</h4>
<?php foreach (['ft_ktp' => 'Foto KTP', 'ft_sim_a' => 'Foto SIM A'] as $field => $label): ?>
<div class="pw-docbox form-group">
<label for="<?= $e($field) ?>"><?= $e($label) ?></label>
<input type="file" id="<?= $e($field) ?>" name="<?= $e($field) ?>"
accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
<p class="help-block" style="margin-bottom:6px;">JPG / PNG / WEBP, maksimal 5 MB.</p>
<?php if ($isEdit && !empty($dipilih[$field]) && is_string($dipilih[$field])): ?>
<small class="text-muted">Tersimpan saat ini:</small><br>
<img class="img-thumbnail" alt="<?= $e($label) ?> saat ini" src="<?= $e($fotoUrl($dipilih[$field])) ?>"
style="width:140px;height:90px;object-fit:cover;">
<?php else: ?>
<small class="text-muted">Belum ada foto tersimpan.</small>
<?php endif; ?>
<div class="new-photo-preview" style="display:none; margin-top:8px;">
<small class="text-muted">Pratinjau pilihan baru:</small><br>
<img class="img-thumbnail new-photo-image" alt="Pratinjau <?= $e($label) ?>" style="width:140px;height:90px;object-fit:cover;">
</div>
</div>
<?php endforeach; ?>
</div>

</div>
</div>
<div class="box-footer">
<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Daftarkan Penyewa' ?></button>
<a href="<?= $e(site_url('admin/penyewa')) ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali ke daftar</a>
</div>
</form>
</div>
<?php endif; ?>

<div class="box box-solid">
<div class="box-header with-border">
<h3 class="box-title"><i class="fa fa-list"></i> Daftar Penyewa</h3>
</div>
<div class="box-body">
<div class="table-responsive">
<table id="penyewaTable" class="table table-hover js-data-table">
<thead>
<tr><th>#</th><th>Penyewa</th><th>NIK</th><th>Kontak</th><th>Alamat</th><th>Dokumen</th><th class="text-right">Aksi</th></tr>
</thead>
<tbody>
<?php foreach ($penyewa as $no => $item): ?>
<tr>
<td><?= (int) $no + 1 ?></td>
<td>
<span class="pw-avatar"><?= $e(mb_strtoupper(mb_substr((string) $item['nm_lngkp'], 0, 1))) ?></span>
<span style="display:inline-block;vertical-align:middle;">
<span class="pw-name"><?= $e($item['nm_lngkp']) ?></span><br>
<span class="pw-sub">@<?= $e($item['user_plg']) ?></span>
</span>
</td>
<td><?= $e($item['nik']) ?></td>
<td><i class="fa fa-phone text-muted"></i> <?= $e($item['no_hp']) ?></td>
<td><?= $e($item['almt_lngkp']) ?></td>
<td>
<?php foreach (['ft_ktp' => 'KTP', 'ft_sim_a' => 'SIM A'] as $f => $lbl): ?>
<?php if (!empty($item[$f])): ?>
<span class="label label-success pw-doc" data-toggle="modal" data-target="#modalFoto"
data-src="<?= $e($fotoUrl($item[$f])) ?>" data-judul="<?= $e($lbl . ' - ' . $item['nm_lngkp']) ?>">
<i class="fa fa-check"></i> <?= $lbl ?>
</span>
<?php else: ?>
<span class="label label-default pw-doc" style="cursor:default;"><i class="fa fa-times"></i> <?= $lbl ?></span>
<?php endif; ?>
<?php endforeach; ?>
</td>
<td class="text-right" style="white-space:nowrap;">
<a class="btn btn-default btn-sm" title="Ubah"
href="<?= $e(site_url('admin/penyewa/ubah/' . rawurlencode((string) $item['user_plg']))) ?>">
<i class="fa fa-pencil text-orange"></i>
</a>
<button type="button" class="btn btn-default btn-sm" title="Hapus" data-toggle="modal" data-target="#modalHapus"
data-nama="<?= $e($item['nm_lngkp']) ?>"
data-action="<?= $e(site_url('admin/penyewa/hapus/' . rawurlencode((string) $item['user_plg']))) ?>">
<i class="fa fa-trash text-red"></i>
</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
</div>

<!-- Modal lihat foto -->
<div class="modal fade" id="modalFoto" tabindex="-1" role="dialog">
<div class="modal-dialog"><div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title" id="modalFotoJudul">Dokumen</h4>
</div>
<div class="modal-body text-center"><img id="modalFotoImg" alt="Dokumen penyewa" style="max-width:100%;max-height:70vh;"></div>
</div></div>
</div>

<!-- Modal konfirmasi hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" role="dialog">
<div class="modal-dialog modal-sm"><div class="modal-content">
<form method="post" id="formHapus" action="#">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title"><i class="fa fa-warning text-red"></i> Hapus penyewa?</h4>
</div>
<div class="modal-body">
Data <strong id="hapusNama"></strong> beserta foto KTP dan SIM A akan dihapus permanen.
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
<button type="submit" class="btn btn-danger">Ya, hapus</button>
</div>
</form>
</div></div>
</div>

</section>
</div>
<?php require APPPATH . '/views/templates/footer.php'; ?>
<script>
(function () {
  'use strict';
  // Modal foto dan hapus (jQuery & Bootstrap dimuat oleh footer)
  $('#modalFoto').on('show.bs.modal', function (ev) {
    var t = $(ev.relatedTarget);
    $('#modalFotoImg').attr('src', t.data('src'));
    $('#modalFotoJudul').text(t.data('judul'));
  });
  $('#modalHapus').on('show.bs.modal', function (ev) {
    var t = $(ev.relatedTarget);
    $('#hapusNama').text(t.data('nama'));
    $('#formHapus').attr('action', t.data('action'));
  });

<?php if ($isForm): ?>
  // Tampilkan/sembunyikan password
  var pw = document.getElementById('sandi');
  document.getElementById('togglePw').addEventListener('click', function () {
    pw.type = pw.type === 'password' ? 'text' : 'password';
  });
  // Penghitung digit NIK (hanya angka)
  var nik = document.getElementById('nik'), cnt = document.getElementById('nikCount');
  function hitungNik() {
    nik.value = nik.value.replace(/\D/g, '');
    cnt.textContent = '(' + nik.value.length + '/16)';
  }
  nik.addEventListener('input', hitungNik); hitungNik();
  // Pratinjau foto yang dipilih
  document.querySelectorAll('input[type="file"][name^="ft_"]').forEach(function (input) {
    var box = input.closest('.form-group');
    var preview = box.querySelector('.new-photo-preview');
    var image = preview.querySelector('.new-photo-image');
    var url = null;
    input.addEventListener('change', function () {
      if (url) { URL.revokeObjectURL(url); url = null; }
      var f = input.files && input.files.length ? input.files[0] : null;
      if (!f || ['image/jpeg', 'image/png', 'image/webp'].indexOf(f.type) === -1 || f.size < 1 || f.size > 5 * 1024 * 1024) {
        preview.style.display = 'none'; image.removeAttribute('src');
        if (f) { alert('Foto harus JPG/PNG/WEBP dan maksimal 5 MB.'); input.value = ''; }
        return;
      }
      url = URL.createObjectURL(f); image.src = url; preview.style.display = 'block';
    });
  });
<?php endif; ?>
})();
</script>