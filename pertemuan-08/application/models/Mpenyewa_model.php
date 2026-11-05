<?php
// UTS: Model Manajemen Penyewa (sisi Admin) - semua query memakai prepared statement
class Mpenyewa_model
{
    private mysqli $db;

    public function __construct()
    {
        global $db;
        $this->db = $db;
    }

    // READ: seluruh data penyewa (kolom sandi sengaja tidak diambil)
    public function getAll(): array
    {
        $sql = "SELECT user_plg, nik, nm_lngkp, no_hp, almt_lngkp, ft_ktp, ft_sim_a
                FROM t_penyewa
                ORDER BY user_plg ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    // READ: satu penyewa berdasarkan username
    public function findByUserPlg(string $userPlg): ?array
    {
        $sql = "SELECT user_plg, nik, nm_lngkp, no_hp, almt_lngkp, ft_ktp, ft_sim_a
                FROM t_penyewa WHERE user_plg = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $userPlg);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    // CREATE: $data['sandi'] sudah berupa HASH
    public function create(array $data): bool
    {
        $sql = "INSERT INTO t_penyewa
                (user_plg, sandi, nik, nm_lngkp, no_hp, almt_lngkp, ft_ktp, ft_sim_a)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            'ssssssss',
            $data['user_plg'],
            $data['sandi'],
            $data['nik'],
            $data['nm_lngkp'],
            $data['no_hp'],
            $data['almt_lngkp'],
            $data['ft_ktp'],
            $data['ft_sim_a']
        );
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // UPDATE: sandi hanya diubah jika $sandiHash tidak null
    public function update(string $userPlg, array $data, ?string $sandiHash = null): bool
    {
        if ($sandiHash !== null) {
            $sql = "UPDATE t_penyewa SET sandi = ?, nik = ?, nm_lngkp = ?, no_hp = ?,
                    almt_lngkp = ?, ft_ktp = ?, ft_sim_a = ? WHERE user_plg = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param(
                'ssssssss',
                $sandiHash,
                $data['nik'],
                $data['nm_lngkp'],
                $data['no_hp'],
                $data['almt_lngkp'],
                $data['ft_ktp'],
                $data['ft_sim_a'],
                $userPlg
            );
        } else {
            $sql = "UPDATE t_penyewa SET nik = ?, nm_lngkp = ?, no_hp = ?,
                    almt_lngkp = ?, ft_ktp = ?, ft_sim_a = ? WHERE user_plg = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param(
                'sssssss',
                $data['nik'],
                $data['nm_lngkp'],
                $data['no_hp'],
                $data['almt_lngkp'],
                $data['ft_ktp'],
                $data['ft_sim_a'],
                $userPlg
            );
        }
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // DELETE
    public function delete(string $userPlg): bool
    {
        $stmt = $this->db->prepare("DELETE FROM t_penyewa WHERE user_plg = ?");
        $stmt->bind_param('s', $userPlg);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}