<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 
$routes->get('/', 'LandingPage::index');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::attemptRegister');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Dashboard::index');
$routes->get('monitoring', 'Monitoring::ultrasonik');
$routes->get('monitoring2', 'Monitoring::dht11');
$routes->get('monitoring3', 'Monitoring::ldr');
$routes->get('monitoring', 'Monitoring::allsensor');
$routes->get('/monitoring/allsensor', 'Monitoring::allsensor');
$routes->group('api/user', function($routes) {
    $routes->get('profile', 'UserController::profile');
    $routes->put('update', 'UserController::updateProfile');
});



