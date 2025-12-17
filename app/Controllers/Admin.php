<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\OrderModel;
use App\Models\MenuModel;

class Admin extends BaseController
{
    protected $adminModel;
    protected $orderModel;
    protected $menuModel;
    
    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->orderModel = new OrderModel();
        $this->menuModel = new MenuModel();
    }
    
    public function login()
    {
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/admin/dashboard');
        }
        
        return view('admin/login');
    }
    
    public function authenticate()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $admin = $this->adminModel->where('username', $username)->first();
        
        if ($admin && password_verify($password, $admin['password'])) {
            session()->set([
                'admin_id' => $admin['id'],
                'admin_username' => $admin['username'],
                'admin_logged_in' => true
            ]);
            
            return redirect()->to('/admin/dashboard');
        }
        
        return redirect()->back()->with('error', 'Username atau password salah');
    }
    
    public function dashboard()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }
        
        $data = [
            'title' => 'Dashboard Admin',
            'totalPesanan' => $this->orderModel->countAll(),
            'pesananMenunggu' => $this->orderModel->where('status', 'menunggu')->countAllResults(),
            'pesananHariIni' => $this->orderModel->where('DATE(waktu_pesan)', date('Y-m-d'))->countAllResults(),
            'orders' => $this->orderModel->orderBy('waktu_pesan', 'DESC')->findAll()
        ];
        
        foreach ($data['orders'] as &$order) {
            $order['items'] = json_decode($order['daftar_item'], true);
        }
        
        return view('admin/dashboard', $data);
    }
    
    public function manageMenu()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }
        
        $data = [
            'title' => 'Kelola Menu',
            'menus' => $this->menuModel->findAll()
        ];
        
        return view('admin/manage_menu', $data);
    }
    
    public function updateStock()
    {
        $id = $this->request->getPost('id');
        $stok = $this->request->getPost('stok');
        
        $menu = $this->menuModel->find($id);
        
        if (!$menu) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Menu tidak ditemukan'
            ]);
        }
        
        $data = [
            'stok' => $stok,
            'status' => $stok > 0 ? 'tersedia' : 'habis'
        ];
        
        if ($this->menuModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Stok berhasil diperbarui'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal memperbarui stok'
        ]);
    }
    
    public function deleteOrder($id)
    {
        $order = $this->orderModel->find($id);
        
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ]);
        }
        
        // Kembalikan stok
        $items = json_decode($order['daftar_item'], true);
        
        foreach ($items as $item) {
            $menu = $this->menuModel->find($item['id']);
            if ($menu) {
                $newStok = $menu['stok'] + $item['qty'];
                $this->menuModel->update($menu['id'], [
                    'stok' => $newStok,
                    'status' => $newStok > 0 ? 'tersedia' : 'habis'
                ]);
            }
        }
        
        // Hapus pesanan
        if ($this->orderModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pesanan berhasil dihapus dan stok dikembalikan'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menghapus pesanan'
        ]);
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login');
    }
}