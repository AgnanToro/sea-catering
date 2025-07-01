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
$routes->get('admin/dashboard', 'WebController::adminDashboard');
$routes->get('logout', 'WebController::logout');
$routes->post('logout', 'WebController::logout'); // Support both GET and POST

// Subscription routes (session-based)
$routes->post('subscription', 'WebController::createSubscription');

// Admin-only routes
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('export', 'WebController::exportData');
    $routes->post('report', 'WebController::generateReport');
});
$routes->group('download', ['filter' => 'admin'], function($routes) {
    $routes->get('report/(:any)', 'WebController::downloadReport/$1');
});

// API Routes
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    
    // Authentication routes (no auth required)
    $routes->group('auth', function($routes) {
        $routes->post('login', 'AuthController::login');
        $routes->post('register', 'AuthController::register');
        $routes->post('logout', 'AuthController::logout', ['filter' => 'auth']);
        $routes->get('profile', 'AuthController::profile', ['filter' => 'auth']);
    });
    
    // Contact routes
    $routes->post('contact', 'ContactController::create'); // Public endpoint
    $routes->group('contact', ['filter' => 'admin'], function($routes) {
        $routes->get('/', 'ContactController::index');
        $routes->get('stats', 'ContactController::stats');
        $routes->get('unread-count', 'ContactController::unreadCount');
        $routes->get('(:num)', 'ContactController::show/$1');
        $routes->patch('(:num)/status', 'ContactController::updateStatus/$1');
        $routes->delete('(:num)', 'ContactController::delete/$1');
    });
    
    // Subscription routes (auth required)
    $routes->group('subscriptions', ['filter' => 'auth'], function($routes) {
        $routes->get('/', 'SubscriptionController::index');
        $routes->get('stats', 'SubscriptionController::stats', ['filter' => 'admin']);
        $routes->get('(:num)', 'SubscriptionController::show/$1');
        $routes->post('/', 'SubscriptionController::create');
        $routes->put('(:num)', 'SubscriptionController::update/$1');
        $routes->delete('(:num)', 'SubscriptionController::delete/$1');
        $routes->patch('(:num)/status', 'SubscriptionController::updateStatus/$1', ['filter' => 'admin']);
        $routes->post('(:num)/status', 'SubscriptionController::updateStatus/$1', ['filter' => 'admin']); // Support POST with method override
    });
    
    // Debug API routes (no auth required)
    $routes->group('debug', function($routes) {
        $routes->get('subscription/(:num)', 'DebugApiController::getSubscription/$1');
        $routes->get('session', 'DebugApiController::checkSession');
        $routes->get('auth', 'DebugApiController::testAuth', ['filter' => 'auth']);
    });
    
});

// Add CORS to all API routes
$routes->group('api', ['filter' => 'corsfilter'], function($routes) {
    // This ensures CORS is applied to all API routes
});

// Test routes untuk debugging
$routes->get('test', 'TestController::index');
$routes->get('test/db', 'TestController::dbTest');
$routes->get('test/login', 'TestController::loginTest');
$routes->get('test/session', 'TestController::sessionTest');
$routes->get('test/api', 'TestController::apiTest');

// Debug routes
$routes->get('debug/subscription/(:num)', 'DebugController::subscriptionDetail/$1');
$routes->get('debug/session', 'DebugController::sessionData');
$routes->get('debug/login-test', 'LoginDebugController::testLogin');
$routes->get('test/admin-login', 'WebController::testAdminLogin');
