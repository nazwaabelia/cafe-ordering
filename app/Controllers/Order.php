<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\MenuModel;

class Order extends BaseController
{
    protected $orderModel;
    protected $menuModel;
    
    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->menuModel = new MenuModel();
    }
    
    public function create()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'nama_pelanggan' => 'requir,ed|min_length[3]',
            'nomor_meja' => 'required',
            'tipe_pesanan' => 'required|in_list[dine-in,take-away]',
            'items' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validation->getErrors()
            ]);
        }
        
        $items = json_decode($this->request->getPost('items'), true);
        
        if (empty($items)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Keranjang pesanan kosong'
            ]);
        }
        
        // Validasi stok
        $totalHarga = 0;
        foreach ($items as $item) {
            $menu = $this->menuModel->find($item['id']);
            
            if (!$menu || $menu['stok'] < $item['qty']) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Stok ' . ($menu['nama_menu'] ?? 'menu') . ' tidak mencukupi'
                ]);
            }
            
            $totalHarga += $menu['harga'] * $item['qty'];
            
            // Kurangi stok
            $this->menuModel->update($menu['id'], [
                'stok' => $menu['stok'] - $item['qty'],
                'status' => ($menu['stok'] - $item['qty']) <= 0 ? 'habis' : 'tersedia'
            ]);
        }
        
        $data = [
            'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
            'nomor_meja' => $this->request->getPost('nomor_meja'),
            'tipe_pesanan' => $this->request->getPost('tipe_pesanan'),
            'daftar_item' => json_encode($items),
            'total_harga' => $totalHarga,
            'status' => 'menunggu'
        ];
        
        if ($this->orderModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pesananmu telah diterima! Silakan menuju kasir untuk melakukan pembayaran.',
                'order_id' => $this->orderModel->insertID()
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal membuat pesanan'
        ]);
    }
    
    public function getAll()
    {
        $orders = $this->orderModel->orderBy('waktu_pesan', 'DESC')->findAll();
        
        foreach ($orders as &$order) {
            $order['items'] = json_decode($order['daftar_item'], true);
        }
        
        return $this->response->setJSON($orders);
    }
    
    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        
        $validStatus = ['menunggu', 'diterima', 'disiapkan', 'siap', 'selesai'];
        
        if (!in_array($status, $validStatus)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Status tidak valid'
            ]);
        }
        
        if ($this->orderModel->update($id, ['status' => $status])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status pesanan berhasil diubah'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal mengubah status'
        ]);
    }
}