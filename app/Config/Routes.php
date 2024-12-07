<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ConvocatoriasPublicas::index');
$routes->get('/admin', 'Login::index');
