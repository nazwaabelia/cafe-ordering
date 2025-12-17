<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_pelanggan',
        'nomor_meja',
        'tipe_pesanan',
        'daftar_item',
        'total_harga',
        'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'waktu_pesan';
    protected $updatedField = 'updated_at';
}