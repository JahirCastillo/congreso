<?php

namespace App\Controllers;
use App\Models\PonenciasModel;

class Inicio extends BaseController
{
    protected $ponenciasModel;
    public function __construct()
    {
        if (!session()->has('nombre')) {
            redirect()->to('')->send();
            exit;
        }
        if (session('rol') == 2) {
            redirect()->to('revisor')->send();
            exit;
        } elseif (session('rol') != 1) {
            redirect()->to('')->send();
            exit;
        }
        $this->ponenciasModel = model(PonenciasModel::class);
    }
    public function index()
    {

        $datosVistaInicio['conteosPonencias'] = $this->ponenciasModel->obtenerConteosPonencias();
        return view('inicio', $datosVistaInicio);
    }
}
