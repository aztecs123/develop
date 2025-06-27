<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/invite', 'InviteController::send');
$routes->get('/test-db', 'TestDB::index');
$routes->get('/cities', 'CityController::index');
$routes->get('/invite', 'InviteController::form');
$routes->post('/invite', 'InviteController::send');
$routes->post('/invite/preview', 'InviteController::preview');
$routes->post('/invite/import-docx', 'InviteController::importDocx');
$routes->get('/templates', 'TemplateController::index');
$routes->get('/templates/delete/(:any)', 'TemplateController::delete/$1');