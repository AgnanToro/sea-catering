<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Web Routes
$routes->get('/', 'WebController::index');
$routes->get('menu', 'WebController::menu');
$routes->get('subscription', 'WebController::subscription');
$routes->get('contact', 'WebController::contact');
$routes->get('login', 'WebController::login');
$routes->post('login', 'WebController::processLogin');
$routes->get('register', 'WebController::register');
$routes->post('register', 'WebController::processRegister');
$routes->get('dashboard', 'WebController::dashboard');
$routes->get('dashboard/admin', 'WebController::adminDashboard');
$routes->get('logout', 'WebController::logout');

// API Routes
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function($routes) {
    
    // Authentication routes (no auth required)
    $routes->group('auth', static function($routes) {
        $routes->post('login', 'AuthController::login');
        $routes->post('register', 'AuthController::register');
        $routes->post('logout', 'AuthController::logout', ['filter' => 'auth']);
        $routes->get('profile', 'AuthController::profile', ['filter' => 'auth']);
    });
    
    // Contact routes
    $routes->post('contact', 'ContactController::create'); // Public endpoint
    $routes->group('contact', ['filter' => 'admin'], static function($routes) {
        $routes->get('/', 'ContactController::index');
        $routes->get('stats', 'ContactController::stats');
        $routes->get('unread-count', 'ContactController::unreadCount');
        $routes->get('(:num)', 'ContactController::show/$1');
        $routes->patch('(:num)/status', 'ContactController::updateStatus/$1');
        $routes->delete('(:num)', 'ContactController::delete/$1');
    });
    
    // Subscription routes (auth required)
    $routes->group('subscriptions', ['filter' => 'auth'], static function($routes) {
        $routes->get('/', 'SubscriptionController::index');
        $routes->get('stats', 'SubscriptionController::stats', ['filter' => 'admin']);
        $routes->get('(:num)', 'SubscriptionController::show/$1');
        $routes->post('/', 'SubscriptionController::create');
        $routes->put('(:num)', 'SubscriptionController::update/$1');
        $routes->delete('(:num)', 'SubscriptionController::delete/$1');
        $routes->patch('(:num)/status', 'SubscriptionController::updateStatus/$1', ['filter' => 'admin']);
    });
    
});

// Add CORS to all API routes
$routes->group('api', ['filter' => 'corsfilter'], static function($routes) {
    // This ensures CORS is applied to all API routes
});
