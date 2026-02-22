<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- RUTAS PÚBLICAS / AUTH ---
$routes->get('/', 'Auth::login'); // Si entran a la raíz sin sesión, al login
$routes->get('login', 'Auth::login');
$routes->post('auth/intentarLogin', 'Auth::intentarLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('seed/crear', 'Seed::crear');
$routes->get('suscripcion-vencida', 'Turnos::vencido'); // Pantalla de bloqueo de pago

// --- PANEL DE SUPERADMIN (Solo protegido por Auth) ---
$routes->group('superadmin', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Superadmin::index');
    $routes->post('guardarClinica', 'Superadmin::guardarClinica');
    $routes->get('usuarios', 'Superadmin::usuarios');
    $routes->get('resetPassword/(:num)', 'Superadmin::resetPassword/$1');
    $routes->post('renovar-plan', 'Superadmin::renovarPlan'); // Nueva ruta para suscripciones
});

// --- MÓDULOS DE CLÍNICA (Protegidos por Auth Y Pago) ---
$routes->group('', ['filter' => ['auth', 'checkPago']], function ($routes) {

    // Dashboard / Agenda
    $routes->get('turnos', 'Turnos::index');
    $routes->post('turnos/agendar', 'Turnos::agendar');
    $routes->get('turnos/eventos', 'Turnos::listarEventos');
    $routes->post('turnos/actualizar', 'Turnos::actualizarEvento');
    $routes->post('turnos/eliminar/(:num)', 'Turnos::eliminar/$1');
    $routes->post('turnos/cambiarEstado', 'Turnos::cambiarEstado');
    $routes->get('turnos/obtenerPendientesNotificar', 'Turnos::obtenerPendientesNotificar');
    $routes->post('turnos/marcarComoNotificado/(:num)', 'Turnos::marcarComoNotificado/$1');

    // Gestión de Profesionales
    $routes->group('profesionales', function ($routes) {
        $routes->get('/', 'Profesionales::index');
        $routes->post('guardar', 'Profesionales::guardar');
        $routes->post('editar/(:num)', 'Profesionales::editar/$1');
        $routes->get('eliminar/(:num)', 'Profesionales::eliminar/$1');
        $routes->get('inactivos', 'Profesionales::inactivos');
        $routes->get('restaurar/(:num)', 'Profesionales::restaurar/$1');
    });

    // Gestión de Pacientes
    $routes->group('pacientes', function ($routes) {
        $routes->get('/', 'Pacientes::index');
        $routes->get('inactivos', 'Pacientes::inactivos');
        $routes->post('guardar', 'Pacientes::guardar');
        $routes->post('editar/(:num)', 'Pacientes::editar/$1');
        $routes->get('eliminar/(:num)', 'Pacientes::eliminar/$1');
        $routes->get('restaurar/(:num)', 'Pacientes::restaurar/$1');
        $routes->post('guardar_ajax', 'Pacientes::guardar_ajax');
    });

    // Gestión de Usuarios (Personal de la Clínica)
    $routes->group('usuarios', function ($routes) {
        $routes->get('/', 'Usuarios::index');
        $routes->post('guardar', 'Usuarios::guardar');
        $routes->post('editar/(:num)', 'Usuarios::editar/$1');
        $routes->get('eliminar/(:num)', 'Usuarios::eliminar/$1');
    });
});
