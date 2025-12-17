<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Customer Routes
$routes->get('/', 'Home::index');
$routes->get('menu', 'Home::index');
$routes->get('menu/(:num)', 'Home::getMenuById/$1');

// Order Routes
$routes->post('order/create', 'Order::create');
$routes->get('order/all', 'Order::getAll');
$routes->post('order/update-status/(:num)', 'Order::updateStatus/$1');

// Admin Routes
$routes->get('admin', 'Admin::login');
$routes->get('admin/login', 'Admin::login');
$routes->post('admin/authenticate', 'Admin::authenticate');
$routes->get('admin/dashboard', 'Admin::dashboard');
$routes->get('admin/manage-menu', 'Admin::manageMenu');
$routes->post('admin/update-stock', 'Admin::updateStock');
$routes->post('admin/delete-order/(:num)', 'Admin::deleteOrder/$1');
$routes->get('admin/logout', 'Admin::logout');