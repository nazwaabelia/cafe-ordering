<?php

namespace App\Controllers;

use App\Models\MenuModel;

class Home extends BaseController
{
    protected $menuModel;
    
    public function __construct()
    {
        $this->menuModel = new MenuModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Menu Kafe',
            'menuTeh' => $this->menuModel->where('kategori', 'teh')->findAll(),
            'menuCookies' => $this->menuModel->where('kategori', 'cookies')->findAll()
        ];
        
        return view('customer/menu', $data);
    }
    
    public function getMenuById($id)
    {
        $menu = $this->menuModel->find($id);
        
        if ($menu) {
            return $this->response->setJSON($menu);
        }
        
        return $this->response->setJSON(['error' => 'Menu tidak ditemukan'], 404);
    }
}