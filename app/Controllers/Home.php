<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function testdb()
{
    try {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT 1 as test");
        $result = $query->getRow();

        echo "Conexión exitosa 🚀";
        print_r($result);

    } catch (\Throwable $e) {
        echo "Error de conexión ❌<br>";
        echo $e->getMessage();
        echo env('database.default.hostname');
    }
}
}
