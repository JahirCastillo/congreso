<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\AccesoModel;
use Config\Services;

class ModuloFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cargar el modelo para obtener los módulos
        // $accesoModel = new AccesoModel();
        // $modulos     = $accesoModel->obtieneModulos();

        // // Hacer que los módulos estén disponibles para todas las vistas
        // $renderer = Services::renderer();
        // $renderer->setVar('modulos', $modulos);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No necesitamos hacer nada después
    }
}


?>