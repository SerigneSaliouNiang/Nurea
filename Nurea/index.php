<?php
declare(strict_types=1);

require_once __DIR__ . '/app/Core/Autoload.php';

$config = \App\Core\Container::get('config');
// Forcer l'affichage des erreurs PHP pour le diagnostic en production
error_reporting(E_ALL);
ini_set('display_errors', '1');
use App\Core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');
$router->get('/products', 'ProductController@index');
$router->get('/product', 'ProductController@show');
$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->post('/cart/remove', 'CartController@remove');
$router->get('/checkout', 'CheckoutController@index');
$router->post('/checkout', 'CheckoutController@placeOrder');
$router->get('/admin', 'Admin\\DashboardController@index');
$router->get('/admin/login', 'Admin\\AuthController@login');
$router->post('/admin/login', 'Admin\\AuthController@authenticate');
$router->post('/admin/logout', 'Admin\\AuthController@logout');

$router->get('/admin/categories', 'Admin\\CategoryController@index');
$router->get('/admin/categories/create', 'Admin\\CategoryController@create');
$router->post('/admin/categories/create', 'Admin\\CategoryController@store');
$router->get('/admin/categories/edit', 'Admin\\CategoryController@edit');
$router->post('/admin/categories/edit', 'Admin\\CategoryController@update');
$router->post('/admin/categories/delete', 'Admin\\CategoryController@delete');

$router->get('/admin/products', 'Admin\\ProductController@index');
$router->get('/admin/products/create', 'Admin\\ProductController@create');
$router->post('/admin/products/create', 'Admin\\ProductController@store');
$router->get('/admin/products/edit', 'Admin\\ProductController@edit');
$router->post('/admin/products/edit', 'Admin\\ProductController@update');
$router->post('/admin/products/delete', 'Admin\\ProductController@delete');

$router->get('/admin/orders', 'Admin\\OrderController@index');
$router->get('/admin/orders/show', 'Admin\\OrderController@show');
$router->post('/admin/orders/status', 'Admin\\OrderController@updateStatus');
$router->get('/admin/orders/export-payments', 'Admin\\OrderController@exportPayments');

$router->get('/admin/settings', 'Admin\\SettingController@index');
$router->post('/admin/settings/update', 'Admin\\SettingController@update');

// Admin users (admins + sellers)
$router->get('/admin/users', 'Admin\\UserController@index');
$router->get('/admin/users/create', 'Admin\\UserController@create');
$router->post('/admin/users/create', 'Admin\\UserController@store');
$router->get('/admin/users/edit', 'Admin\\UserController@edit');
$router->post('/admin/users/edit', 'Admin\\UserController@update');
$router->post('/admin/users/delete', 'Admin\\UserController@destroy');

// Seller auth (first-login password change flow)
$router->get('/seller/login', 'Seller\\AuthController@login');
$router->post('/seller/login', 'Seller\\AuthController@authenticate');
$router->post('/seller/logout', 'Seller\\AuthController@logout');
$router->get('/seller/change-password', 'Seller\\AuthController@showChangePassword');
$router->post('/seller/change-password', 'Seller\\AuthController@changePassword');

// Seller dashboard
$router->get('/seller', 'Seller\\DashboardController@index');
// Seller orders
$router->get('/seller/orders', 'Seller\\OrderController@index');
$router->get('/seller/orders/show', 'Seller\\OrderController@show');
$router->post('/seller/orders/update-status', 'Seller\\OrderController@updateStatus');
$router->post('/seller/orders/mark-paid', 'Seller\\OrderController@markPaid');

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
