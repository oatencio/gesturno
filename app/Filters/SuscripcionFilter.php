<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SuscripcionFilter implements FilterInterface {
    public function before(RequestInterface $request, $arguments = null) {
        $session = session();
        
        // 1. Si no hay sesión, no hacemos nada (el filtro Auth se encarga)
        if (!$session->get('isLoggedIn')) return;

        // 2. EXCEPCIONES: No aplicar filtro si va a la página de error, al logout o es Superadmin
        $uri = $request->getUri()->getPath();
        if ($uri === 'suscripcion-vencida' || $uri === 'logout' || $session->get('rol') === 'superadmin') {
            return;
        }

        $db = \Config\Database::connect();
        $clinica = $db->table('clinicas')->where('id', $session->get('clinica_id'))->get()->getRow();

        if (!$clinica) return;

        $hoy = date('Y-m-d');
        
        // 3. Lógica de bloqueo
        // Agregué 'vencido' a la condición para que si ya fue marcado así, lo bloquee de entrada
        if ($clinica->estado === 'vencido' || $clinica->estado === 'suspendido' || $clinica->fecha_vencimiento < $hoy) {
            
            // Si la fecha venció hoy pero el estado seguía 'activo', actualizamos la base de datos
            if ($clinica->fecha_vencimiento < $hoy && $clinica->estado === 'activo') {
                $db->table('clinicas')->where('id', $clinica->id)->update(['estado' => 'vencido']);
            }

            return redirect()->to(base_url('suscripcion-vencida'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}