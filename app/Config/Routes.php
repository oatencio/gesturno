<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('turnos', 'Turnos::index');
$routes->post('turnos/agendar', 'Turnos::agendar');
$routes->get('turnos/eventos', 'Turnos::listarEventos');
$routes->post('turnos/actualizar', 'Turnos::actualizarEvento');
$routes->post('turnos/eliminar/(:num)', 'Turnos::eliminar/$1');
$routes->post('turnos/cambiarEstado', 'Turnos::cambiarEstado');

$routes->get('login', 'Auth::login');
$routes->post('auth/intentarLogin', 'Auth::intentarLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('seed/crear', 'Seed::crear');


$routes->get('profesionales', 'Profesionales::index');
$routes->post('profesionales/guardar', 'Profesionales::guardar');
$routes->post('profesionales/editar/(:num)', 'Profesionales::editar/$1');
$routes->get('profesionales/eliminar/(:num)', 'Profesionales::eliminar/$1');
$routes->get('profesionales/inactivos', 'Profesionales::inactivos');
$routes->get('profesionales/restaurar/(:num)', 'Profesionales::restaurar/$1');

$routes->get('pacientes', 'Pacientes::index');
$routes->get('pacientes/inactivos', 'Pacientes::inactivos');
$routes->post('pacientes/guardar', 'Pacientes::guardar');
$routes->post('pacientes/editar/(:num)', 'Pacientes::editar/$1');
$routes->get('pacientes/eliminar/(:num)', 'Pacientes::eliminar/$1');
$routes->get('pacientes/restaurar/(:num)', 'Pacientes::restaurar/$1');
$routes->post('pacientes/guardar_ajax', 'Pacientes::guardar_ajax');
